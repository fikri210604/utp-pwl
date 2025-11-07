<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratKeluar extends Model
{
    use HasFactory, SoftDeletes;
    public $table = 'surat_keluars';
    public $incrementing = false;
    public $keyType = 'string';
    protected $fillable = [
        'nomor_surat', 'tanggal_surat', 'user_id', 'kode_pihak_id', 'tujuan',
        'perihal_surat_id', 'nama_kegiatan', 'lokasi_acara', 'hari_tanggal',
        'waktu_acara', 'isi_tambahan', 'penandatangan_id', 'file_pdf', 'status_surat'
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function ($model) {
            if(empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid(); 
            }
        });
    }

    public function nomorSurat()
    {
        return $this->belongsTo(NomorSurat::class, 'kode_pihak_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penandatangan()
    {
        return $this->belongsTo(Penandatangan::class, 'penandatangan_id');
    }

    public function penandatangans()
    {
        return $this->belongsToMany(Penandatangan::class, 'surat_penandatangans', 'surat_keluar_id', 'penandatangan_id')
            ->withPivot('urutan_ttd')
            ->orderBy('surat_penandatangans.urutan_ttd');
    }

    public function perihalSurat()
    {
        return $this->belongsTo(PerihalSurat::class, 'perihal_surat_id', 'perihal_surat_id');
    }

    // Compat accessors so existing views keep working
    public function getPerihalAttribute()
    {
        return optional($this->perihalSurat)->nama_perihal;
    }

    public function getIsiSuratAttribute()
    {
        // Legacy field not used when templates are applied; return empty string to avoid errors
        return '';
    }

    public function getPenujuAttribute()
    {
        if (!$this->nomor_surat) return null;
        $parts = explode('/', (string) $this->nomor_surat);
        return $parts[1] ?? null;
    }

    // Gunakan relasi penandatangan() untuk akses objek penandatangan.

}
