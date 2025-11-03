<?php

namespace App\Filament\Resources\Members\Pages;

use Filament\Pages\Page;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Members\Models\Member;
use Illuminate\Http\Response;

class GenerateIdCard extends Page
{
    protected static ?string $resource = \App\Filament\Resources\MembersResource::class;

    // Must be string, not ?string
protected string $view = 'backend.member-card';

    public Member $record;

    public function mount(Member $record)
    {
        $this->record = $record;
    }

    public function downloadIdCard()
    {
        $pdf = Pdf::loadView('backend.member-card', [
            'allData' => $this->record,
        ])->setOptions([
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
        ]);

        $filename = 'id-card-' . $this->record->member_id . '-' . $this->record->full_name . '.pdf';

        return response()->streamDownload(
            fn() => print($pdf->output()),
            $filename
        );
    }
}
