<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterHeritagesMakeAssetsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('heritages', function (Blueprint $table) {
            $table->string('asset_name')->change()->nullable()->default(null);
            $table->string('asset_type')->change()->nullable()->default(null);
            $table->text('description')->change();
        });
    }
}
