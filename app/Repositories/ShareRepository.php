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
use Exception;
use Illuminate\Support\Facades\DB;

class ShareRepository
{
    use BaseRepository;

    protected $model;

    protected $relationship =[
        'tags' => 'tags'
    ];
    public function __construct(Share $share)
    {
        $this->model = $share;
    }

    public function search()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return $this->paginate();
        }

        if (\Request::has('tags')) {
            $tags = explode(',', \Request::get('tags'));
            $this->model = $this->model->withAnyTags($tags, 'shares');
        }

        return $this->paginate();
    }

    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            $share = $this->save($this->model, $data);

            if (isset($data['tags']) && !empty($data['tags'])) {
                $share->syncTagsWithType(array_flatten($data['tags']), 'shares');
            }

            DB::commit();
            return $share;
        } catch (Exception $exception) {
            DB::rollBack();
            errorLog($exception, '新增分享报错');
            return false;
        }
    }

    public function update(array $data, $id)
    {
        try {
            DB::beginTransaction();
            $share = $this->save($this->find($id), $data);

            if (isset($data['tags']) && !empty($data['tags'])) {
                $share->syncTagsWithType(array_flatten($data['tags']), 'shares');
            }

            DB::commit();
            return $share;
        } catch (Exception $exception) {
            DB::rollBack();
            errorLog($exception, '更新分享报错');

            return false;
        }
    }
}
