<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PipUsulan extends Model
{
    protected $fillable = [
        'nisn',
        'nama_siswa',
        'nama_sekolah',
        'nominal',
        'status',

        'status_usulan',
        'catatan_admin',

        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'nominal' => 'decimal:2',
    ];

    /* =========================================================
     * RELATIONS
     * ========================================================= */
    public function pip(): BelongsTo
    {
        return $this->belongsTo(Pip::class, 'nisn', 'nisn');
    }

    /* =========================================================
     * QUERY SCOPES (OPTIONAL TAPI SANGAT MEMBANTU)
     * ========================================================= */
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
