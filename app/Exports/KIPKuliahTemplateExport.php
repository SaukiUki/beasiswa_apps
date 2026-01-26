<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KIPKuliahTemplateExport implements
    FromArray,
    WithHeadings,
    ShouldAutoSize
{
    public function array(): array
    {
        // ❗ kosong → hanya template
        return [];
    }

    public function headings(): array
    {
        return [
            'pdid',
            'nama_mahasiswa',
            'nama_perguruan_tinggi',
            'provinsi',
            'kabupaten',
            'kecamatan',
            'nik',
            'nisn',
            'npsn',
            'kelas',
            'rombel',
            'semester',
            'tahun',
            'jenjang',
            'bentuk',
            'jk',
            'tempat_lahir',
            'tanggal_lahir',
            'nama_ayah',
            'nama_ibu',
            'nomor_hp',
            'nominal',
            'tipe_sk',
            'nomor_sk',
            'nomor_sk_nominasi',
            'tanggal_sk',
            'tanggal_sk_nominasi',
            'tahap',
            'tahap_nominasi',
            'virtual_account',
            'virtual_account_nominasi',
            'no_rekening',
            'bank',
            'tanggal_aktifasi',
            'tanggal_mulai_pencairan',
            'tanggal_cair',
            'no_kip',
            'no_kks',
            'no_kps',
            'no_pkh',
            'layak_pip',
            'nama_pengusul',
            'nama_pengusul_utama',
            'fase',
            'keterangan_tahap',
            'keterangan_pencairan',
            'keterangan_tambahan',
            'status',
        ];
    }
}
