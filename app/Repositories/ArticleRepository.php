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

/**
 * Class ArticleRepository
 * @package App\Repositories
 */
class ArticleRepository
{
    use BaseRepository;

    /**
     * @var Article
     */
    protected $model;

    /**
     * @var array
     */
    protected $searchField;

    /**
     * @var array
     */
    protected $relationship = [
        'tags' => 'tags',
        'user' => 'user',
    ]; //支持预加载的关联关系 实现在BaseRepository下的covertInclude方法里

    /**
     * ArticleRepository constructor.
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->model = $article;
        $this->searchField = $article->searchField;
    }


    /**
     * @return mixed
     */
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

    /**
     * @param array $data
     * @return bool|mixed
     */
    public function store(array $data)
    {
        try{
            DB::beginTransaction();
            $data['body_original'] = EmojiParser::parse($data['body']);

            $data['body'] = Markdown::convertToHtml($data['body_original']);

            $article = $this->save($this->model , $data);

            if (isset($data['tags']) && !empty($data['tags'])){
                //Tag使用的是spatie/laravel-tags这个包
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

    /**
     * @param array $data
     * @param $id
     * @return bool|mixed
     */
    public function update(array $data , $id)
    {
        try{
            DB::beginTransaction();

            $data['body_original'] = EmojiParser::parse($data['body']);//避免emoji引起的sql保存错误

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

    /**
     * @param $id
     * @return mixed
     */
    public function replies($id)
    {
        $this->model = new Reply();

        $this->model = $this->model->where('article_id' , $id );

        return $this->paginate();
    }
}