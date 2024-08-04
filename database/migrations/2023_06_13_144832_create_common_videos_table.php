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
        Schema::create('common_videos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title');
            $table->integer('tbl_id')->nullable();
            $table->string('tbl_name')->nullable();
            $table->mediumText('link');
            $table->integer('sort_order')->nullable();
            $table->tinyInteger('featured')->default(0);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('common_videos');
    }
};
