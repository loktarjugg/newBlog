<?php

namespace App\Transformers;

use App\Models\Share;
use League\Fractal\TransformerAbstract;

class ShareTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'tags'
    ];
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Share $share)
    {
        return [
            'id' => $share->id,
            'name' => (string) $share->name,
            'desc' => (string) $share->desc,
            'logo' => (string) $share->logo,
            'link' => (string) $share->link,
        ];
    }

    public function includeTags(Share $share)
    {
        $tags = $share->tags;

        return $tags ? $this->collection($tags , new TagTransformer , false ) :$this->null();
    }
}
