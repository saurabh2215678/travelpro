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
        Schema::create('cabs_cities', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->integer('discount_category_id')->nullable();
            $table->text('cab');
            $table->integer('per_day_km')->default(0);
            $table->text('inclusions')->nullable();
            $table->text('exclusions')->nullable();
            $table->text('terms')->nullable();
            $table->boolean('is_sharing')->default(false);
            $table->boolean('is_sightseeing')->default(false);
            $table->boolean('featured')->default(false);
            $table->boolean('is_airport')->default(false);
            $table->decimal('airport_entry_charge',15,2)->default(0);
            $table->integer('airport_max_distance')->default(0);
            $table->text('terminals')->nullable();
            $table->boolean('is_railway')->default(false);
            $table->decimal('station_entry_charge',15,2)->default(0);
            $table->integer('station_max_distance')->default(0);
            $table->text('stations')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('cabs_cities');
    }
};
