<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Siswa;
use Filament\Forms\Set;

class PIP extends Model
{
    use HasFactory;

    /**
     * Nama tabel (karena tidak mengikuti konvensi Laravel)
     */
    protected $table = 'p_i_p_s';

    /**
     * Kolom yang boleh diisi mass assignment
     */
    protected $fillable = [
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
     * Casting tipe data
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_sk' => 'date',
        'tanggal_sk_nominasi' => 'date',
        'tanggal_aktifasi' => 'date',
        'tanggal_mulai_pencairan' => 'date',
        'tanggal_cair' => 'date',
        'nominal' => 'integer',
    ];

    /* =========================================================
     * RELATIONSHIPS
     * ========================================================= */

    /**
     * Relasi ke Siswa
     * PIP milik 1 Siswa berdasarkan NISN
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }
}
