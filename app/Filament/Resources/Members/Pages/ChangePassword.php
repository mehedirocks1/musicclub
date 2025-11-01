<?php

namespace App\Filament\Resources\Members\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Members\Models\Member;

class ChangePassword extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    // ✅ Static because BasePage::$title and $slug are static
    protected static ?string $title = 'Change Password';
    protected static ?string $slug = 'change-password';

    // ✅ Non-static Blade view
    protected string $view = 'member.change-password';

    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('current_password')
                ->label('Current Password')
                ->password()
                ->required(),

            TextInput::make('new_password')
                ->label('New Password')
                ->password()
                ->required()
                ->confirmed(),

            TextInput::make('new_password_confirmation')
                ->label('Confirm New Password')
                ->password()
                ->required(),
        ];
    }

    public function submit(): void
    {
        /** @var Member $user */
        $user = Auth::guard('member')->user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        $user->password = Hash::make($this->new_password);
        $user->save();

        $this->notify('success', 'Password changed successfully!');
        $this->reset();
    }

    protected function getFormModel(): Member
    {
        // ✅ Return the authenticated member instance for proper form binding
        return Auth::guard('member')->user();
    }
}
