<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PipUsulan extends Model
{
    protected $table = 'pip_usulans';

    /* =========================================================
     * MASS ASSIGNMENT
     * ========================================================= */
    protected $fillable = [
        // ===== DATA UTAMA (SAMA DENGAN PIP) =====
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

        // ===== WORKFLOW USULAN =====
        'status_usulan',
        'catatan_admin',
        'approved_at',
        'approved_by',
    ];

    /* =========================================================
     * CASTS
     * ========================================================= */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_sk' => 'date',
        'tanggal_sk_nominasi' => 'date',
        'tanggal_aktifasi' => 'date',
        'tanggal_mulai_pencairan' => 'date',
        'tanggal_cair' => 'date',

        'approved_at' => 'datetime',

        'nominal' => 'decimal:2',
        'layak_pip' => 'boolean',
    ];

    /* =========================================================
     * RELATIONS
     * ========================================================= */
    public function pip(): BelongsTo
    {
        return $this->belongsTo(Pip::class, 'nisn', 'nisn');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /* =========================================================
     * QUERY SCOPES
     * ========================================================= */
    public function scopeDraft($query)
    {
        return $query->where('status_usulan', 'draft');
    }

    public function scopeDiajukan($query)
    {
        return $query->where('status_usulan', 'diajukan');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status_usulan', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status_usulan', 'ditolak');
    }
}
