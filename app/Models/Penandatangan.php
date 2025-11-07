<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penandatangan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penandatangans';

    protected $fillable = [
        'penandatangan',
        'gambar_tandatangan',
    ];

    public function suratKeluars()
    {
        return $this->hasMany(SuratKeluar::class);
    }
}
