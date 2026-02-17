<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatasetRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'dataset_id',
        'kabupaten_kota',
        'kode_kabkota',
        'kecamatan',
        'desa',
        'tahun',
        'bulan',
        'satuan',
        'values',
        'nilai_utama',
    ];

    protected $casts = [
        'values' => 'array',
        'nilai_utama' => 'decimal:2',
    ];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class);
    }
}