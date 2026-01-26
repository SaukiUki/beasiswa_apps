<?php

namespace App\Exports;

use App\Models\KIPKuliah;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class KIPKuliahExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithChunkReading,
    ShouldAutoSize
{
    public function query()
    {
        return KIPKuliah::query()->orderBy('kabupaten');
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
            'tahun',
            'tahun',
            'jenjang',
            'bentuk',
            'jenis_kelamin',
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

    public function map($row): array
    {
        return [
            $row->pdid,
            $row->nama_mahasiswa,
            $row->nama_perguruan_tinggi,
            $row->provinsi,
            $row->kabupaten,
            $row->kecamatan,
            $row->nik,
            $row->nisn,
            $row->npsn,
            $row->kelas,
            $row->rombel,
            $row->semester,
            $row->jenjang,
            $row->bentuk,
            $row->jenis_kelamin,
            $row->tempat_lahir,
            optional($row->tanggal_lahir)->format('Y-m-d'),
            $row->nama_ayah,
            $row->nama_ibu,
            $row->nomor_hp,
            $row->nominal,
            $row->tipe_sk,
            $row->nomor_sk,
            $row->nomor_sk_nominasi,
            optional($row->tanggal_sk)->format('Y-m-d'),
            optional($row->tanggal_sk_nominasi)->format('Y-m-d'),
            $row->tahap,
            $row->tahap_nominasi,
            $row->virtual_account,
            $row->virtual_account_nominasi,
            $row->no_rekening,
            $row->bank,
            optional($row->tanggal_aktifasi)->format('Y-m-d'),
            optional($row->tanggal_mulai_pencairan)->format('Y-m-d'),
            optional($row->tanggal_cair)->format('Y-m-d'),
            $row->no_kip,
            $row->no_kks,
            $row->no_kps,
            $row->no_pkh,
            $row->layak_pip,
            $row->nama_pengusul,
            $row->nama_pengusul_utama,
            $row->fase,
            $row->keterangan_tahap,
            $row->keterangan_pencairan,
            $row->keterangan_tambahan,
            $row->status,
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
