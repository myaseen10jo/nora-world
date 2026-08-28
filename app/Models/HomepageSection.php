<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HomepageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'subtitle',
        'content',
        'image',
        'link',
        'link_text',
        'settings',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'settings' => 'json',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'homepage_section_product')->withPivot('sort_order')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
