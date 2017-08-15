<?php

namespace App\Http\Controllers\Api\V1;

use App\Repositories\ShareRepository;
use App\Transformers\ShareTransformer;
use Illuminate\Http\Request;

class ShareController extends ApiController
{
    protected $shareRepository;

    public function __construct(ShareRepository $shareRepository)
    {
        $this->middleware(['auth:api','admin'])->except('index','show');

        $this->middleware('cors')->only(['index','show']);

        $this->shareRepository = $shareRepository;
    }

    public function index()
    {
        $shares = $this->shareRepository->search();

        return $this->respond($shares , new ShareTransformer );
    }

    public function store(Request $request)
    {
        $share = $this->shareRepository->store($request->all());

        if (! $share){
            return $this->errorRespond();
        }

        return $this->respond($share , new ShareTransformer );
    }

    public function update(Request $request , $id)
    {
        $share = $this->shareRepository->update($request->all() , $id);

        if (! $share){
            return $this->errorRespond();
        }

        return $this->respond($share , new ShareTransformer );
    }

    public function destroy($id)
    {
        if (! $this->shareRepository->destroy($id)){
            return $this->errorRespond();
        }
        return $this->noContent();
    }
}
