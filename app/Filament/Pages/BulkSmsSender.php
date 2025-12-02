<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use DevWizard\Textify\Facades\Textify;
use App\Models\SmsLog;
use BackedEnum;

class BulkSmsSender extends Page implements HasForms
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Bulk SMS Sender';
    protected static ?string $title = 'Bulk SMS Sender';

    protected string $view = 'filament.pages.bulk-sms-sender';

    // Form inputs
    public ?string $numbers = '';
    public ?string $message = '';

    // Internal state for the sending process
    public array $cleanNumbersList = [];

    protected function getFormSchema(): array
    {
        return [
            Textarea::make('numbers')
                ->label('Phone Numbers')
                ->helperText('Paste numbers here. You can separate them using new lines, commas, or spaces.')
                ->rows(10)
                ->placeholder("01700000000\n01800000000, 01900000000")
                ->required(),
            Textarea::make('message')
                ->label('SMS Message')
                ->rows(4)
                ->required(),
        ];
    }

    /**
     * ✅ STEP 1: Prepare the list
     */
    public function prepareNumbers()
    {
        if (empty($this->numbers)) {
            Notification::make()->title('Please enter phone numbers')->warning()->send();
            return ['count' => 0];
        }

        // Split by Newline (\n), Comma (,), or Space (\s)
        $raw = preg_split('/[\r\n, ]+/', (string)$this->numbers, -1, PREG_SPLIT_NO_EMPTY);

        // Clean and Unique
        $this->cleanNumbersList = collect($raw)
            ->map(fn($n) => trim($n))
            ->filter(fn($n) => strlen($n) > 5) 
            ->unique()
            ->values()
            ->toArray();

        $count = count($this->cleanNumbersList);

        if ($count === 0) {
            Notification::make()->title('No valid numbers found')->danger()->send();
            return ['count' => 0];
        }

        return ['count' => $count];
    }

    /**
     * ✅ STEP 2: Send Single SMS (Fixed for your DB)
     */
    public function sendSingleSms($index)
    {
        if (empty($this->cleanNumbersList)) {
            $this->prepareNumbers(); 
        }

        if (!isset($this->cleanNumbersList[$index])) {
            return 'failed';
        }

        $phone = $this->cleanNumbersList[$index];
        $status = 'failed';
        $errorMsg = null;

        try {
            // Send API Request
            Textify::to($phone)->message($this->message)->via('bulksmsbd')->send();
            $status = 'sent';
        } catch (\Exception $e) {
            $status = 'failed';
            // Capture the specific error message for your 'error' column
            $errorMsg = $e->getMessage();
        }

        // ✅ DATABASE SAVE (Matched to your Schema)
        if (class_exists(SmsLog::class)) {
            SmsLog::create([
                'phone'   => $phone,           // Matches DB Name=phone
                'message' => $this->message,   // Matches DB Name=message
                'status'  => $status,          // Matches DB Name=status
                'error'   => $errorMsg,        // Matches DB Name=error
            ]);
        }

        return $status;
    }

    /**
     * ✅ STEP 3: Cleanup
     */
    public function sendingComplete()
    {
        Notification::make()
            ->title('Bulk Sending Completed')
            ->success()
            ->send();
    }
}