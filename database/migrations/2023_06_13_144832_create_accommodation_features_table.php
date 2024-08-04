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
        Schema::create('accommodation_features', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title');
            $table->string('icon')->nullable();
            $table->boolean('is_featured')->nullable();
            $table->integer('feature_type')->nullable()->default(0);
            $table->integer('sort_order');
            $table->integer('is_deleted')->nullable()->default(0);
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
        Schema::dropIfExists('accommodation_features');
    }
};
