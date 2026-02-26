<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Dataset extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'organization_id',
        'dataset_upload_id', // ← tambahan
        'columns',
        'excel_path',        // ← tambahan
        'can_visualize',     // ← tambahan
        'visualize_types',
        'unit',
        'frequency',
        'start_year',
        'end_year',
        'status',
        'is_public',
        'published_at',
    ];

    protected $casts = [
        'columns'       => 'array',
        'is_public'     => 'boolean',
        'can_visualize' => 'boolean',
        'published_at'  => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($dataset) {
            if (empty($dataset->slug)) {
                $dataset->slug = Str::slug($dataset->title);
            }
        });
    }

    // ── Relationships ─────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function records()
    {
        return $this->hasMany(DatasetRecord::class);
    }

    public function upload()
    {
        return $this->belongsTo(DatasetUpload::class, 'dataset_upload_id');
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('is_public', true);
    }
}