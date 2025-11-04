<?php

namespace App\Filament\Resources\Members;

use Maatwebsite\Excel\Facades\Excel;
use App\Filament\Resources\Members\BulkActions\SendDueBalanceRemindersBulkAction;
use App\Filament\Resources\Members\Pages\CreateMember;
use App\Filament\Resources\Members\Pages\EditMember;
use App\Filament\Resources\Members\Pages\ListMembers;
use App\Filament\Resources\Members\Pages\ViewMember;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Heading;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\SelectColumn;
use Modules\Members\Models\Member;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use BackedEnum;
use Filament\Panel;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

// Added for the header action
use Filament\Tables\Contracts\HasTable;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('profile_pic')
                    ->label('Profile Picture')
                    ->image()
                    ->directory('members/profile_pics'),

                TextInput::make('member_id')
                    ->label('Member ID')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->maxLength(50),

                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('name_bn')
                    ->label('Name (Bangla)')
                    ->maxLength(255),

                TextInput::make('email')
                    ->email()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('Phone')
                    ->tel()
                    ->maxLength(20),

                TextInput::make('father_name')
                    ->maxLength(255),

                TextInput::make('mother_name')
                    ->maxLength(255),

                DatePicker::make('dob')
                    ->label('Date of Birth'),

                TextInput::make('id_number')
                    ->label('NID / Passport / Student ID')
                    ->maxLength(100),

                Select::make('gender')
                    ->options(array_combine(Member::GENDERS, Member::GENDERS))
                    ->searchable(),

                Select::make('blood_group')
                    ->options(array_combine(Member::BLOOD_GROUPS, Member::BLOOD_GROUPS))
                    ->searchable(),

                TextInput::make('education_qualification')
                    ->label('Education Qualification'),

                TextInput::make('profession'),

                TextInput::make('other_expertise')
                    ->label('Other Expertise'),

                TextInput::make('country')->default('Bangladesh'),
                TextInput::make('division'),
                TextInput::make('district'),
                Textarea::make('address'),

                Select::make('membership_type')
                    ->options(array_combine(Member::MEMBERSHIP_TYPES, Member::MEMBERSHIP_TYPES))
                    ->required(),

                Select::make('membership_plan')
                    ->options([
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ])
                    ->required()
                    ->label('Membership Plan'),

                DatePicker::make('registration_date')
                    ->label('Registration Date')
                    ->default(now()),

                TextInput::make('balance')
                    ->numeric()
                    ->default(0.00)
                    ->prefix('৳'),

                // Keep account-level status editable in form
                Select::make('status')
                    ->label('Account Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
                    ->required(),
            ])
            ->columns(3);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_pic')->circular()->label('Photo'),
                TextColumn::make('member_id')->sortable()->searchable(),
                TextColumn::make('full_name')->sortable()->searchable(),
                TextColumn::make('phone')->label('Phone'),
                TextColumn::make('district')->sortable()->searchable(),
                TextColumn::make('membership_type')->badge()->color(fn ($state) => match ($state) {
                    'Student' => 'info',
                    'General' => 'gray',
                    'Premium' => 'success',
                    'Lifetime' => 'warning',
                    default => 'gray',
                }),

                // membership_status as inline select (allows pending/active/expired/inactive)
                SelectColumn::make('membership_status')
                    ->label('Membership Status')
                    ->options(array_combine(Member::MEMBERSHIP_STATUS, Member::MEMBERSHIP_STATUS))
                    ->sortable()
                    ->searchable()
                    // save selected enum string directly to DB
                    ->action(function (Member $record, $state) {
                        // $state contains the chosen option string (e.g., 'pending','active', etc.)
                        $record->update(['membership_status' => $state]);
                    }),

                // account-level status as a toggle (active/inactive)
                ToggleColumn::make('status')
                    ->label('Account Status')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('danger')
                    ->sortable()
                    ->getStateUsing(fn(Member $record) => $record->status === 'active')
                    ->action(function (Member $record, bool $state) {
                        $value = $state ? 'active' : 'inactive';
                        $record->update(['status' => $value]);
                    }),

                TextColumn::make('balance_status')
                    ->label('Balance / Due')
                    ->getStateUsing(function (Member $record): string {
                        $monthlyFee = 200.00;
                        $yearlyFee = 2400.00;
                        $registrationFee = 100.00;

                        $registrationDate = Carbon::parse($record->registration_date);
                        $now = Carbon::now();
                        $totalFeeRequired = $registrationFee;

                        if ($record->membership_plan === 'monthly') {
                            $monthsPassed = $registrationDate->diffInMonths($now);
                            if ($now->day >= $registrationDate->day) $monthsPassed++;
                            $totalFeeRequired += max(0, $monthsPassed) * $monthlyFee;
                        } elseif ($record->membership_plan === 'yearly') {
                            $yearsPassed = $registrationDate->diffInYears($now);
                            if ($now->month > $registrationDate->month || ($now->month === $registrationDate->month && $now->day >= $registrationDate->day)) $yearsPassed++;
                            $totalFeeRequired += max(0, $yearsPassed) * $yearlyFee;
                        }

                        $due = $totalFeeRequired - ($record->balance ?? 0.00);

                        return match (true) {
                            $due > 0 => number_format($due, 2) . ' ৳ Due',
                            $due < 0 => number_format(abs($due), 2) . ' ৳ Credit',
                            default => 'Cleared',
                        };
                    })
                    ->sortable(false)
                    ->searchable(false)
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        str_contains($state, 'Due') => 'danger',
                        str_contains($state, 'Credit') => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('balance')->money('BDT')->label('Paid Balance'),
                TextColumn::make('registration_date')->date('d M Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('membership_type')
                    ->options(array_combine(Member::MEMBERSHIP_TYPES, Member::MEMBERSHIP_TYPES)),

                // membership_status filter (shows enum values)
                Tables\Filters\SelectFilter::make('membership_status')
                    ->label('Membership Status')
                    ->options(array_combine(Member::MEMBERSHIP_STATUS, Member::MEMBERSHIP_STATUS)),

                // account-level status filter
                Tables\Filters\SelectFilter::make('status')
                    ->label('Account Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])
            // *** MOVED THE ACTION HERE ***
->headerActions([
    \Filament\Actions\Action::make('download_excel')
        ->label('Download Excel')
        ->icon('heroicon-o-arrow-down-tray')
        ->color('success')
        ->requiresConfirmation()
        ->url(fn () => route('members.export')), 
            ])
            ->actions([
                \Filament\Actions\ActionGroup::make([
                    \Filament\Actions\ViewAction::make(),
                    \Filament\Actions\EditAction::make(),
                    \Filament\Actions\DeleteAction::make(),

                    \Filament\Actions\Action::make('generate_id_card')
                        ->label('ID Card')
                        ->icon('heroicon-o-identification')
                        ->color('info') // Or 'success', 'warning', etc.
                        ->action(function (Member $record): StreamedResponse {
                            
                            // 1. Prepare the data (using the $record from the table row)
                            $data['allData'] = $record;

                            // 2. Load the view and set options
                            // (I've added 'isRemoteEnabled' => true, which you need for Google Fonts)
                            $pdf = Pdf::loadView('backend.member-card', $data)
                                ->setOptions([
                                    'defaultFont' => 'sans-serif',
                                    'isRemoteEnabled' => true 
                                ]);

                            // 3. Set a dynamic filename
                            $filename = 'id-card-' . $record->member_id . '-' . $record->full_name . '.pdf';

                            // 4. Return a StreamedResponse to download the file
                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                $filename
                            );
                        }),

                    // Send Due SMS
                    \Filament\Actions\Action::make('send_due_sms')
                        ->label('Send Due SMS')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->action(function (Member $record) {
                            $due = $record->balance_status; // use your balance_status calculation
                            $phone = $record->phone;
                            if (!$phone) {
                                \Filament\Notifications\Notification::make()
                                    ->title('No phone number found')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $msg = "Dear {$record->full_name}, your current due is {$due}. Member ID: {$record->member_id}";

                            try {
                                \DevWizard\Textify\Facades\Textify::to($phone)
                                    ->message($msg)
                                    ->via('bulksmsbd')
                                    ->send();

                                \Filament\Notifications\Notification::make()
                                    ->title('SMS sent successfully')
                                    ->success()
                                    ->send();
                            } catch (\Throwable $e) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Failed to send SMS')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),

                    // Send Due Email
                    \Filament\Actions\Action::make('send_due_email')
                        ->label('Send Due Email')
                        ->icon('heroicon-o-envelope')
                        ->color('secondary')
                        ->requiresConfirmation()
                        ->action(function (Member $record) {
                            $due = $record->balance_status;
                            $email = $record->email;
                            if (!$email) {
                                \Filament\Notifications\Notification::make()
                                    ->title('No email found')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            try {
                                \Illuminate\Support\Facades\Mail::to($email)
                                    ->send(new \App\Mail\DueReminderMail($record, $due));

                                \Filament\Notifications\Notification::make()
                                    ->title('Email sent successfully')
                                    ->success()
                                    ->send();
                            } catch (\Throwable $e) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Failed to send email')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                ])->label('Actions'),
            ])
            ->bulkActions([
                \Filament\Actions\DeleteBulkAction::make(),
                SendDueBalanceRemindersBulkAction::make('send_due_balance_reminders'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListMembers::route('/'),
            'create' => CreateMember::route('/create'),
            'view'   => ViewMember::route('/{record}'),
            'edit'   => EditMember::route('/{record}/edit'),
        ];
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return 'members';
    }
}