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

        $this->middleware(['cors'])->only('index','show');

        $this->shareRepository = $shareRepository;
    }

    public function index()
    {
        $shares = $this->shareRepository->search();

        return $this->respond($shares , new ShareTransformer );
    }
}
