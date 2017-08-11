<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ReplyRequest;
use App\Repositories\ReplyRepository;
use App\Services\EmojiParser;
use App\Transformers\ReplyTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReplyController extends ApiController
{
    protected $replyRepository;

    public function __construct(ReplyRepository $replyRepository)
    {
        $this->middleware(['auth:api'])->only(['store','update','destroy']);

        $this->middleware(['cors']);

        $this->replyRepository = $replyRepository;

    }

    public function index()
    {

    }

    public function store(ReplyRequest $request)
    {
        $data = $request->all();

        $data['body'] = EmojiParser::parse($data['body']);

        $data['body_original'] = $data['body'];

        $reply = Auth::user()->replies()
            ->create($data);;

        if (! $reply){
            return $this->errorRespond();
        }

        return $this->respond($reply , new ReplyTransformer );
    }

    public function destroy($id)
    {
        $deleted = $this->replyRepository->destroy($id);

        if (! $deleted){
            return $this->errorRespond();
        }

        return $this->noContent();
    }
}
