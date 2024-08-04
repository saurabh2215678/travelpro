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
        Schema::create('accommodation_room', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('accommodation_id')->index('accommodation_id');
            $table->integer('room_type_id')->nullable()->index('room_type_id');
            $table->string('room_type_name', 55)->nullable();
            $table->tinyInteger('single_bed')->nullable();
            $table->tinyInteger('double_bed')->nullable();
            $table->tinyInteger('extra_adult_bed')->nullable();
            $table->integer('base_occupancy')->nullable();
            $table->integer('max_adult_bed')->nullable();
            $table->integer('base_child_no')->nullable();
            $table->integer('max_child_no')->nullable();
            $table->integer('max_occupancy')->nullable();
            $table->text('brief');
            $table->text('description');
            $table->integer('quantity');
            $table->integer('max_quantity');
            $table->integer('minimum_stay');
            $table->integer('no_of_exrta_beds');
            $table->integer('max_adults');
            $table->integer('max_children');
            $table->string('room_features');
            $table->integer('total_room')->nullable();
            $table->integer('room_view')->nullable();
            $table->integer('bed_type')->nullable();
            $table->integer('extra_bed_type')->nullable();
            $table->decimal('single_price', 15)->nullable();
            $table->boolean('status');
            $table->smallInteger('sort_order');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
            $table->decimal('base_price', 15)->nullable();
            $table->decimal('price_extra_adult', 15)->nullable();
            $table->tinyInteger('extra_child_bed')->nullable();
            $table->decimal('price_extra_child_1', 15)->nullable();
            $table->decimal('price_extra_child_2', 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accommodation_room');
    }
};
