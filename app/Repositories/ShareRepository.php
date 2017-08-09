<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 2017/8/9
 * Time: 13:15
 */

namespace App\Repositories;


use App\Models\Share;
use App\Traits\BaseRepository;
use Illuminate\Support\Facades\Auth;

class ShareRepository
{
    use BaseRepository;

    protected $model;

    public function __construct(Share $share)
    {
        $this->model = $share;
    }

    public function search()
    {
        if (Auth::check() && Auth::user()->is_admin){
            return $this->paginate();
        }

        if (\Request::has('tags')){
            $tags = explode(',' , \Request::get('tags'));
            $this->model = $this->model->withAnyTags($tags , 'shares');
        }

        return $this->paginate();
    }
}