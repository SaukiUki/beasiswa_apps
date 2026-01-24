<?php

namespace App\Filament\Resources\PipPengajuanResource\Pages;

use App\Filament\Resources\PipPengajuanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPipPengajuan extends EditRecord
{
    protected static string $resource = PipPengajuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
