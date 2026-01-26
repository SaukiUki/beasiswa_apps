<?php

namespace App\Filament\Resources\KipKuliahResource\Pages;

use App\Filament\Resources\KipKuliahResource;
use App\Exports\KIPKuliahTemplateExport;
use App\Imports\KIPKuliahImport;
use App\Exports\KIPKuliahExport;
use App\Models\KIPKuliah;

use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

// Widgets
use App\Filament\Widgets\KipKuliahStats;

class ListKipKuliahs extends ListRecords
{
    protected static string $resource = KipKuliahResource::class;

    /* =========================================================
     * HEADER WIDGETS
     * ========================================================= */
    protected function getHeaderWidgets(): array
    {
        return [
            KipKuliahStats::class,
            // nanti bisa tambah chart seperti PIP
            // KipKuliahPerKabupatenChart::class,
            // KipKuliahPerKecamatanChart::class,
        ];
    }

    /* =========================================================
     * HEADER ACTIONS
     * ========================================================= */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            /* =============================
             * IMPORT EXCEL (QUEUE)
             * ============================= */
            Actions\Action::make('importExcel')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('Pilih File Excel')
                        ->disk('local')
                        ->directory('imports')
                        ->maxSize(51200) // 50MB
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

                    // ✅ IMPORT VIA QUEUE
                    Excel::queueImport(
                        new KIPKuliahImport,
                        $relativePath,
                        'local'
                    );

                    Notification::make()
                        ->title('Import diproses')
                        ->body('File berhasil diunggah. Import berjalan di background.')
                        ->success()
                        ->send();
                }),

            /* =============================
             * EXPORT EXCEL
             * ============================= */
            Actions\Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    Select::make('kabupaten')
                        ->label('Kabupaten')
                        ->options(
                            KIPKuliah::query()
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

                    $fileName = 'export_kip_kuliah_' .
                        str($kabupaten)->slug() . '_' .
                        now()->format('Ymd_His') . '.xlsx';

                    return Excel::download(
                        new KIPKuliahExport($kabupaten),
                        $fileName
                    );
                }),

                Actions\Action::make('downloadTemplate')
    ->label('Download Template')
    ->icon('heroicon-o-document-arrow-down')
    ->color('info')
    ->action(function () {
        return Excel::download(
            new KIPKuliahTemplateExport,
            'template_import_kip_kuliah.xlsx'
        );
    }),

        ];
    }
}
