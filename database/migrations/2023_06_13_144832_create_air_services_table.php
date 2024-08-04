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
        Schema::create('air_services', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('services_name')->nullable();
            $table->string('services_url')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=Inactive 1=Active');
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
        Schema::dropIfExists('air_services');
    }
};
