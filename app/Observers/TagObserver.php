<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 2017/8/4
 * Time: 15:54
 */

namespace App\Observers;


use App\Models\Tag;

class TagObserver
{
    public function saving(Tag $tag)
    {
//        if (empty($tag->slug)){
//            $tag->slug = translug($tag->name);
//        }
    }
}