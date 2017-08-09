<?php
namespace App\Repositories;


use App\Models\Tag;
use App\Traits\BaseRepository;
use Illuminate\Support\Facades\DB;
use Exception;
use Spatie\Tags\Tag as SpatieTag;

class TagRepository
{
    use BaseRepository;

    protected $model;

    protected $searchField;

    public function __construct(Tag $tag)
    {
        $this->model = $tag;

        $this->searchField = $tag->searchField;
    }

    public function search()
    {
        return $this->paginate();
    }

    public function store(array $data)
    {
        try{

            SpatieTag::create([
                'name' => $data['name'],
                'type' => $data['type']
            ]);

            return true;
        }catch (Exception $exception){
            return false;
        }
    }

    public function update(array $data , $id)
    {
        $tag = SpatieTag::findOrFail($id);

        try{
            $tag->fill($data)->save();
            return true;
        }catch (Exception $exception){
            return false;
        }

    }

}