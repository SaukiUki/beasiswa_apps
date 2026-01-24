<?php

namespace App\Filament\Resources\PipPengajuanResource\Pages;

use App\Filament\Resources\PipPengajuanResource;
use App\Imports\PIPPengajuanImport;
use App\Exports\PIPPengajuanExport;
use App\Models\PIP;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListPipPengajuans extends ListRecords
{
    protected static string $resource = PipPengajuanResource::class;

    /* =========================================================
     * HEADER ACTIONS
     * ========================================================= */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pengajuan'),

            /* =============================
             * IMPORT EXCEL (PENGAJUAN)
             * ============================= */
            Actions\Action::make('importExcel')
                ->label('Import Pengajuan')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('Pilih File Excel')
                        ->disk('local')
                        ->directory('imports')
                        ->maxSize(51200)
                        ->required()
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv',
                        ]),
                ])
                ->action(function (array $data) {
                    $relativePath = $data['file'];

                    if (! Storage::disk('local')->exists($relativePath)) {
                        Notification::make()
                            ->title('File tidak ditemukan')
                            ->danger()
                            ->send();
                        return;
                    }

                    // ✅ IMPORT KHUSUS PENGAJUAN
                    Excel::queueImport(
                        new PIPPengajuanImport,
                        $relativePath,
                        'local'
                    );

                    Notification::make()
                        ->title('Import pengajuan diproses')
                        ->body('Data pengajuan sedang diproses di background.')
                        ->success()
                        ->send();
                }),

            /* =============================
             * EXPORT EXCEL (PENGAJUAN)
             * ============================= */
            Actions\Action::make('exportExcel')
                ->label('Export Pengajuan')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    Select::make('kabupaten')
                        ->label('Kabupaten')
                        ->options(
                            PIP::query()
                                ->whereNull('status') // 🔑 hanya pengajuan
                                ->select('kabupaten')
                                ->distinct()
                                ->whereNotNull('kabupaten')
                                ->orderBy('kabupaten')
                                ->pluck('kabupaten', 'kabupaten')
                                ->toArray()
                        )
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $kabupaten = $data['kabupaten'];

                    $fileName = 'export_pengajuan_pip_' .
                        str($kabupaten)->slug() . '_' .
                        now()->format('Ymd_His') . '.xlsx';

                    return Excel::download(
                        new PIPPengajuanExport($kabupaten),
                        $fileName
                    );
                }),
        ];
    }
}
