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
        Schema::table('accommodation_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('accommodation_plans', 'price')) {
                $table->decimal('price',15,2)->nullable()->after('plan_json_data');
            }
            if (!Schema::hasColumn('accommodation_plans', 'single_price')) {
                $table->decimal('single_price',15,2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('accommodation_plans', 'extra_adult')) {
                $table->decimal('extra_adult',15,2)->nullable()->after('single_price');
            }
            if (!Schema::hasColumn('accommodation_plans', 'extra_child_1')) {
                $table->decimal('extra_child_1',15,2)->nullable()->after('extra_adult');
            }
            if (!Schema::hasColumn('accommodation_plans', 'extra_child_2')) {
                $table->decimal('extra_child_2',15,2)->nullable()->after('extra_child_1');
            }
            if (!Schema::hasColumn('accommodation_plans', 'is_default')) {
                $table->boolean('is_default')->nullable()->default(false)->after('extra_child_2');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accommodation_plans', function (Blueprint $table) {
            //
        });
    }
};
