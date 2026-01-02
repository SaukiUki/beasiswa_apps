<?php

namespace App\Filament\Resources\PipUsulanResource\Pages;

use App\Filament\Resources\PipUsulanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPipUsulan extends EditRecord
{
    protected static string $resource = PipUsulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
