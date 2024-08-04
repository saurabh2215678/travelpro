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
        Schema::create('destinations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->integer('parent_id');
            $table->integer('is_city')->nullable()->default(0);
            $table->integer('destination_type')->nullable();
            $table->boolean('is_international')->nullable()->default(false);
            $table->string('slug');
            $table->mediumText('brief')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('feature_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('video_link')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longtitude')->nullable();
            $table->integer('sort_order')->nullable();
            $table->string('best_months')->nullable();
            $table->string('meta_title', 650)->nullable();
            $table->string('meta_keyword', 650)->nullable();
            $table->string('meta_description', 650)->nullable();
            $table->string('package_meta_title', 650)->nullable();
            $table->string('package_meta_keyword', 650)->nullable();
            $table->string('package_meta_description', 650)->nullable();
            $table->string('activity_meta_title', 650)->nullable();
            $table->string('activity_meta_keyword', 650)->nullable();
            $table->string('activity_meta_description', 650)->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('featured')->default(false);
            $table->boolean('show')->nullable()->default(true);
            $table->integer('is_deleted')->nullable()->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('destinations');
    }
};
