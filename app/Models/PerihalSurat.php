<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerihalSurat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perihal_surats';
    protected $primaryKey = 'perihal_surat_id';

    protected $fillable = [
        'nama_perihal',
        'jenis_surat',
        'template_view',
    ];

    // Compat: make $model->id and $model->template usable in existing views
    public function getIdAttribute()
    {
        return $this->attributes['perihal_surat_id'] ?? null;
    }

    public function getTemplateAttribute()
    {
        // Backward-compat for views expecting $perihal->template
        return $this->attributes['template_view'] ?? null;
    }
}
