<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 2017/8/4
 * Time: 11:45
 */

namespace App\Observers;

use App\Models\Article;

class ArticleObserver
{
    public function saving(Article $article)
    {
        if (empty($article->published_at)) {
            $article->published_at = time();
        }

        if (empty($article->slug)) {
            $article->slug = translug($article->title);
        }
    }
}
