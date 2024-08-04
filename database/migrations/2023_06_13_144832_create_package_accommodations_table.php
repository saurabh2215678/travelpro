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
        Schema::create('package_accommodations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('package_id')->nullable()->index('package_id');
            $table->integer('package_price_id')->nullable()->index('package_price_id');
            $table->integer('itenary_id')->nullable()->index('itenary_id');
            $table->integer('accommodation_id')->nullable()->index('pack_accommodation_id');
            $table->string('itenary_data')->nullable();
            $table->string('accommodation_data')->nullable();
            $table->integer('accommodation_type_id')->nullable()->index('pack_accommodation_type_id');
            $table->tinyInteger('service_level')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->boolean('is_default_without_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_accommodations');
    }
};
