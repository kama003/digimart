<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'method',
        'account_details',
        'status',
        'admin_notes',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'account_details' => 'encrypted',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the user who requested the withdrawal
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
