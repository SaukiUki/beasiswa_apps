<?php

namespace App\Filament\Resources\PipPengajuanResource\Pages;

use App\Filament\Resources\PipPengajuanResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePipPengajuan extends CreateRecord
{
    protected static string $resource = PipPengajuanResource::class;

    /**
     * Pastikan data manual SELALU masuk sebagai PENGAJUAN
     * (status NULL, belum SK)
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 🔐 KUNCI STATUS
        $data['status'] = null;

        // 🔐 KOSONGKAN ADMINISTRASI SK
        $data['tipe_sk'] = null;
        $data['nomor_sk'] = null;
        $data['nomor_sk_nominasi'] = null;
        $data['tanggal_sk'] = null;
        $data['tanggal_sk_nominasi'] = null;

        return $data;
    }
}

