<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NomorSurat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nomor_surat';
    protected $fillable = ['kode_pihak', 'nama_pihak', 'is_aktif'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class);
    }
    
}
