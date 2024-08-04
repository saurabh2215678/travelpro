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
        Schema::create('widgets', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('widget_name');
            $table->string('slug')->nullable();
            $table->string('section_heading');
            $table->string('section_sub_heading')->nullable();
            $table->text('about_widget_desc')->nullable();
            $table->mediumText('description');
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
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
        Schema::dropIfExists('widgets');
    }
};
