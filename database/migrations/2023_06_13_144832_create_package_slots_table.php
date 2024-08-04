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
        Schema::create('package_slots', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('package_id');
            $table->integer('price_id');
            $table->integer('duration');
            $table->string('duration_type', 20);
            $table->string('start_time', 20);
            $table->tinyInteger('status')->nullable()->default(1);
            $table->dateTime('created_at');
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
        Schema::dropIfExists('package_slots');
    }
};
