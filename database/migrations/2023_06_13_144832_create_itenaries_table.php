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
        Schema::create('itenaries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('package_id')->index('itenaries_package_id');
            $table->integer('location_id')->nullable();
            $table->string('meal_option')->nullable();
            $table->tinyInteger('day');
            $table->string('day_title', 100);
            $table->string('title');
            $table->string('image')->nullable();
            $table->text('itenary_inclusions')->nullable();
            $table->mediumText('brief')->nullable();
            $table->mediumText('tags')->nullable();
            $table->text('description');
            $table->smallInteger('sort_order');
            $table->boolean('status');
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
        Schema::dropIfExists('itenaries');
    }
};
