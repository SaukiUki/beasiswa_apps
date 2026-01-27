<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KipKuliahTemplateExport implements
    FromArray,
    WithHeadings,
    ShouldAutoSize
{
    public function array(): array
    {
        // Template kosong (header saja)
        return [];
    }

    public function headings(): array
    {
        return [
            'no_pendaftaran',
            'nama_siswa',
            'nik',
            'no_kartu_keluarga',
            'nik_kepala_keluarga',
            'nisn',

            'status_dtks',
            'status_p3ke',

            'no_kip',
            'no_kks',

            'asal_sekolah',
            'kab_kota_sekolah',
            'provinsi_sekolah',

            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',

            'alamat_tinggal',
            'no_handphone',
            'alamat_email',

            'nama_ayah',
            'pekerjaan_ayah',
            'penghasilan_ayah',
            'status_ayah',

            'nama_ibu',
            'pekerjaan_ibu',
            'penghasilan_ibu',
            'status_ibu',

            'jumlah_tanggungan',
            'kepemilikan_rumah',
            'tahun_perolehan',
            'sumber_listrik',
            'luas_tanah',
            'luas_bangunan',
            'sumber_air',
            'mck',
            'jarak_pusat_kota_km',

            'diusulkan_oleh',
            'pt_tujuan',
            'prodi_rekomendasi',
            'status_pengajuan',
            'tahun',
            'rekomendasi',
        ];
    }
}
