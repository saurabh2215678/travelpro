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
        Schema::create('agent_airline_markup', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('agent_id');
            $table->string('code', 20);
            $table->string('name', 100);
            $table->string('offer_markup_type', 10);
            $table->integer('offer_markup_value');
            $table->string('online_markup_type', 10);
            $table->integer('online_markup_value');
            $table->integer('sort_order');
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('agent_airline_markup');
    }
};
