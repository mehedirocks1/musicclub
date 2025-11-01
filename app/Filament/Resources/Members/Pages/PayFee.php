<?php

namespace App\Filament\Resources\Members\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Modules\Members\Models\Member;

class PayFee extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    // ✅ Static because BasePage::$title and $slug are static
    protected static ?string $title = 'Pay Monthly Fee';
    protected static ?string $slug = 'pay-monthly-fee';

    // ✅ Non-static Blade view path
    protected string $view = 'member.pay-fee';

    public $amount;

    public function mount(): void
    {
        $this->amount = Auth::guard('member')->user()->balance ?? 0;
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('amount')
                ->label('Amount')
                ->required()
                ->numeric(),
        ];
    }

    public function submit(): void
    {
        $user = Auth::guard('member')->user();

        if ($this->amount > 0 && $user->balance >= $this->amount) {
            $user->balance -= $this->amount;
            $user->save();

            $this->notify('success', 'Payment successful!');
        } else {
            $this->notify('danger', 'Invalid payment amount!');
        }
    }

    protected function getFormModel(): Member
    {
        return Auth::guard('member')->user();
    }
}
