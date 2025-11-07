<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SuratMasuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'surat_masuk_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'pengirim',
        'penerima_id',
        'perihal',
        'tanggal_diterima',
        'keterangan',
        'file_path',
        'user_id',
    ];

    /**
     * Mendapatkan user (penerima) yang terkait dengan surat masuk.
     */

    public static function boot(){
        parent::boot();
        static::creating(function ($model) {
            if(empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid(); 
            }
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }
    
}
