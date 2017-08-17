<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 2017/8/11
 * Time: 16:11
 */

namespace App\Repositories;

use App\Models\Reply;
use App\Traits\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

class ReplyRepository
{
    use BaseRepository;

    protected $model;

    public function __construct(Reply $reply)
    {
        $this->model = $reply;
    }

    public function destroy($id)
    {
        if (Auth::user()->is_admin) {
            $reply = $this->find($id);
        } else {
            $reply = $this->model->where('customer_id', Auth::id())
                ->findOrFail($id);
        }

        return $reply->delete();
    }
}
