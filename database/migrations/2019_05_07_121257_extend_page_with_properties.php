<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendPageWithProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function($table) {
            $table->integer('inNav');
            $table->unsignedBigInteger('servicepoint_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function($table) {
            $table->dropColumn('inNav');
            $table->dropColumn('servicepoint_id');
        });
    }
}
