<?php

namespace App\Transformers;

use App\Models\Reply;
use League\Fractal\TransformerAbstract;

class ReplyTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'user'
    ];
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Reply $reply)
    {
        return [
            'id' => $reply->id,
            'user_id' => (int) $reply->user_id,
            'article_id' => (int) $reply->article_id,
            'body_original' => (string) $reply->body_original,
            'body' => (string) $reply->body,
            'vote_count' => (int) $reply->vote_count,
            'created_at' => (int) $reply->created_at,
        ];
    }

    public function includeUser(Reply $reply)
    {
        $user = $reply->user;

        return $user ? $this->item($user, new UserTransformer, false) : $this->null();
    }
}
