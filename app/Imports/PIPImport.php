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
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Contracts\Queue\ShouldQueue;

class PIPImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts,
    WithUpserts,
    ShouldQueue

{
    /**
     * Mapping 1 row Excel → 1 record PIP
     */
    public function model(array $row)
    {
        // ❗ WAJIB: NISN adalah UNIT
        if (empty($row['nisn'])) {
            return null; // skip row rusak
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
            'semester' => $row['semester'] ?? null, // typo excel
            'jenjang' => $row['jenjang'] ?? null,
            'bentuk' => $row['bentuk'] ?? null,

            'jenis_kelamin' => $row['jk'] ?? null,
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => $this->parseTanggal($row['tanggal_lahir'] ?? null),

            'nama_ayah' => $row['nama_ayah'] ?? null,
            'nama_ibu' => $row['nama_ibu'] ?? null,

            'nominal' => $this->parseNominal($row['nominal'] ?? null),

            'tipe_sk' => $row['tipe_sk'] ?? null,
            'nomor_sk' => $row['nomor_sk'] ?? null,
            'nomor_sk_nominasi' => $row['nomor_sk_nominasi'] ?? null,

            'tanggal_sk' => $this->parseTanggal($row['tanggal_sk'] ?? null),
            'tanggal_sk_nominasi' => $this->parseTanggal($row['tanggal_sk_nominasi'] ?? null),

            'tahap' => $row['tahap'] ?? null,
            'tahap_nominasi' => $row['tahap_nominasi'] ?? null,

            'virtual_account' => $row['virtual_account'] ?? null,
            'virtual_account_nominasi' => $row['virtual_account_nominasi'] ?? null,

            'no_rekening' => $row['no_rekening'] ?? null,
            'bank' => $row['bank'] ?? null,

            'tanggal_aktifasi' => $this->parseTanggal($row['tanggal_aktifasi'] ?? null),
            'tanggal_mulai_pencairan' => $this->parseTanggal($row['tanggal_mulai_pecairan'] ?? null),
            'tanggal_cair' => $this->parseTanggal($row['tanggal_cair'] ?? null),

            'no_kip' => $row['no_kip'] ?? null,
            'no_kks' => $row['no_kks'] ?? null,
            'no_kps' => $row['no_kps'] ?? null,
            'no_pkh' => $row['no_pkh'] ?? null,

            'layak_pip' => $row['layak_pip'] ?? null,
            'nama_pengusul' => $row['nama_pengusul'] ?? null,
            'nama_pengusul_utama' => $row['nama_pengusul_utama'] ?? null,
            'fase' => $row['fase'] ?? null,

            'keterangan_tahap' => $row['keterangan_tahap'] ?? null,
            'keterangan_pencairan' => $row['keterangan_pencairan'] ?? null,
            'keterangan_tambahan' => $row['keterangan_tambahan'] ?? null,

            // DEFAULT SYSTEM
            'status_pengajuan' => 'draft',
            'status' => $row['status'] ?? 'aktif',
        ]);
    }

    /**
     * 1 NISN = 1 PIP (UPSERT)
     */
    public function uniqueBy()
    {
        return 'nisn';
    }

    /**
     * Chunk reading (anti memory leak)
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Batch insert (lebih cepat)
     */
    public function batchSize(): int
    {
        return 500;
    }

    /**
     * Parsing tanggal Excel (numeric / string)
     */
    private function parseTanggal($value)
    {
        try {
            if (empty($value)) {
                return null;
            }

            // Excel numeric date
            if (is_numeric($value)) {
                return Carbon::instance(
                    ExcelDate::excelToDateTimeObject($value)
                );
            }

            return Carbon::parse($value);
        } catch (\Throwable $e) {
            Log::warning('Tanggal tidak valid', ['value' => $value]);
            return null;
        }
    }

    /**
     * Bersihkan nominal: "1.000.000" → 1000000
     */
    private function parseNominal($value)
    {
        if (empty($value)) {
            return null;
        }

        return (int) preg_replace('/[^0-9]/', '', $value);
    }
}