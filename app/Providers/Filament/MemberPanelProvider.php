<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class MemberPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('member')
            ->path('member')                  // URL prefix for the member panel
            ->login(fn () => route('member.login')) // Redirect to your member login
            ->authGuard('member')             // Custom member guard
            ->colors([
                'primary' => Color::Amber,
            ])

            // Only discover pages & widgets specific to member panel
            ->discoverPages(
                in: app_path('Filament/Member/Pages'),
                for: 'App\\Filament\\Member\\Pages'
            )
            ->discoverWidgets(
                in: app_path('Filament/Member/Widgets'),
                for: 'App\\Filament\\Member\\Widgets'
            )

            // Explicitly register member-specific pages
->pages([
    \App\Filament\Resources\Members\Pages\Dashboard::class,
    \App\Filament\Resources\Members\Pages\Profile::class,
    \App\Filament\Resources\Members\Pages\ChangePassword::class,
    \App\Filament\Resources\Members\Pages\PayFee::class,
    \App\Filament\Resources\Members\Pages\CheckPayments::class,
])

            ->widgets([
                // Add custom widgets here if needed
            ])

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            ->plugins([
                FilamentShieldPlugin::make(),
            ])

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
