<?php

namespace App\Modules\Website\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class News extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'content', 'image_path', 'author', 'is_published', 'published_at'];

    protected $casts = ['is_published' => 'boolean', 'published_at' => 'datetime'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->title) ?: Str::random(10);
            if (! $model->author) {
                $model->author = 'Admin';
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }
}
