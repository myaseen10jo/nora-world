<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'paypal_order_id',
        'paypal_capture_id',
        'status',
        'amount',
        'currency',
        'payer_email',
        'payer_id',
        'payment_method',
        'metadata',
        'failure_reason',
        'captured_at',
        'refunded_at',
        'idempotency_key',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'json',
        'captured_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format((float) $this->amount, 2);
    }

    public function isCaptured(): bool
    {
        return $this->status === 'captured';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return in_array($this->status, ['failed', 'cancelled']);
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }
}
