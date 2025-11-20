<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_post_id',
        'user_id',
    ];

    /**
     * Get the blog post
     */
    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }

    /**
     * Get the user who liked
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
