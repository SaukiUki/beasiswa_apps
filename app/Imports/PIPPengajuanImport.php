<?php

namespace App\Imports;

use App\Models\PIP;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Illuminate\Contracts\Queue\ShouldQueue;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PIPPengajuanImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts,
    WithUpserts,
    ShouldQueue
{
    /**
     * Mapping 1 row Excel → 1 record PIP (PENGAJUAN)
     */
    public function model(array $row)
    {
        // 🔑 NISN wajib
        if (empty($row['nisn'])) {
            return null;
        }

        return new PIP([
            'nisn' => trim($row['nisn']),
            'pdid' => $row['pdid'] ?? null,

            'nama_siswa' => $row['nama_siswa'] ?? null,
            'nama_sekolah' => $row['nama_sekolah'] ?? null,
            'provinsi' => $row['provinsi'] ?? null,
            'kabupaten' => $row['kabupaten_kota'] ?? null,
            'kecamatan' => $row['kecamatan'] ?? null,

            'nik' => $row['nik'] ?? null,
            'npsn' => $row['npsn'] ?? null,

            'kelas' => $row['kelas'] ?? null,
            'rombel' => $row['rombel'] ?? null,
            'semester' => $row['semester'] ?? null,
            'jenjang' => $row['jenjang'] ?? null,
            'bentuk' => $row['bentuk'] ?? null,

            'jenis_kelamin' => $row['jk'] ?? null,
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => $this->parseTanggal($row['tanggal_lahir'] ?? null),

            'nama_ayah' => $row['nama_ayah'] ?? null,
            'nama_ibu' => $row['nama_ibu'] ?? null,

            'nominal' => $this->parseNominal($row['nominal'] ?? null),

            // ❌ SK & REKENING DIKOSONGKAN (BELUM TAHAPNYA)
            'tipe_sk' => null,
            'nomor_sk' => null,
            'nomor_sk_nominasi' => null,
            'tanggal_sk' => null,
            'tanggal_sk_nominasi' => null,

            'tahap' => $row['tahap'] ?? null,
            'tahap_nominasi' => null,

            'virtual_account' => null,
            'virtual_account_nominasi' => null,
            'no_rekening' => null,
            'bank' => null,

            'tanggal_aktifasi' => null,
            'tanggal_mulai_pencairan' => null,
            'tanggal_cair' => null,

            'no_kip' => $row['no_kip'] ?? null,
            'no_kks' => $row['no_kks'] ?? null,
            'no_kps' => $row['no_kps'] ?? null,
            'no_pkh' => $row['no_pkh'] ?? null,

            'layak_pip' => $row['layak_pip'] ?? null,
            'nama_pengusul' => $row['nama_pengusul'] ?? null,
            'nama_pengusul_utama' => $row['nama_pengusul_utama'] ?? null,
            'fase' => null,

            'keterangan_tahap' => null,
            'keterangan_pencairan' => null,
            'keterangan_tambahan' => $row['keterangan'] ?? null,

            // 🔑 KUNCI PENGAJUAN
            'status' => null,
        ]);
    }

    /**
     * UPSERT by NISN
     */
    public function uniqueBy()
    {
        return 'nisn';
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }

    /* ================= HELPER ================= */

    private function parseTanggal($value)
    {
        try {
            if (empty($value)) {
                return null;
            }

            if (is_numeric($value)) {
                return Carbon::instance(
                    ExcelDate::excelToDateTimeObject($value)
                );
            }

            return Carbon::parse($value);
        } catch (\Throwable $e) {
            Log::warning('Tanggal tidak valid (Pengajuan)', ['value' => $value]);
            return null;
        }
    }

    private function parseNominal($value)
    {
        if (empty($value)) {
            return null;
        }

        return (int) preg_replace('/[^0-9]/', '', $value);
    }
}
