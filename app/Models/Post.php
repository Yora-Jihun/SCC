<?php
// app/Models/Post.php

namespace App\Models;

use App\Enums\PostVisibility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = ['user_id', 'shared_post_id', 'body', 'image_path', 'visibility'];

    protected $casts = [
        'visibility' => PostVisibility::class,
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function originalPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'shared_post_id');
    }

    public function shares(): HasMany
    {
        return $this->hasMany(Post::class, 'shared_post_id');
    }

    public function isShare(): bool
    {
        return $this->shared_post_id !== null;
    }
}