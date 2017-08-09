<?php

use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tag_lists = [
            'articles' =>[
                '设计漫谈',
                '尤文私藏',
                '不负时光',
            ],
            'works' => [
                '界面',
                '动效',
                '图标',
                '壁纸',
                '其他',
            ],
            'shares' => [
                '灵感创意',
                '摄影图库',
                '设计团队',
                '资源素材',
                '经验教程',
            ],
        ];

        foreach ($tag_lists as $key => $tag_list) {
            foreach ($tag_list as $tag) {
                \Spatie\Tags\Tag::create([
                    'name' => $tag,
                    'type' => $key,
                ]);
            }
        }

        //给文章填充标签
        $articles = \App\Models\Article::all();

        $articles->map(function ($article) use($tag_lists){
            $tag = array_get($tag_lists , $article->type);
            if ($tag){
                $article->syncTagsWithType([array_random($tag)] , $article->type );
            }
        });

        $shares = \App\Models\Share::all();

        $shares->map(function ($share) use ($tag_lists){
            $tags = array_get($tag_lists, 'shares');
            if ($tags){
                $share->syncTagsWithType([array_random($tags)] ,'shares');
            }
        });

    }
}
