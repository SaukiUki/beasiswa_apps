<?php

namespace App\Filament\Resources\KipPengajuanResource\Pages;

use App\Filament\Resources\KipPengajuanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKipPengajuan extends EditRecord
{
    protected static string $resource = KipPengajuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
