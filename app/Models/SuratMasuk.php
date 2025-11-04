<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratMasuk extends Model
{
    use HasFactory, SoftDeletes;

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
    
}
