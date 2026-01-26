<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pendidikan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_sekolah',
        'jenjang_instansi',
        'kota_id',
        'kecamatan_id',
        'alamat',
        'nama_kepsek',
        'nama_operator',
        'no_hp_kepsek',
        'no_hp_operator',
        'jumlah_siswa',
        'jumlah_pip_aspirasi',
    ];

    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }

    // Relasi ke model Kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function guru()
    {
        return $this->hasMany(Guru::class);
    }
}
