<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'compare_at_price',
        'sku',
        'stock_quantity',
        'in_stock',
        'is_featured',
        'is_active',
        'weight',
        'dimensions',
        'origin_type',
        'origin_country',
        'artisan_name',
        'product_story',
        'materials_used',
        'handmade_technique',
        'care_instructions',
        'estimated_preparation_time',
        'estimated_shipping_time',
        'is_one_of_a_kind',
        'is_made_to_order',
        'cultural_note',
        'gift_wrapping_available',
        'return_policy',
        'clothing_size',
        'color_palette',
        'color_primary',
        'color_secondary',
        'condition',
        'age_estimate',
        'style_notes',
        'height_cm',
        'width_cm',
        'depth_cm',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'weight' => 'decimal:2',
        'in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_one_of_a_kind' => 'boolean',
        'is_made_to_order' => 'boolean',
        'gift_wrapping_available' => 'boolean',
        'height_cm' => 'integer',
        'width_cm' => 'integer',
        'depth_cm' => 'integer',
    ];

    public static function boot(): void
    {
        parent::boot();
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_category')->withTimestamps();
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProductMedia::class)->orderBy('sort_order');
    }

    public function primaryMedia()
    {
        return $this->hasOne(ProductMedia::class)->where('is_primary', true);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag')->withTimestamps();
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_product')->withPivot('sort_order')->withTimestamps();
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format((float) $this->price, 2);
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->compare_at_price && $this->compare_at_price > $this->price;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->is_on_sale) {
            return null;
        }
        return (int) round((($this->compare_at_price - $this->price) / $this->compare_at_price) * 100);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('in_stock', true)->where('stock_quantity', '>', 0);
    }

    public function scopeNewArrivals($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
