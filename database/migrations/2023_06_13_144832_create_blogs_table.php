<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('type', 50)->nullable();
            $table->integer('category_id')->nullable()->index('blog_category_id');
            $table->string('title')->nullable();
            $table->string('post_by')->nullable();
            $table->string('slug')->nullable();
            $table->mediumText('brief')->nullable();
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->string('pdf')->nullable();
            $table->dateTime('blog_date')->nullable();
            $table->boolean('sort_order')->nullable();
            $table->string('source_title')->nullable();
            $table->text('source_url')->nullable();
            $table->text('add_media')->nullable();
            $table->text('post_title_url')->nullable();
            $table->boolean('show_comments')->nullable();
            $table->boolean('allow_comments')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->tinyInteger('featured')->nullable();
            $table->boolean('popular')->nullable();
            $table->tinyInteger('is_home')->nullable();
            $table->integer('posted_by')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
};
