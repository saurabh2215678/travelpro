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
        Schema::create('customize_package_enquaries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('package_id')->index('pack_package_id');
            $table->string('name');
            $table->string('phone', 15);
            $table->string('email', 100);
            $table->string('country');
            $table->string('zip_code', 25);
            $table->string('want_to_travel');
            $table->integer('no_of_packs');
            $table->integer('duration_of_stay');
            $table->boolean('need_flight')->nullable();
            $table->boolean('travelled_with')->nullable();
            $table->text('content');
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
        Schema::dropIfExists('customize_package_enquaries');
    }
};
