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
        Schema::create('seo_url_redirection', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('url_type')->nullable()->default(0)->comment('0=>Marketting Url,1=>Seo Url');
            $table->string('source_url',255)->nullable();
            $table->string('destination_url',255)->nullable();
            $table->integer('status_code')->nullable();
            $table->tinyInteger('show')->nullable();
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
        Schema::dropIfExists('seo_url_redirection');
    }
};
