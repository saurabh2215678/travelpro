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
        Schema::create('request_details', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('package_id')->index('req_package_id');
            $table->enum('form_type', ['enquire', 'request_itinerary'])->default('request_itinerary');
            $table->string('name');
            $table->string('phone', 15);
            $table->string('email', 100);
            $table->string('country');
            $table->string('zip_code', 25);
            $table->string('plan_to_travel');
            $table->boolean('travelled_with');
            $table->dateTime('created_at')->useCurrent();
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
        Schema::dropIfExists('request_details');
    }
};
