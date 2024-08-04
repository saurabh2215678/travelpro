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
        Schema::create('contact_enquiries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->string('phone', 15);
            $table->string('contact_email', 100);
            $table->string('month_of_travel')->nullable();
            $table->string('duration', 25)->default('0');
            $table->integer('person_count')->default(0);
            $table->text('comment');
            $table->string('country');
            $table->dateTime('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_enquiries');
    }
};
