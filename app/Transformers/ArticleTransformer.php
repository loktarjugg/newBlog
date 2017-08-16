<?php

namespace App\Transformers;

use App\Models\Article;
use App\Services\EmojiParser;
use League\Fractal\TransformerAbstract;

class ArticleTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'user',
        'tags'
    ];
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Article $article)
    {
        $prev_slug = $this->getId($article ,'<') ?: null;
        $next_slug = $this->getId($article,'>')?:null;

        return [
            'id' => $article->id,
            'user_id' => $article->user_id,
            'title' => (string) $article->title,
            'slug' => (string) $article->slug,
            'cover_link' => (string) $article->cover_link,
            'desc' => (string) $article->desc,
            'body' => (string) EmojiParser::parse($article->body , 'DECODE'),
            'body_original' => (string) EmojiParser::parse($article->body_original , 'DECODE'),
            'source_link' => (string) $article->source_link,
            'vote_count' => (int) $article->vote_count,
            'view_count' => (int) $article->view_count,
            'replies_count' => (int) $article->replies_count,
            'status' => (boolean) $article->status,
            'enable_reply' => (boolean) $article->enable_reply,
            'is_top' => (boolean) $article->is_top,
            'published_at' => (int) $article->published_at,
            'next_slug' => $next_slug,
            'prev_slug' => $prev_slug,
        ];
    }

    public function includeTags(Article $article)
    {
        $tags = $article->tags;

        return !$tags? $this->null() : $this->collection($tags , new TagTransformer ,false);
    }

    public function includeUser(Article $article)
    {
        $user = $article->user;

        return !$user ? $this->null() : $this->item($user , new UserTransformer , false );
    }

    protected function getId( Article $article  , $m = '>')
    {
        $articleModel = new Article();

        $articleModel = $articleModel->withAnyTags($article->tags
            ->pluck('name')->
            collapse()->values()->toArray() , $article->type);

        return $articleModel->where('id' , $m , $article->id)
            ->orderBy('id' , $m == '<' ? 'desc' : 'asc')
            ->pluck('id')->first();

    }
}
