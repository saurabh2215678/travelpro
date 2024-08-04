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
        Schema::create('packages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title');
            $table->enum('tour_type', ['group', 'fixed', 'open', 'general'])->default('fixed');
            $table->integer('is_activity')->nullable()->default(0);
            $table->integer('vendor_id')->nullable();
            $table->integer('discount_category_id')->nullable();
            $table->mediumText('multiple_dates')->nullable();
            $table->integer('price_category')->index('price_category');
            $table->string('subtitle')->nullable();
            $table->string('package_duration', 100)->nullable();
            $table->integer('package_duration_days');
            $table->integer('activity_duration')->nullable()->default(0);
            $table->integer('activity_duration_type')->nullable()->default(1)->comment('1=Hours 2=Days');
            $table->string('slug');
            $table->integer('destination_id')->nullable()->index('package_destination_id');
            $table->integer('sub_destination_id')->nullable()->index('sub_destination_id');
            $table->text('related_destinations')->nullable();
            $table->mediumText('related_packages')->nullable();
            $table->string('best_months')->nullable();
            $table->boolean('activity_level')->default(true);
            $table->integer('package_type')->nullable();
            $table->text('package_inclusions')->nullable();
            $table->string('meal_option')->nullable();
            $table->text('experts')->nullable();
            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();
            $table->text('brief')->nullable();
            $table->text('description');
            $table->text('inclusions')->nullable();
            $table->text('exclusions')->nullable();
            $table->text('payment_policy')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->boolean('inclusions_chk')->nullable();
            $table->boolean('exclusions_chk')->nullable();
            $table->boolean('payment_policy_chk')->nullable();
            $table->boolean('cancellation_policy_chk')->nullable();
            $table->boolean('terms_conditions_chk')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('star_ratings', 10)->nullable();
            $table->boolean('status');
            $table->integer('is_deleted')->nullable()->default(0);
            $table->integer('inladakh')->nullable()->comment('1=inladakh 2="outsideladakh');
            $table->decimal('search_price', 15)->nullable()->comment('Use for filter in listing ');
            $table->boolean('featured');
            $table->boolean('popular');
            $table->integer('sort_order')->nullable()->default(0);
            $table->mediumText('video_link')->nullable();
            $table->integer('days')->nullable()->default(0);
            $table->integer('nights')->nullable()->default(0);
            $table->mediumText('days_nights_city')->nullable();
            $table->mediumText('tags')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
};
