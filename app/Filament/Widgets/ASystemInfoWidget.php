<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ASystemInfoWidget extends Widget
{
    protected string $view = 'filament.widgets.a-system-info-widget';

    // Make it full width
    protected int | string | array $columnSpan = 'full';

    public $userName;
    public $currentDate;
    public $currentTime;
    public $phpVersion;
    public $laravelVersion;
    public $serverOs;

    public function mount(): void
    {
        $this->userName = Auth::user()?->name ?? 'Admin';
        $this->currentDate = Carbon::now()->format('F d, Y');
        $this->currentTime = Carbon::now()->timezone('Asia/Dhaka')->format('h:i:s A');
        $this->phpVersion = phpversion();
        $this->laravelVersion = app()->version();
        $this->serverOs = php_uname('s') . ' ' . php_uname('r');
    }

    public function getViewData(): array
    {
        return [
            'userName' => $this->userName,
            'currentDate' => $this->currentDate,
            'currentTime' => $this->currentTime,
            'phpVersion' => $this->phpVersion,
            'laravelVersion' => $this->laravelVersion,
            'serverOs' => $this->serverOs,
        ];
    }
}
