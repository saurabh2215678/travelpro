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
        Schema::create('enquiries_master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_id');
            $table->unsignedBigInteger('group_id')->nullable()->index('group_id');
            $table->string('name', 50);
            $table->string('type', 50)->nullable();
            $table->string('category', 20)->nullable();
            $table->string('description', 50)->nullable();
            $table->string('slug', 50);
            $table->string('color_code', 10)->nullable();
            $table->string('color_name', 10)->nullable();
            $table->integer('sort_order');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquiries_master');
    }
};
