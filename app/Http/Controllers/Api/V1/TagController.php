<?php

namespace App\Http\Controllers\Api\V1;

use App\Repositories\TagRepository;
use App\Transformers\TagTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TagController extends ApiController
{
    protected $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;

        $this->middleware(['auth:api','admin'])->except('index','show');
    }

    public function index()
    {
        $tags = $this->tagRepository->search();

        return $this->respond($tags , new TagTransformer );
    }

    public function store(Request $request)
    {

        $tag = $this->tagRepository->store($request->all());

        if (! $tag){
            return $this->errorRespond();
        }

        return $this->noContent();
    }

    public function update(Request $request , $id)
    {
        $tag = $this->tagRepository->update($request->all() , $id );

        if (! $tag){
            return $this->errorRespond();
        }

        return $this->noContent();
    }

    public function destroy($id)
    {
        $tag = $this->tagRepository->find($id);

        if (DB::table('taggables')->where('tag_id' , $id)->first()){
            return $this->errorRespond('此标签存在关联，无法删除');
        }

        if (! $tag->delete() ){
            return $this->errorRespond();
        }

        return $this->noContent();
    }
}
