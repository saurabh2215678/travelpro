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
        Schema::create('airport_codes_master', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('code', 3)->nullable();
            $table->string('name', 47)->nullable();
            $table->string('citycode', 3)->nullable();
            $table->string('city', 43)->nullable();
            $table->string('country', 36)->nullable();
            $table->string('countrycode', 2)->nullable();
            $table->integer('sort_order')->nullable()->default(0);
            $table->integer('featured')->nullable()->default(0);
            $table->integer('status')->nullable()->default(1);
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
        Schema::dropIfExists('airport_codes_master');
    }
};
