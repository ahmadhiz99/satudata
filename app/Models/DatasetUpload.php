<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DatasetUpload extends Model
{
    protected $fillable = [
        'title',
        'organization_id',
        'category_id',
        'file_path',
        'file_name',
        'file_size',
        'extract_to_db',
        'start_row',
        'start_col',
        'view_count',
    ];

    protected $casts = [
        'extract_to_db' => 'boolean',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function dataset(): HasOne
    {
        return $this->hasOne(Dataset::class, 'dataset_upload_id');
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if (!$bytes) return '-';
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}