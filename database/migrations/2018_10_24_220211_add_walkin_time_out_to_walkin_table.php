<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWalkinTimeOutToWalkinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('walkin', function (Blueprint $table) {
            //
            $table->string('walkin_time_out')->after('walkin_time');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('walkin', function (Blueprint $table) {
            $table->dropColumn('walkin_time_out');
        });
    }
}
