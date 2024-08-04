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
        Schema::table('blog_tags', function (Blueprint $table) {
            $table->foreign(['blog_id'], 'tag_blog_id')->references(['id'])->on('blogs');
            $table->foreign(['tag_id'], 'bt_tag_id')->references(['id'])->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_tags', function (Blueprint $table) {
            $table->dropForeign('tag_blog_id');
            $table->dropForeign('bt_tag_id');
        });
    }
};
