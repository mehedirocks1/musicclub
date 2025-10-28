<?php

namespace App\Filament\Resources\Members\BulkActions;

use Filament\Actions\BulkAction;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Collection;
use Modules\Members\Models\Member; // Assuming this model path is correct
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendDueBalanceRemindersBulkAction extends BulkAction
{
    protected ?string $name = 'send-due-balance-reminders';

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Send Due Balance Reminders')
            ->modalHeading('Send Due Balance Reminders')
            ->requiresConfirmation()
            ->form([
                Textarea::make('message')
                    ->label('Optional message')
                    ->rows(4)
                    ->maxLength(2000)
                    ->placeholder('Add an optional message to include in each reminder. The member name and calculated due amount will be added automatically.'),
            ])
            ->color('warning') // Changed color to warning for visibility
            ->icon('heroicon-o-paper-airplane'); // Added an icon
    }

    public function handle(Collection $records, array $data = null): void
    {
        // Fee Configuration (Must match MemberResource logic)
        $monthlyFee = 200.00;
        $yearlyFee = 2400.00;
        $registrationFee = 100.00; // One-time fee

        $message = $data['message'] ?? null;
        $now = Carbon::now();

        $sent = 0;
        $skipped = 0;
        $errors = [];

        foreach ($records as $record) {
            try {
                // Ensure we have a Member object
                $member = $record instanceof Member ? $record : Member::find($record->id ?? $record);
                if (!$member) {
                    $skipped++;
                    continue;
                }

                // --- 1. Calculate Required Fee (Total Debt) ---
                $totalFeeRequired = $registrationFee; 
                $registrationDate = Carbon::parse($member->registration_date);

                if ($member->membership_type === 'Monthly') {
                    $monthsPassed = $registrationDate->diffInMonths($now);
                    if ($now->day >= $registrationDate->day) {
                        $monthsPassed++;
                    }
                    $monthsPassed = max(0, $monthsPassed); 
                    $totalFeeRequired += $monthsPassed * $monthlyFee;

                } elseif ($member->membership_type === 'Yearly') {
                    $yearsPassed = $registrationDate->diffInYears($now);
                    if ($now->month > $registrationDate->month || ($now->month === $registrationDate->month && $now->day >= $registrationDate->day)) {
                        $yearsPassed++;
                    }
                    $yearsPassed = max(0, $yearsPassed); 
                    $totalFeeRequired += $yearsPassed * $yearlyFee;
                }

                // --- 2. Calculate Actual Due Amount (Total Debt - Paid Balance) ---
                $balancePaid = $member->balance ?? 0.00;
                $dueAmount = $totalFeeRequired - $balancePaid;

                // --- 3. Check for Due (Only send reminders for positive due amounts) ---
                if ($dueAmount <= 0) {
                    $skipped++;
                    continue;
                }
                
                $dueFormatted = number_format($dueAmount, 2);

                // --- 4. Prepare Reminder Message ---
                $reminder = "Dear {$member->full_name}, your calculated membership due balance is ৳{$dueFormatted}. Please pay as soon as possible.";
                if (!empty($message)) {
                    $reminder .= "\n\n" . $message;
                }

                // --- 5. Logging/Sending Placeholder ---
                
                // 📧 Email Placeholder
                if ($member->email) {
                    // TODO: Replace with actual Laravel Notification logic:
                    // $member->notify(new \App\Notifications\DueReminderNotification($dueFormatted)); 
                }

                // 📱 SMS Placeholder
                if ($member->phone) {
                    // TODO: Replace with actual SMS Service API call:
                    // (new \App\Services\SmsService())->send($member->phone, $reminder); 
                }
                
                Log::info('Due balance reminder prepared and logged', [
                    'member_id' => $member->id,
                    'email' => $member->email ?? null,
                    'phone' => $member->phone ?? null,
                    'due_amount' => $dueAmount,
                    'total_required' => $totalFeeRequired,
                    'balance_paid' => $balancePaid,
                    'reminder_preview' => $reminder,
                    'timestamp' => $now->toDateTimeString(),
                ]);

                $sent++;
            } catch (Throwable $e) {
                $errors[] = "Member #{$record->id}: " . $e->getMessage();
                Log::error('Error during due balance reminder preparation', [
                    'exception' => $e,
                    'record_id' => $record->id ?? 'N/A',
                ]);
            }
        }

        // --- 6. Send Final Notification to User ---
        if ($sent > 0) {
            $this->success("Sent reminders for {$sent} member(s). Skipped: {$skipped}.");
        } elseif ($skipped > 0 && $sent === 0) {
            $this->warning("No reminders were sent. {$skipped} selected member(s) had a cleared balance.");
        } else {
            $this->danger('No reminders were processed due to an unknown error or no members being selected.');
        }

        if (!empty($errors)) {
            Log::error('SendDueBalanceRemindersBulkAction completed with errors', ['errors' => $errors]);
        }
    }
}