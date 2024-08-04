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
        Schema::create('price_category_elements', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('pc_id')->index('pc_id');
            $table->string('price_label');
            $table->string('input_label');
            $table->string('input_type');
            $table->mediumText('input_choices');
            $table->integer('sort_order')->nullable()->default(0);
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
        Schema::dropIfExists('price_category_elements');
    }
};
