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
        Schema::create('cabs_sightseeing', function (Blueprint $table) {
            $table->id();
            $table->string('name',255)->nullable();
            $table->integer('origin')->nullable()->index('origin');
            $table->integer('destination')->nullable()->index('destination');
            $table->string('duration', 100)->nullable();
            $table->integer('distance')->nullable();
            $table->integer('route_type')->nullable()->default(0)->comment('0=>Transfer,1=>Sightseeing');
            $table->integer('discount_category_id')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->mediumText('places')->nullable();
            $table->text('description')->nullable();
            $table->integer('cab_route_group_id')->nullable()->index('cab_route_group_id');
            $table->string('start_time', 55);
            $table->boolean('sharing')->default(false);
            $table->boolean('featured')->default(false);
            $table->string('image')->nullable();
            $table->decimal('search_price',15,2)->default(0);
            $table->decimal('publish_price',15,2)->default(0);
            $table->boolean('is_deleted')->default(false);
            $table->integer('status')->nullable();
            $table->integer('sort_order');
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
        Schema::dropIfExists('cabs_sightseeing');
    }
};
