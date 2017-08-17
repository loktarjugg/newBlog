<?php

namespace App\Models;

use App\Traits\CanVoteTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Article extends Model
{
    use SoftDeletes , CanVoteTrait,HasTags,CanVoteTrait;

    protected $casts = [
        'status' => 'boolean',
        'is_top' => 'boolean',
        'enable_reply' => 'boolean',
    ];

    protected $fillable = [
        'title',
        'user_id',
        'slug',
        'cover_link',
        'desc',
        'body_original',
        'body',
        'source_link',
        'vote_count',
        'view_count',
        'replies_count',
        'status',
        'enable_reply',
        'is_top',
        'published_at',
        'type',
    ];

    public $searchField = [
        'type' => 'type'
    ];

    public function tags()
    {
        return $this
            ->morphToMany(Tag::class, 'taggable')
            ->orderBy('order_column');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function scopeCommon($query)
    {
        return $query->where('status', 1);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'article_id');
    }
}
