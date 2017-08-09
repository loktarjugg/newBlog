<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
class Share extends Model
{
    use HasTags;

    protected $fillable = [
        'name',
        'desc',
        'logo',
        'link'
    ];

    public function tags()
    {
        return $this
            ->morphToMany(Tag::class, 'taggable')
            ->orderBy('order_column');
    }
}
