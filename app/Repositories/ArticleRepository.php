<?php
namespace App\Repositories;


use App\Models\Article;
use App\Models\Reply;
use App\Models\Tag;
use App\Services\EmojiParser;
use App\Traits\BaseRepository;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class ArticleRepository
{
    use BaseRepository;

    protected $model;

    protected $searchField;

    protected $relationship = [
        'tags' => 'tags',
        'user' => 'user',
    ]; //支持预加载的关联关系 实现在BaseRepository下的covertInclude方法里

    public function __construct(Article $article)
    {
        $this->model = $article;
        $this->searchField = $article->searchField;
    }

    public function search()
    {
        if (Auth::check() && Auth::user()->is_admin){
            return $this->paginate();
        }

        $this->model = $this->model->common();

        $type = \Request::get('type') ?: 'articles';

        if (\Request::has('tags')){
            $tags = explode(',' , \Request::get('tags'));
            $this->model = $this->model->withAnyTags($tags , $type);
        }

        return $this->paginate(10,'is_top');
    }

    public function store(array $data)
    {
        try{
            DB::beginTransaction();
            $data['body_original'] = EmojiParser::parse($data['body']);

            $data['body'] = Markdown::convertToHtml($data['body_original']);

            $article = $this->save($this->model , $data);

            if (isset($data['tags']) && !empty($data['tags'])){
                $article->syncTagsWithType(array_flatten($data['tags']) , $data['type']);
            }

            DB::commit();
            return $article;

        }catch (Exception $exception){
            DB::rollBack();
            errorLog($exception , '新增文章');
            return false;
        }
    }

    public function update(array $data , $id)
    {
        try{
            DB::beginTransaction();

            $data['body_original'] = EmojiParser::parse($data['body']);

            $data['body'] = Markdown::convertToHtml($data['body_original']);

            $article = $this->save($this->find($id) , $data);

            if (isset($data['tags']) && !empty($data['tags'])){
                $article->syncTagsWithType(array_flatten($data['tags']) , $data['type']);
            }

            DB::commit();

            return $article;

        }catch (Exception $exception){
            DB::rollBack();
            errorLog($exception , '修改文章');
            return false;
        }
    }

    public function replies($id)
    {
        $this->model = new Reply();

        $this->model = $this->model->where('article_id' , $id );

        return $this->paginate();
    }
}