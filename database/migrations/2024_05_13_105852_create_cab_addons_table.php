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
        Schema::create('cab_addons', function (Blueprint $table) {
            if(!Schema::hasTable('cab_addons')) {
                $table->id();
                $table->string('name', 100)->nullable();
                $table->decimal('price',15,2)->default(0);
                $table->integer('sort_order')->default(0);
                $table->integer('featured')->default(0);
                $table->integer('daily_rental')->default(0);
                $table->integer('status')->default(1);
                $table->integer('is_deleted')->default(0);
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cab_addons');
    }
};
