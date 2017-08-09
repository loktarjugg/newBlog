<?php

namespace App\Http\Controllers\Api\V1;

use App\Repositories\ArticleRepository;
use App\Transformers\ArticleTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends ApiController
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->middleware(['auth:api','admin'])->except('index','show');

        $this->middleware(['cors'])->only('index','show');

        $this->articleRepository = $articleRepository;
    }

    public function index()
    {
        $articles = $this->articleRepository->search();

        return $this->respond($articles , new ArticleTransformer);
    }

    public function show($id)
    {
        $article = $this->articleRepository->find($id);

        $article->increment('view_count');

        return $this->respond($article , new ArticleTransformer );
    }

    public function store(Request $request)
    {
        $request->request->set('user_id' , Auth::id());

        $article = $this->articleRepository->store($request->all());

        if (! $article){
            return $this->errorRespond();
        }

        return $this->respond($article , new ArticleTransformer );
    }

    public function update(Request $request , $id)
    {
        $article = $this->articleRepository->update($request->all() , $id);

        if (! $article){
            return $this->errorRespond();
        }

        return $this->respond($article , new ArticleTransformer );
    }

    public function destroy($id)
    {
        if (! $this->articleRepository->destroy($id)){
            return $this->errorRespond();
        }

        return $this->noContent();
    }
}
