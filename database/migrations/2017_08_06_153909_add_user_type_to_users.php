<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserTypeToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_type',50)->default('family');
        });
    }
    
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('user_type');
        });
    }
}
