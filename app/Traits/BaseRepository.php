<?php
namespace App\Traits;

/**
 * Class BaseRepository
 * @package App\Traits
 */
trait BaseRepository
{
    /**
     * 通过主键获取数据
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $params = \Request::all();
        //关系映射
        if (isset($params['include'])) {
            $this->convertInclude($params['include']);
        }

        return $this->model->findOrFail($id);
    }

    /**
     * 获取所有数据
     * @return mixed
     */
    public function lists()
    {
        return $this->model->get();
    }

    /**
     * 分页数据
     * 自动增加查询参数,自动增加字段映射、关系映射
     * @param int $count 每页显示数量
     * @param string $sortColumn 排序字段
     * @param string $sort 排序方式 desc asc
     * @return mixed
     */
    public function paginate($count = 10, $sortColumn = 'id', $sort ='desc')
    {
        $params = \Request::all();

        $fields = isset($this->searchField) ? $this->searchField :[];
        //字段映射
        if (count($fields) > 0) {
            foreach ($params as $param => $value) {
                if (array_has($fields, $param)) {
                    $this->model = $this->model->where(array_get($fields, $param), $value);
                }
            }
        }

        //关系映射
        if (isset($params['include'])) {
            $this->convertInclude($params['include']);
        }

        return $this->basePaginate($sortColumn, $sort, $count);
    }

    public function basePaginate($sortColumn = 'id', $sort ='desc', $count = 10)
    {
        $count = \Request::has('count') ? \Request::get('count') : $count;

        $queryParams = array_diff_key($_GET, array_flip(['page']));

        $sort = \Request::has('sort') ? \Request::get('sort') : $sort;

        $sortColumn = \Request::has('sortBy') ? \Request::get('sortBy') : $sortColumn;

        return $this->model
            ->orderBy($sortColumn, $sort)
            ->paginate($count)
            ->appends($queryParams);
    }

    public function update(array $data, $id)
    {
        $model = $this->find($id);

        return $this->save($model, $data);
    }

    public function destroy($id)
    {
        $model = $this->find($id);

        return $model->delete();
    }

    /**
     * 保存模型
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        return $this->save($this->model, $data);
    }

    /**
     * @param $model
     * @param $data
     * @return mixed
     */
    public function save($model, $data)
    {
        $model->fill($data);

        $model->save();

        return $model;
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    protected function convertInclude($includeData)
    {
        $relationships = isset($this->relationship) ? $this->relationship : [];

        $includes = explode(',', $includeData);

        $withed = collect([]);

        if (count($relationships) > 0) {
            foreach ($includes as $include) {
                if (array_has($relationships, $include)) {
                    $withed->push(array_get($relationships, $include));
                }
            }
        }

        if (! $withed->isEmpty()) {
            $this->model = $this->model->with($withed->toArray());
        }
    }
}
