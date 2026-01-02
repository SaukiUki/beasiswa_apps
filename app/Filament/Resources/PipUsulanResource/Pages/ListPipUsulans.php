<?php

namespace App\Filament\Resources\PipUsulanResource\Pages;

use App\Filament\Resources\PipUsulanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPipUsulans extends ListRecords
{
    protected static string $resource = PipUsulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
