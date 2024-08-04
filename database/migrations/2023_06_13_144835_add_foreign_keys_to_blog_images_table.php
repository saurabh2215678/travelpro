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
        Schema::table('blog_images', function (Blueprint $table) {
            $table->foreign(['blog_id'], 'blog_id')->references(['id'])->on('blogs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_images', function (Blueprint $table) {
            $table->dropForeign('blog_id');
        });
    }
};
