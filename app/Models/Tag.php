<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'name',
        'slug',
        'order_column',
    ];

    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
    ];

    public $searchField =[
        'type' => 'type',
    ];

}
