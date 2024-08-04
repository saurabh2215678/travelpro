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
        Schema::create('faqs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('tbl_id')->nullable();
            $table->string('tbl_category', 25)->nullable();
            $table->string('tbl_name')->nullable();
            $table->integer('destination_id')->nullable()->index('faq_destination_id');
            $table->integer('sub_destination_id')->nullable()->index('faq_sub_destination_id');
            $table->string('category')->nullable();
            $table->mediumText('question');
            $table->text('answer');
            $table->integer('sort_order');
            $table->boolean('status');
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
        Schema::dropIfExists('faqs');
    }
};
