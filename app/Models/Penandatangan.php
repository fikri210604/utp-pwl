<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penandatangan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penandatangans';
    protected $primaryKey = 'penandatangan_id';

    protected $fillable = [
        'nama_penandatangan',
        'nip_npm_penandatangan',
        'jabatan_penandatangan',
        'gambar_tandatangan',
    ];

    public function suratKeluars()
    {
        // legacy single foreign key
        return $this->hasMany(SuratKeluar::class, 'penandatangan_id', 'penandatangan_id');
    }

    // Compat: allow $model->id in existing views
    public function getIdAttribute()
    {
        return $this->attributes['penandatangan_id'] ?? null;
    }

    public function suratKeluarMany()
    {
        return $this->belongsToMany(SuratKeluar::class, 'surat_penandatangans', 'penandatangan_id', 'surat_keluar_id')
            ->withPivot('urutan_ttd');
    }
}
