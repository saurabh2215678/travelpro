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
        Schema::create('agent_discount_module_to_group', function (Blueprint $table) {
            $table->integer('id', true);
            $table->boolean('is_default')->nullable()->default(false);
            $table->string('module_name', 25);
            $table->integer('module_discount_type_id');
            $table->integer('agent_group_id');
            $table->string('discount_type', 15);
            $table->decimal('discount_value', 10, 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_discount_module_to_group');
    }
};
