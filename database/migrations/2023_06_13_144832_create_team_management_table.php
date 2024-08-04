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
        Schema::create('team_management', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('gender')->nullable();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('slug');
            $table->string('designation')->nullable();
            $table->string('category')->nullable();
            $table->string('trip_theme');
            $table->mediumText('brief_profile')->nullable();
            $table->text('detail_profile');
            $table->string('email', 100)->nullable();
            $table->string('alt_email', 100)->nullable();
            $table->string('mobile_no', 20)->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('image')->nullable();
            $table->integer('sort_order');
            $table->boolean('status');
            $table->integer('is_deleted')->default(0);
            $table->integer('featured');
            $table->string('meta_title')->nullable();
            $table->mediumText('meta_description')->nullable();
            $table->mediumText('meta_keywords')->nullable();
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
        Schema::dropIfExists('team_management');
    }
};
