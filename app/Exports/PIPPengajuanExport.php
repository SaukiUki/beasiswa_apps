<?php

namespace App\Exports;

use App\Models\Pip;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;

class PIPPengajuanExport implements
    FromQuery,
    WithHeadings,
    WithChunkReading,
    ShouldQueue
{
    use Exportable;

    protected ?string $kabupaten;

    public function __construct(?string $kabupaten = null)
    {
        $this->kabupaten = $kabupaten;
    }

    /**
     * Query khusus DATA PENGAJUAN
     */
    public function query()
    {
        return Pip::query()
            ->whereNull('status') // 🔑 kunci pengajuan
            ->when($this->kabupaten, function ($q) {
                $q->where('kabupaten', $this->kabupaten);
            })
            ->select([
                'pdid',
                'nama_siswa',
                'nama_sekolah',
                'provinsi',
                'kabupaten',
                'kecamatan',
                'nik',
                'nisn',
                'npsn',
                'kelas',
                'rombel',
                'semester',
                'jenjang',
                'bentuk',
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'nama_ayah',
                'nama_ibu',
                'nominal',
                'tahap',
                'tahap_nominasi',
                'no_kip',
                'no_kks',
                'no_kps',
                'no_pkh',
                'layak_pip',
                'nama_pengusul',
                'nama_pengusul_utama',
                'keterangan_tambahan',
                'created_at',
            ])
            ->orderBy('nama_siswa');
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * Heading khusus PENGAJUAN
     * (tanpa SK & Rekening)
     */
    public function headings(): array
    {
        return [
            'PDID',
            'Nama Siswa',
            'Nama Sekolah',
            'Provinsi',
            'Kabupaten / Kota',
            'Kecamatan',
            'NIK',
            'NISN',
            'NPSN',
            'Kelas',
            'Rombel',
            'Semester',
            'Jenjang',
            'Bentuk',
            'JK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Nama Ayah',
            'Nama Ibu',
            'Nominal',
            'Tahap',
            'Tahap Nominasi',
            'No. KIP',
            'No. KKS',
            'No. KPS',
            'No. PKH',
            'Layak PIP',
            'Nama Pengusul',
            'Nama Pengusul Utama',
            'Catatan Pengajuan',
            'Tanggal Masuk',
        ];
    }
}
