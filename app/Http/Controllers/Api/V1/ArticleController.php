<?php

namespace App\Http\Controllers\Api\V1;

use App\Repositories\ArticleRepository;
use App\Transformers\ArticleTransformer;
use App\Transformers\ReplyTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class ArticleController
 * @package App\Http\Controllers\Api\V1
 */
class ArticleController extends ApiController
{
    /**
     * @var ArticleRepository
     */
    protected $articleRepository;

    /**
     * ArticleController constructor.
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->middleware(['auth:api','admin'])->except('index','show','replies','vote');

        $this->middleware('cors')->only(['index','show','replies','vote']);

        $this->articleRepository = $articleRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $articles = $this->articleRepository->search();

        return $this->respond($articles , new ArticleTransformer);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $article = $this->articleRepository->find($id);

        $article->increment('view_count');

        return $this->respond($article , new ArticleTransformer );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->request->set('user_id' , Auth::id());

        $article = $this->articleRepository->store($request->all());

        if (! $article){
            return $this->errorRespond();
        }

        return $this->respond($article , new ArticleTransformer );
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request , $id)
    {
        $article = $this->articleRepository->update($request->all() , $id);

        if (! $article){
            return $this->errorRespond();
        }

        return $this->respond($article , new ArticleTransformer );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (! $this->articleRepository->destroy($id)){
            return $this->errorRespond();
        }

        return $this->noContent();
    }

    /**
     * 获取指定文章的评论
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function replies($id)
    {
        $replies = $this->articleRepository->replies($id);

        return $this->respond($replies , new ReplyTransformer );
    }

    /**
     * 点赞
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote($id)
    {
        $reply = $this->articleRepository->find($id);

        if (Auth::user()->hasVoted($reply)){
            if (! Auth::user()->cancelVote($reply)){
                return $this->errorRespond();
            }

            return $this->noContent();
        }

        if (! Auth::user()->upVote($reply)){
            return $this->errorRespond();
        }

        return $this->noContent();
    }
}
