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
        return $this->belongsTo(NomorSurat::class, 'nomor_surat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penandatangan()
    {
        return $this->belongsTo(Penandatangan::class, 'penandatangan_id');
    }

    public function perihalSurat()
    {
        return $this->belongsTo(PerihalSurat::class, 'perihal_surat_id');
    }


}
