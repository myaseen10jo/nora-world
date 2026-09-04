<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'title',
        'file_path',
        'external_url',
        'content_html',
        'description',
        'is_primary',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the playable embed URL for videos.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->type === 'youtube') {
            $id = $this->extractYouTubeId($this->external_url);
            return $id ? "https://www.youtube.com/embed/{$id}" : null;
        }

        if ($this->type === 'vimeo') {
            $id = $this->extractVimeoId($this->external_url);
            return $id ? "https://player.vimeo.com/video/{$id}" : null;
        }

        return null;
    }

    /**
     * Get the display URL (thumbnail or poster).
     */
    public function getDisplayUrlAttribute(): ?string
    {
        return match ($this->type) {
            'image', 'video' => $this->file_path ? asset('storage/' . $this->file_path) : null,
            'youtube' => $this->getYouTubeThumbnail(),
            'vimeo' => $this->getVimeoThumbnail(),
            'content' => null,
            default => null,
        };
    }

    private function extractYouTubeId(?string $url): ?string
    {
        if (!$url) return null;
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([\w-]+)/', $url, $m)) {
            return $m[1];
        }
        return null;
    }

    private function extractVimeoId(?string $url): ?string
    {
        if (!$url) return null;
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            return $m[1];
        }
        return null;
    }

    private function getYouTubeThumbnail(): ?string
    {
        $id = $this->extractYouTubeId($this->external_url);
        return $id ? "https://img.youtube.com/vi/{$id}/maxresdefault.jpg" : null;
    }

    private function getVimeoThumbnail(): ?string
    {
        // Vimeo thumbnails require API call — return null for now
        return null;
    }
}
