<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PerihalSurat extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'perihal_surats';
    protected $primaryKey = 'id';
    protected $fillable = ['perihal', 'keterangan', 'created_at', 'updated_at', 'deleted_at'];
}
