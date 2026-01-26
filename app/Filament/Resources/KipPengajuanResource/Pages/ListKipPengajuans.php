<?php

namespace App\Filament\Resources\KipPengajuanResource\Pages;

use App\Filament\Resources\KipPengajuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKipPengajuans extends ListRecords
{
    protected static string $resource = KipPengajuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
