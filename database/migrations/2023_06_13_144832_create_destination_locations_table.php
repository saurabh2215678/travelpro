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
        Schema::create('destination_locations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('destination_id')->nullable()->index('destination_id');
            $table->string('name', 100)->nullable();
            $table->integer('sort_order')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('destination_locations');
    }
};
