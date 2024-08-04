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
        Schema::create('user_agent_info', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('company_name', 55)->nullable();
            $table->string('company_brand', 55)->nullable();
            $table->string('gst_image')->nullable();
            $table->string('gst_no', 120)->nullable();
            $table->string('pan_no', 120)->nullable();
            $table->string('pan_image')->nullable();
            $table->string('company_owner_name', 55)->nullable();
            $table->string('bookings_per_month', 25)->nullable();
            $table->string('agent_logo', 100)->nullable();
            $table->string('sales_team_size', 25)->nullable();
            $table->string('source', 30)->nullable();
            $table->string('website', 50)->nullable();
            $table->string('whatsapp_phone', 20)->nullable();
            $table->integer('whatsapp_country_code')->nullable();
            $table->string('destinations_sell_most', 25)->nullable();
            $table->string('agency_age', 55)->nullable();
            $table->string('no_of_employees', 70)->nullable();
            $table->string('region', 70)->nullable();
            $table->string('company_profile')->nullable();
            $table->string('query')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_agent_info');
    }
};
