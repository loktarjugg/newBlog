<?php

namespace App\Transformers;

use App\Models\Tag;
use League\Fractal\TransformerAbstract;

class TagTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Tag $tag)
    {
        return [
            'id' => $tag->id,
            'name' => $tag->name['zh-CN'],
            'slug' =>  $tag->slug['zh-CN'],
            'type' => $tag->type,
            'order_column' => $tag->order_column,
        ];
    }

}
