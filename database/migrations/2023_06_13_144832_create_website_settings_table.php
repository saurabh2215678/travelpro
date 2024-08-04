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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['website', 'seo', 'social_links'])->nullable()->default('website');
            $table->boolean('is_dedicated')->nullable()->default(false);
            $table->string('label', 100)->nullable();
            $table->string('name', 45);
            $table->text('value')->nullable();
            $table->text('old_value')->nullable();
            $table->string('field_type', 50)->nullable()->default('text');
            $table->string('css_class', 50)->nullable();
            $table->integer('created_by')->nullable()->default(0)->comment('Admin ID');
            $table->integer('updated_by')->nullable()->default(0)->comment('Admin ID');
            $table->integer('sort_order')->nullable()->default(0);
            $table->boolean('status')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_settings');
    }
};
