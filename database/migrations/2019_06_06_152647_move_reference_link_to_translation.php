<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveReferenceLinkToTranslation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reference_translations', function (Blueprint $table) {
            $table->string('url')->nullable();
        });

        Schema::table('references', function (Blueprint $table) {
            $table->dropColumn('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reference_translations', function (Blueprint $table) {
           $table->dropColumn('url');
        });

        Schema::table('references', function (Blueprint $table) {
            $table->string('url')->nullable();
        });
    }
}
