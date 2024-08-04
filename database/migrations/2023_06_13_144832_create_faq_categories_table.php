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
        Schema::create('faq_categories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('parent_id')->nullable()->default(0);
            $table->string('name', 100)->nullable();
            $table->mediumText('related_destinations')->nullable();
            $table->string('slug');
            $table->string('title')->nullable();
            $table->string('heading')->nullable();
            $table->string('brief', 600)->nullable();
            $table->string('template')->nullable();
            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();
            $table->mediumText('document')->nullable();
            $table->mediumText('doc_link')->nullable();
            $table->text('content')->nullable();
            $table->mediumText('old_content')->nullable();
            $table->mediumText('default_content')->nullable();
            $table->string('meta_title', 650)->nullable();
            $table->string('meta_keyword', 650)->nullable();
            $table->string('meta_description', 650)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('sort_order')->nullable();
            $table->tinyInteger('featured')->default(0);
            $table->dateTime('created_at')->useCurrent();
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
        Schema::dropIfExists('faq_categories');
    }
};
