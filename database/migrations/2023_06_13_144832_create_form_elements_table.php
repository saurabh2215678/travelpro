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
        Schema::create('form_elements', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('form_id')->nullable()->index('form_id');
            $table->string('label', 100);
            $table->string('placeHolder')->nullable();
            $table->string('type', 100)->nullable();
            $table->string('validation');
            $table->integer('position')->default(999);
            $table->mediumText('data');
            $table->string('className')->nullable();
            $table->string('classNameInner')->nullable();
            $table->string('enquiryMapping')->nullable();
            $table->string('data_table')->nullable();
            $table->string('defaultValue')->nullable();
            $table->boolean('is_display')->default(false);
            $table->tinyInteger('is_static')->nullable()->default(0);
            $table->boolean('is_hide')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('form_elements');
    }
};
