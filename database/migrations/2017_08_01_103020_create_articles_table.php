<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('title')->comment('标题');
            $table->string('slug');
            $table->string('cover_link')->nullable()->comment('封面地址');
            $table->string('desc')->nullable()->comment('简介');
            $table->text('body_original')->comment('原始正文');
            $table->text('body')->comment('正文');
            $table->string('source_link')->nullable()->comment('来源地址');
            $table->unsignedInteger('vote_count')->default(0)->comment('点赞数');
            $table->unsignedInteger('view_count')->default(0)->comment('查看数');
            $table->unsignedInteger('replies_count')->default(0)->comment('回复数');
            $table->boolean('status')->default(true)->comment('状态 true|false');
            $table->boolean('enable_reply')->default(true)->comment('是否允许评论 true|false');
            $table->boolean('is_top')->default(false)->comment('置顶 true|false');
            $table->integer('published_at')->comment('发布时间');
            $table->enum('type',[
                'articles',
                'works'
            ])->default('articles')->comment('类型');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
