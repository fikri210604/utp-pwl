<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratKeluar extends Model
{
    use HasFactory, SoftDeletes;



    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'isi_surat',
        'penandatangan',
        'user_id',
        'nomor_surat_id',
        'status_surat',
        'file_pdf',
    ];

    public function nomorSurat()
    {
        return $this->belongsTo(NomorSurat::class, 'nomor_surat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
