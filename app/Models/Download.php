<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'transaction_id',
        'download_url',
        'expires_at',
        'downloaded_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'downloaded_at' => 'datetime',
    ];

    /**
     * Eager load relationships by default
     */
    protected $with = ['product'];

    /**
     * Get the user who owns the download
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product being downloaded
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the transaction associated with this download
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Check if the download link has expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
