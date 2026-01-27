<?php

namespace App\Exports;

use App\Models\KipKuliah;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class KipKuliahExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithChunkReading,
    ShouldAutoSize
{
    public function query()
    {
        // ❗ pakai kolom yang PASTI ADA
        return KipKuliah::query()->orderBy('nama_siswa');
    }

    public function headings(): array
    {
        return [
            'No. Pendaftaran',
            'Nama Siswa',
            'NIK',
            'No. Kartu Keluarga',
            'NIK Kepala Keluarga',
            'NISN',

            'Status DTKS',
            'Status P3KE',

            'No. KIP',
            'No. KKS',

            'Asal Sekolah',
            'Kab/Kota Sekolah',
            'Provinsi Sekolah',

            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',

            'Alamat Tinggal',
            'No. Handphone',
            'Alamat Email',

            'Nama Ayah',
            'Pekerjaan Ayah',
            'Penghasilan Ayah',
            'Status Ayah',

            'Nama Ibu',
            'Pekerjaan Ibu',
            'Penghasilan Ibu',
            'Status Ibu',

            'Jumlah Tanggungan',
            'Kepemilikan Rumah',
            'Tahun Perolehan',
            'Sumber Listrik',
            'Luas Tanah',
            'Luas Bangunan',
            'Sumber Air',
            'MCK',
            'Jarak Pusat Kota (KM)',

            'Diusulkan Oleh',
            'PT Tujuan',
            'Prodi Rekomendasi',
            'Status Pengajuan',
            'Tahun',
            'Rekomendasi',
        ];
    }

    public function map($row): array
    {
        return [
            $row->no_pendaftaran,
            $row->nama_siswa,
            $row->nik,
            $row->no_kartu_keluarga,
            $row->nik_kepala_keluarga,
            $row->nisn,

            $this->labelDtks($row->status_dtks),
            $this->labelP3ke($row->status_p3ke),

            $row->no_kip,
            $row->no_kks,

            $row->asal_sekolah,
            $row->kab_kota_sekolah,
            $row->provinsi_sekolah,

            $row->tempat_lahir,
            optional($row->tanggal_lahir)->format('Y-m-d'),
            $row->jenis_kelamin,

            $row->alamat_tinggal,
            $row->no_handphone,
            $row->email,

            $row->nama_ayah,
            $row->pekerjaan_ayah,
            $row->penghasilan_ayah,
            $row->status_ayah,

            $row->nama_ibu,
            $row->pekerjaan_ibu,
            $row->penghasilan_ibu,
            $row->status_ibu,

            $row->jumlah_tanggungan,
            $row->kepemilikan_rumah,
            $row->tahun_perolehan,
            $row->sumber_listrik,
            $row->luas_tanah,
            $row->luas_bangunan,
            $row->sumber_air,
            $row->mck,
            $row->jarak_pusat_kota_km,

            $row->diusulkan_oleh,
            $row->pt_tujuan,
            $row->prodi_rekomendasi,
            $row->status_pengajuan,
            $row->tahun,
            $row->rekomendasi,
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    /* ================= HELPER ================= */

    private function labelDtks($value): string
    {
        return match ($value) {
            'terdata' => 'Terdata',
            'belum_terdata' => 'Belum Terdata',
            default => '',
        };
    }

    private function labelP3ke($value): string
    {
        return match ($value) {
            'desil_1' => 'Terdata : Desil 1',
            'desil_2' => 'Terdata : Desil 2',
            'desil_3' => 'Terdata : Desil 3',
            'desil_4' => 'Terdata : Desil 4',
            'desil_5' => 'Terdata : Desil 5',
            'desil_6' => 'Terdata : Desil 6',
            'desil_7' => 'Terdata : Desil 7',
            default => 'Belum Terdata',
        };
    }
}
