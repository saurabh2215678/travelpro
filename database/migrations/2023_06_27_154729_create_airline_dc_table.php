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
        Schema::create('airline_dc', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_group_id');
            $table->string('type',20);
            $table->string('domestic_offer_type',10);
            $table->integer('domestic_offer_value');
            $table->string('domestic_online_type',10);
            $table->integer('domestic_online_value');
            $table->string('international_offer_type',10);
            $table->integer('international_offer_value');
            $table->string('international_online_type',10);
            $table->integer('international_online_value');
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
        Schema::dropIfExists('airline_dc');
    }
};
