<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagGroup extends Model
{
    protected $fillable = ['name'];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public function tags()
    {
        return $this->hasMany(Tag::class,'group_id');
    }
}
