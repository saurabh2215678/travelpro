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
        Schema::create('air_services_api', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('air_services_id');
            $table->string('name')->nullable();
            $table->mediumText('request_json')->nullable();
            $table->mediumText('sample_response_json')->nullable();
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
        Schema::dropIfExists('air_services_api');
    }
};
