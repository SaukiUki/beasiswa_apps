<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KipKuliah extends Model
{
    use HasFactory;

    protected $table = 'kip_kuliahs';

    /**
     * Kolom yang boleh diisi (sesuai Excel)
     */
    protected $fillable = [
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
        'email',
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

    /**
     * Casting tipe data (PENTING)
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_perolehan' => 'integer',
        'tahun' => 'integer',
        'penghasilan_ayah' => 'integer',
        'penghasilan_ibu' => 'integer',
        'jumlah_tanggungan' => 'integer',
        'luas_tanah' => 'integer',
        'luas_bangunan' => 'integer',
        'jarak_pusat_kota_km' => 'decimal:2',
    ];
}
