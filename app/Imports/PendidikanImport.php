<?php

namespace App\Imports;

use App\Models\Pendidikan;
use App\Models\Kota;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Contracts\Queue\ShouldQueue;

class PendidikanImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts,
    ShouldQueue
{
    public function model(array $row)
    {
        // Validasi data wajib
        if (empty($row['nama_sekolah'])) {
            Log::warning('Skip row: Nama Sekolah kosong', $row);
            return null;
        }

        if (empty($row['nama_kota'])) {
            Log::warning('Skip row: Nama Kota kosong', $row);
            return null;
        }

        if (empty($row['nama_kecamatan'])) {
            Log::warning('Skip row: Nama Kecamatan kosong', $row);
            return null;
        }

           /** ==========================
         *  KOTA (auto create)
         *  ========================== */
        $kota = Kota::firstOrCreate(
            ['nama_kota' => trim($row['nama_kota'])],
            ['nama_kota' => trim($row['nama_kota'])]
        );

        if ($kota->wasRecentlyCreated) {
            Log::info('Kota baru dibuat', ['nama_kota' => $kota->nama_kota]);
        }

        /** ==========================
         *  KECAMATAN (auto create)
         *  ========================== */
        $kecamatan = Kecamatan::firstOrCreate(
            [
                'nama_kecamatan' => trim($row['nama_kecamatan']),
                'kota_id' => $kota->id,
            ],
            [
                'nama_kecamatan' => trim($row['nama_kecamatan']),
                'kota_id' => $kota->id,
            ]
        );

        if ($kecamatan->wasRecentlyCreated) {
            Log::info('Kecamatan baru dibuat', [
                'nama_kecamatan' => $kecamatan->nama_kecamatan,
                'kota_id' => $kota->id
            ]);
        }

        return new Pendidikan([
            'nama_sekolah' => trim($row['nama_sekolah']),
            'jenjang_instansi' => $this->parseJenjang($row['jenjang_instansi'] ?? null),
            'kota_id' => $kota->id,
            'kecamatan_id' => $kecamatan->id,
            'alamat' => $row['alamat'] ?? null,
            'nama_kepsek' => $row['nama_kepsek'] ?? null,
            'nama_operator' => $row['nama_operator'] ?? null,
            'no_hp_kepsek' => $row['no_hp_kepsek'] ?? null,
            'no_hp_operator' => $row['no_hp_operator'] ?? null,
            'jumlah_siswa' => $this->parseInteger($row['jumlah_siswa'] ?? null),
            'jumlah_pip_aspirasi' => $this->parseInteger($row['jumlah_pip_aspirasi'] ?? null),
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }

    /**
     * Parse jenjang ke format standar
     */
    private function parseJenjang($value)
    {
        if (empty($value)) return null;

        $jenjang = strtoupper(trim($value));
        
        // Normalisasi jenjang
        $mapping = [
            'SEKOLAH DASAR' => 'SD',
            'SD' => 'SD',
            'SEKOLAH MENENGAH PERTAMA' => 'SMP',
            'SMP' => 'SMP',
            'SEKOLAH MENENGAH AKHIR' => 'SMA',
            'SMA' => 'SMA',
            'SEKOLAH MENENGAH KEJURUAN' => 'SMK',
            'SMK' => 'SMK',
            'SEKOLAH LUAR BIASA' => 'SLB',
            'SLB' => 'SLB',
            'PERGURUAN TINGGI' => 'PERGURUAN TINGGI',
            'UNIVERSITAS' => 'PERGURUAN TINGGI',
        ];

        foreach ($mapping as $key => $val) {
            if (str_contains($jenjang, $key)) {
                return $val;
            }
        }
        $mapping = [
            'SEKOLAH DASAR' => 'SD',
            'SD' => 'SD',
            'SEKOLAH MENENGAH PERTAMA' => 'SMP',
            'SMP' => 'SMP',
            'SEKOLAH MENENGAH AKHIR' => 'SMA',
            'SMA' => 'SMA',
            'SEKOLAH MENENGAH KEJURUAN' => 'SMK',
            'SMK' => 'SMK',
            'SEKOLAH LUAR BIASA' => 'SLB',
            'SLB' => 'SLB',
            'PERGURUAN TINGGI' => 'PERGURUAN TINGGI',
            'UNIVERSITAS' => 'PERGURUAN TINGGI',
        ];

        foreach ($mapping as $key => $val) {
            if (str_contains($jenjang, $key)) {
                return $val;
            }
        }

        return $jenjang;
    }

    /**
     * Parse integer dari berbagai format
     */
    private function parseInteger($value)
    {
        if (empty($value)) return 0;
        return (int) preg_replace('/[^0-9]/', '', $value);
    }
}

