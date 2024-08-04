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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('form_id')->nullable()->index('form_id');
            $table->string('enquiry_no_id', 100)->nullable()->unique('enquiry_no_id');
            $table->integer('order_id')->nullable();
            $table->integer('cc_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('country_code')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->mediumText('content')->nullable();
            $table->mediumText('inclusions')->nullable();
            $table->integer('destination')->nullable();
            $table->string('other_destination')->nullable();
            $table->integer('package_id')->nullable();
            $table->string('package_name')->nullable();
            $table->string('other_package')->nullable();
            $table->integer('accommodation')->nullable();
            $table->string('accommodation_comment')->nullable();
            $table->integer('activity')->nullable();
            $table->string('other_activity')->nullable();
            $table->integer('lead_source')->nullable();
            $table->integer('lead_status')->nullable();
            $table->integer('lead_sub_status')->nullable();
            $table->integer('rating')->nullable();
            $table->dateTime('followup_date')->nullable();
            $table->dateTime('date_of_travel')->nullable();
            $table->mediumText('meal_plans')->nullable();
            $table->integer('no_of_pax')->nullable();
            $table->integer('adu_abo_12')->nullable();
            $table->integer('chi_6_12')->nullable();
            $table->integer('chi_2_5')->nullable();
            $table->integer('infants_upto_2')->nullable();
            $table->integer('owner_id')->nullable();
            $table->dateTime('owner_date')->nullable();
            $table->mediumText('form_data')->nullable();
            $table->mediumText('refer_url')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('is_deleted')->nullable();
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
        Schema::dropIfExists('enquiries');
    }
};
