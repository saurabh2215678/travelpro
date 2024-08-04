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
        Schema::create('room_master', function (Blueprint $table) {
            $table->integer('id', true);
            $table->enum('type', ['room_view', 'bed_type', 'extra_bed_type']);
            $table->string('name', 25);
            $table->boolean('sort_order')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('room_master');
    }
};
