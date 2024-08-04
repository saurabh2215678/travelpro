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
        Schema::create('enquiries_interactions_destinations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('enquiry_interaction_id')->nullable();
            $table->integer('destination_id')->nullable();
            $table->enum('type', ['destination', 'sub_destination', '', ''])->nullable()->default('destination');
            $table->integer('sort_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquiries_interactions_destinations');
    }
};
