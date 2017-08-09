<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Tag;
use App\Observers\ArticleObserver;
use App\Observers\TagObserver;
use Illuminate\Support\ServiceProvider;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Article::observe(ArticleObserver::class);
        Tag::observe(TagObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
