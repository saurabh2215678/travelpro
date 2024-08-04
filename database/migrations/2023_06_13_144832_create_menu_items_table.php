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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('menu_id')->nullable()->index('menu_id');
            $table->integer('parent_id')->nullable()->default(0);
            $table->string('page_id', 100)->nullable();
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->string('slug')->nullable();
            $table->string('target')->nullable();
            $table->string('link_type')->nullable();
            $table->integer('sort_order')->nullable()->default(0);
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
        Schema::dropIfExists('menu_items');
    }
};
