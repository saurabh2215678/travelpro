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
        Schema::create('seo_meta_tags', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('page_title')->nullable();
            $table->text('page_brief')->nullable();
            $table->text('page_description')->nullable();
            $table->string('page_url_label')->nullable();
            $table->string('page_url')->nullable();
            $table->string('page_detail_url')->nullable();
            $table->string('identifier', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('meta_description')->nullable();
            $table->mediumText('other_meta_tag')->nullable();
            $table->string('image')->nullable();
            $table->boolean('agent_discount')->nullable()->default(false);
            $table->tinyInteger('status')->default(1);
            $table->boolean('is_disable')->nullable()->default(false);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seo_meta_tags');
    }
};
