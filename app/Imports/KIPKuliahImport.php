<?php

namespace App\Imports;

use App\Models\KIPKuliah;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Contracts\Queue\ShouldQueue;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class KIPKuliahImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts,
    ShouldQueue
{
    public function model(array $row)
    {
        // 🔑 PDID WAJIB
        if (empty($row['pdid'])) {
            Log::warning('Skip row: PDID kosong', $row);
            return null;
        }

        $pdid = trim($row['pdid']);

        // ❌ TOLAK jika PDID sudah ada
        if (KIPKuliah::where('pdid', $pdid)->exists()) {
            Log::info('Skip row: PDID sudah ada', [
                'pdid' => $pdid,
                'nama' => $row['nama_mahasiswa'] ?? null,
            ]);
            return null;
        }

        return new KIPKuliah([
            'pdid' => $pdid,
            'nama_mahasiswa' => $row['nama_mahasiswa'] ?? null,
            'nama_perguruan_tinggi' => $row['nama_perguruan_tinggi'] ?? null,

            'provinsi' => $row['provinsi'] ?? null,
            'kabupaten' => $row['kabupaten'] ?? null,
            'kecamatan' => $row['kecamatan'] ?? null,

            'nik' => $row['nik'] ?? null,
            'nisn' => $row['nisn'] ?? null,
            'npsn' => $row['npsn'] ?? null,

            'kelas' => $row['kelas'] ?? null,
            'rombel' => $row['rombel'] ?? null,
            'semester' => $row['semester'] ?? null,
            'tahun' => $row['tahun'] ?? null,
            'jenjang' => $row['jenjang'] ?? null,
            'bentuk' => $row['bentuk'] ?? null,

            'jenis_kelamin' => $row['jk'] ?? null,
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => $this->parseTanggal($row['tanggal_lahir'] ?? null),

            'nama_ayah' => $row['nama_ayah'] ?? null,
            'nama_ibu' => $row['nama_ibu'] ?? null,
            'nomor_hp' => $row['nomor_hp'] ?? null,

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
            'tanggal_mulai_pencairan' => $this->parseTanggal($row['tanggal_mulai_pencairan'] ?? null),
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

            'status' => $row['status'] ?? 'aktif',
        ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }

    private function parseTanggal($value)
    {
        try {
            if (empty($value)) return null;

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

    private function parseNominal($value)
    {
        if (empty($value)) return null;
        return (int) preg_replace('/[^0-9]/', '', $value);
    }
}
