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
        Schema::create('custom_module_record_discount', function (Blueprint $table) {
            $table->id();
            $table->string('module_name', 25);
            $table->integer('module_record_id');
            $table->integer('agent_group_id');
            $table->string('discount_type', 15);
            $table->decimal('discount_value', 10, 0);
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
        Schema::dropIfExists('custom_module_record_discount');
    }
};
