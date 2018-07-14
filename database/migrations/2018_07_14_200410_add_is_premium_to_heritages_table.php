<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsPremiumToHeritagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('heritages', function (Blueprint $table) {
            $table->boolean('is_premium')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('heritages', function (Blueprint $table) {
            $table->boolean('is_premium')->default(false);
        });
    }
}
