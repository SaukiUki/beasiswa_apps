<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KipKuliah extends Model
{
    use HasFactory;

    protected $table = 'kip_kuliahs';

    /**
     * Primary identifier (logical)
     * pdid = unique key utama
     */
    protected $fillable = [
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

    /**
     * Cast data types
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_sk' => 'date',
        'tanggal_sk_nominasi' => 'date',
        'tanggal_aktifasi' => 'date',
        'tanggal_mulai_pencairan' => 'date',
        'tanggal_cair' => 'date',

        'nominal' => 'integer',
        'semester' => 'integer',
        'layak_pip' => 'boolean',
    ];
}
