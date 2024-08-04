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
        Schema::create('accommodations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('slug');
            $table->integer('destination_id')->nullable()->index('destination_id');
            $table->string('name')->nullable();
            $table->integer('vendor_id')->default(0);
            $table->string('accommodation_feature');
            $table->string('accommodation_facility');
            $table->integer('category_id')->nullable()->index('category_id');
            $table->boolean('star_classification');
            $table->string('related_hotels');
            $table->string('address');
            $table->string('contact_phone', 20);
            $table->string('contact_email', 100);
            $table->mediumText('map');
            $table->tinyInteger('rating');
            $table->integer('total_room')->nullable();
            $table->time('checkin_time');
            $table->time('checkout_time');
            $table->text('brief');
            $table->text('description');
            $table->text('policies')->nullable();
            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('latitude');
            $table->string('longtitude');
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->integer('sort_order');
            $table->boolean('status')->default(true);
            $table->boolean('featured')->default(false);
            $table->boolean('is_deleted')->nullable();
            $table->decimal('search_price', 15);
            $table->integer('discount_category_id')->nullable();
            $table->integer('booking_mode')->nullable()->comment('0=>Automatic booking mode, 1=>Booking needs approval');
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
        Schema::dropIfExists('accommodations');
    }
};
