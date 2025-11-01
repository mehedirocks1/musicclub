<?php

namespace App\Filament\Resources\Members\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\Members\Models\Member;

class Dashboard extends Page
{
    // ✅ Page title shown in Filament
    protected static ?string $title = 'Dashboard';

    // ✅ URL slug
    protected static ?string $slug = 'dashboard';

    // ✅ Blade view path (non-static)
    protected string $view = 'member.dashboard';

    // ✅ Logged-in member instance
    public ?Member $member = null;

    public function mount(): void
    {
        $this->member = Auth::guard('member')->user();
    }
}
