<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

#[Fillable(['name', 'email', 'password', 'social_provider', 'social_id', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin || in_array($this->email, ['admin@noraworld.com', 'admin@nora.com']);
    }

    /**
     * Check if the user registered via a social provider.
     */
    public function isSocialUser(): bool
    {
        return !is_null($this->social_provider);
    }

    /**
     * Check if the user has a password (can log in traditionally).
     */
    public function hasPassword(): bool
    {
        return !is_null($this->password);
    }

    // Relationships
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function recentlyViewedProducts(): HasMany
    {
        return $this->hasMany(RecentlyViewedProduct::class);
    }

    public function paidOrders(): HasMany
    {
        return $this->orders()->whereHas('payments', function ($query) {
            $query->where('status', 'captured');
        });
    }
}
