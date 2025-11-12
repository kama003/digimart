<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'short_description',
        'product_type',
        'price',
        'license_type',
        'thumbnail_path',
        'file_path',
        'file_size',
        'is_approved',
        'is_active',
        'rejection_reason',
        'downloads_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'product_type' => \App\Enums\ProductType::class,
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
        'file_size' => 'integer',
        'downloads_count' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('title')) {
                $product->slug = Str::slug($product->title);
            }
        });
    }

    /**
     * Get the user who created the product
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of the product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the transaction items for this product
     */
    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Get the downloads for this product
     */
    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }

    /**
     * Get the cart items for this product
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
