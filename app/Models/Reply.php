<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{

    protected $fillable = [
        'user_id',
        'article_id',
        'reply_id',
        'body_original',
        'body',
        'vote_count'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public function user()
    {
        return $this->hasOne(User::class , 'id' , 'user_id');
    }
}
