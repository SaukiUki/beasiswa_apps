<?php

namespace App\Filament\Resources\PendidikanResource\Pages;

use App\Filament\Resources\PendidikanResource;
use App\Imports\PendidikanImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListPendidikans extends ListRecords
{
    protected static string $resource = PendidikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
            // Import Button
            Actions\Action::make('import')
                ->label('Import Data')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('File Excel')
                        ->disk('local')
                        ->directory('imports')
                        ->maxSize(51200)
                        ->required()
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
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

                    Excel::queueImport(
                        new PendidikanImport,
                        $relativePath,
                        'local'
                    );

                    Notification::make()
                        ->title('Import diproses')
                        ->body('Data Pendidikan sedang diproses di background.')
                        ->success()
                        ->send();
                }),
            
            // Download Template Button
            Actions\Action::make('downloadTemplate')
                ->label('Download Template')
                ->color('info')
                ->icon('heroicon-o-document-arrow-down')
                ->url(route('pendidikan.template'))
                ->openUrlInNewTab(),
        ];
    }
}

