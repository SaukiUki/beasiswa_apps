<?php

namespace App\Imports;

use App\Models\KipKuliah;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class KipKuliahImport implements
    OnEachRow,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts
    
{
    public function onRow(Row $row): void
    {
        $data = $row->toArray();

        try {
            /* =====================
             * VALIDASI DASAR
             * ===================== */
            if (empty($data['nik'])) {
                return; // NIK wajib
            }

            // Skip jika NIK sudah ada
            if (KipKuliah::where('nik', trim($data['nik']))->exists()) {
                return;
            }

            KipKuliah::create([

                // IDENTITAS
                'no_pendaftaran' => $this->null($data['no_pendaftaran'] ?? null),
                'nama_siswa' => $this->null($data['nama_siswa'] ?? null),
                'nik' => trim($data['nik']),
                'no_kartu_keluarga' => $this->null($data['no_kartu_keluarga'] ?? null),
                'nik_kepala_keluarga' => $this->null($data['nik_kepala_keluarga'] ?? null),
                'nisn' => $this->nullableUnique($data['nisn'] ?? null),

                // STATUS BANTUAN
                'status_dtks' => $this->mapDtks($data['status_dtks'] ?? null),
                'status_p3ke' => $this->mapP3ke($data['status_p3ke'] ?? null),

                // BANTUAN
                'no_kip' => $this->null($data['no_kip'] ?? null),
                'no_kks' => $this->null($data['no_kks'] ?? null),

                // SEKOLAH
                'asal_sekolah' => $this->null($data['asal_sekolah'] ?? null),
                'kab_kota_sekolah' => $this->null($data['kab_kota_sekolah'] ?? null),
                'provinsi_sekolah' => $this->null($data['provinsi_sekolah'] ?? null),

                // PRIBADI
                'tempat_lahir' => $this->null($data['tempat_lahir'] ?? null),
                'tanggal_lahir' => $this->parseTanggal($data['tanggal_lahir'] ?? null),
                'jenis_kelamin' => $this->enum($data['jenis_kelamin'] ?? null, ['L', 'P']),

                // KONTAK
                'alamat_tinggal' => $this->null($data['alamat_tinggal'] ?? null),
                'no_handphone' => $this->null($data['no_handphone'] ?? null),
                'email' => $this->email($data['email'] ?? null),

                // AYAH
                'nama_ayah' => $this->null($data['nama_ayah'] ?? null),
                'pekerjaan_ayah' => $this->null($data['pekerjaan_ayah'] ?? null),
                'penghasilan_ayah' => $this->toInt($data['penghasilan_ayah'] ?? null),
                'status_ayah' => $this->null($data['status_ayah'] ?? null),

                // IBU
                'nama_ibu' => $this->null($data['nama_ibu'] ?? null),
                'pekerjaan_ibu' => $this->null($data['pekerjaan_ibu'] ?? null),
                'penghasilan_ibu' => $this->toInt($data['penghasilan_ibu'] ?? null),
                'status_ibu' => $this->null($data['status_ibu'] ?? null),

                // EKONOMI
                'jumlah_tanggungan' => $this->toInt($data['jumlah_tanggungan'] ?? null),
                'kepemilikan_rumah' => $this->null($data['kepemilikan_rumah'] ?? null),
                'tahun_perolehan' => $this->year($data['tahun_perolehan'] ?? null),
                'sumber_listrik' => $this->null($data['sumber_listrik'] ?? null),
                'luas_tanah' => $this->toInt($data['luas_tanah'] ?? null),
                'luas_bangunan' => $this->toInt($data['luas_bangunan'] ?? null),
                'sumber_air' => $this->null($data['sumber_air'] ?? null),
                'mck' => $this->null($data['mck'] ?? null),
                'jarak_pusat_kota_km' => $this->toDecimal($data['jarak_pusat_kota_km'] ?? null),

                // PENGAJUAN
                'diusulkan_oleh' => $this->null($data['diusulkan_oleh'] ?? null),
                'pt_tujuan' => $this->null($data['pt_tujuan'] ?? null),
                'prodi_rekomendasi' => $this->null($data['prodi_rekomendasi'] ?? null),
                'status_pengajuan' => $this->null($data['status_pengajuan'] ?? null),
                'tahun' => $this->year($data['tahun'] ?? null),
                'rekomendasi' => $this->null($data['rekomendasi'] ?? null),
            ]);

        } catch (\Throwable $e) {
            Log::error('Import KipKuliah gagal', [
                'row' => $row->getIndex(),
                'nik' => $data['nik'] ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /* =====================
     * QUEUE CONFIG
     * ===================== */
    public function chunkSize(): int
    {
        return 200; // lebih aman
    }

    public function batchSize(): int
    {
        return 200;
    }

    /* =====================
     * HELPER
     * ===================== */
    private function null($value)
    {
        return filled($value) ? trim((string) $value) : null;
    }

    private function nullableUnique($value)
    {
        return filled($value) ? trim((string) $value) : null;
    }

    private function toInt($value)
    {
        return is_numeric($value) ? (int) $value : null;
    }

    private function toDecimal($value)
    {
        return is_numeric($value) ? (float) $value : null;
    }

    private function year($value)
    {
        return is_numeric($value) && strlen((string) $value) === 4
            ? (int) $value
            : null;
    }

    private function enum($value, array $allowed)
    {
        return in_array($value, $allowed) ? $value : null;
    }

    private function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : null;
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
            return null;
        }
    }

    private function mapDtks($value)
    {
        return match (strtolower(trim((string) $value))) {
            'terdata' => 'terdata',
            'belum terdata', 'belum_terdata' => 'belum_terdata',
            default => null,
        };
    }

    private function mapP3ke($value)
    {
        $value = strtolower((string) $value);

        return match (true) {
            str_contains($value, 'desil 1') => 'desil_1',
            str_contains($value, 'desil 2') => 'desil_2',
            str_contains($value, 'desil 3') => 'desil_3',
            str_contains($value, 'desil 4') => 'desil_4',
            str_contains($value, 'desil 5') => 'desil_5',
            str_contains($value, 'desil 6') => 'desil_6',
            str_contains($value, 'desil 7') => 'desil_7',
            default => 'belum_terdata',
        };
    }
}
