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
        Schema::create('customer_reviews', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('trip_name_duration')->nullable();
            $table->string('trip_date', 50)->nullable();
            $table->string('local_guide_name')->nullable();
            $table->string('driver_name')->nullable();
            $table->boolean('courteous')->default(false);
            $table->boolean('helpful')->default(false);
            $table->boolean('informative')->default(false);
            $table->text('guide_comments')->nullable();
            $table->boolean('sightseeing_tour')->default(false);
            $table->text('sight_tour_comments')->nullable();
            $table->boolean('driver')->default(false);
            $table->boolean('vehicle')->default(false);
            $table->text('transportation_comment')->nullable();
            $table->boolean('comfort')->default(false);
            $table->boolean('services')->default(false);
            $table->boolean('food')->default(false);
            $table->text('accommodation_comments')->nullable();
            $table->boolean('foods')->default(false);
            $table->boolean('camp_staff')->default(false);
            $table->boolean('pony_yak')->default(false);
            $table->text('trekking_comments')->nullable();
            $table->boolean('on_tour')->default(false);
            $table->boolean('on_trek')->default(false);
            $table->text('garbage_disposal_coomments')->nullable();
            $table->string('name')->nullable();
            $table->text('if_so_why')->nullable();
            $table->text('highlight_of_trip')->nullable();
            $table->text('low_point')->nullable();
            $table->boolean('staff_on_the_trip')->default(false);
            $table->boolean('trip_expectations')->default(false);
            $table->text('overall_comments')->nullable();
            $table->string('your_name');
            $table->string('address');
            $table->string('email', 100);
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
        Schema::dropIfExists('customer_reviews');
    }
};
