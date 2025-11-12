<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_gateway',
        'payment_id',
        'amount',
        'commission',
        'seller_amount',
        'status',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission' => 'decimal:2',
        'seller_amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the user who made the transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transaction items
     */
    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Get the downloads associated with this transaction
     */
    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }
}
