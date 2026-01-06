<?php

namespace App\Filament\Resources\PIPResource\Pages;

use App\Filament\Resources\PIPResource;
use App\Imports\PIPImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

// Widgets
use App\Filament\Widgets\PIPStats;
use App\Filament\Widgets\PIPSiswaPerKabupatenChart;
use App\Filament\Widgets\PIPSiswaPerKecamatanChart;

class ListPIPS extends ListRecords
{
    protected static string $resource = PIPResource::class;

    /* =========================================================
     * HEADER ACTIONS
     * ========================================================= */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            // =============================
            // IMPORT EXCEL (FIX FINAL)
            // =============================
            Actions\Action::make('importExcel')
                ->label('Import Excel')
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
                    // path RELATIF (penting untuk queue)
                    $relativePath = $data['file'];

                    // validasi tambahan (aman)
                    if (! Storage::disk('local')->exists($relativePath)) {
                        Notification::make()
                            ->title('File tidak ditemukan')
                            ->danger()
                            ->send();

                        return;
                    }

                    // ✅ IMPORT VIA QUEUE (WAJIB UNTUK FILE BESAR)
                    Excel::queueImport(
                        new PIPImport,
                        $relativePath,
                        'local'
                    );

                    Notification::make()
                        ->title('Import diproses')
                        ->body('File berhasil diunggah. Import sedang berjalan di background.')
                        ->success()
                        ->send();
                }),

            // =============================
            // EXPORT EXCEL (AMAN)
            // =============================
            Actions\Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    Select::make('kabupaten')
                        ->label('Kabupaten')
                        ->options(
                            \App\Models\PIP::query()
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

                    $fileName = 'export_pip_' .
                        str($kabupaten)->slug() . '_' .
                        now()->format('Ymd_His') . '.xlsx';

                    return Excel::download(
                        new \App\Exports\PIPExport($kabupaten),
                        $fileName
                    );
                }),
        ];
    }

    /* =========================================================
     * HEADER WIDGETS
     * ========================================================= */
    protected function getHeaderWidgets(): array
    {
        return [
            PIPStats::class,
            PIPSiswaPerKecamatanChart::class,
            PIPSiswaPerKabupatenChart::class,
        ];
    }
}