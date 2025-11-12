<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerRoleRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'admin_notes',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    /**
     * Get the user who made the request
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
