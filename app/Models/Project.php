<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'category_id', 'short_description', 'description',
        'status', 'project_date', 'is_featured', 'project_url',
        'repository_url', 'thumbnail',
    ];

    protected function casts(): array
    {
        return [
            'project_date' => 'date',
            'is_featured'  => 'boolean',
        ];
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}