<?php

namespace App\Filament\Resources\Members\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\Members\Models\Member;

class CheckPayments extends Page
{
    // Page title shown in Filament
    protected static ?string $title = 'Check Payments';

    // URL slug
    protected static ?string $slug = 'check-payments';

    // Blade view path (non-static)
    protected string $view = 'member.check-payments';

    // Logged-in member instance
    public ?Member $member = null;

    // Example: payments list
    public $payments = [];

    public function mount(): void
    {
        $this->member = Auth::guard('member')->user();

        if ($this->member) {
            // Example: Load payments (replace with your actual logic)
            $this->payments = $this->member->payments()->latest()->get();
        }
    }
}
