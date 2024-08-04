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
        Schema::create('enquiries_interactions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('enquiry_id')->nullable()->index('enquiry_id');
            $table->integer('type')->nullable();
            $table->integer('cc_id')->nullable();
            $table->integer('destination')->nullable();
            $table->integer('sub_destination')->nullable();
            $table->integer('package')->nullable();
            $table->integer('accommodation')->nullable();
            $table->integer('activity')->nullable();
            $table->integer('lead_source')->nullable();
            $table->integer('lead_status')->nullable();
            $table->integer('lead_sub_status')->nullable();
            $table->integer('rating')->nullable();
            $table->dateTime('followup_date')->nullable();
            $table->mediumText('comment')->nullable();
            $table->integer('created_by')->nullable();
            $table->enum('created_type', ['backend', 'customer', 'agent', ''])->nullable()->default('customer');
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
        Schema::dropIfExists('enquiries_interactions');
    }
};
