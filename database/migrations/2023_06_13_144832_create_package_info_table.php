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
        Schema::create('package_info', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('package_id')->index('package_id');
            $table->string('title');
            $table->text('brief')->nullable();
            $table->text('description');
            $table->boolean('status');
            $table->smallInteger('sort_order');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_info');
    }
};
