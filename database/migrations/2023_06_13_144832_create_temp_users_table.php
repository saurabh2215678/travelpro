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
        Schema::create('temp_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('is_agent')->nullable()->default(false);
            $table->boolean('is_vendor')->nullable()->default(false);
            $table->integer('group_id')->nullable();
            $table->boolean('approved_agent')->nullable()->default(false);
            $table->string('name');
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->integer('country_code')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('dob')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->integer('country')->nullable();
            $table->string('zipcode', 10)->nullable();
            $table->string('password')->nullable();
            $table->string('confirm_password')->nullable();
            $table->string('referrer')->nullable();
            $table->rememberToken();
            $table->string('verify_token')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('otp', 6)->nullable();
            $table->boolean('status')->nullable()->default(false);
            $table->string('forgot_token')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
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
        Schema::dropIfExists('temp_users');
    }
};
