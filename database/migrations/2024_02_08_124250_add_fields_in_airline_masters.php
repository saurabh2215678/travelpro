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
        Schema::table('airline_masters', function (Blueprint $table) {
            if (!Schema::hasColumn('airline_masters', 'image')) {
                $table->string('image', 255)->after('name');
            }
            if (!Schema::hasColumn('airline_masters', 'featured')) {
                $table->integer('featured')->default(0)->after('image');
            }
            if (!Schema::hasColumn('airline_masters', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('featured');
            }
            if (!Schema::hasColumn('airline_masters', 'status')) {
                $table->integer('status')->default(0)->after('sort_order');
            }
            if (Schema::hasColumn('airline_masters', 'created_at')) {
                $table->dateTime('created_at')->nullable()->change();
            }
            if (Schema::hasColumn('airline_masters', 'updated_at')) {
                $table->dateTime('updated_at')->nullable()->change();
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
        Schema::table('airline_masters', function (Blueprint $table) {
            //
        });
    }
};
