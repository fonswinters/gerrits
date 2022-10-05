<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendPostTranslationsProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_translations', function (Blueprint $table) {
            $table->string('hero_title');
            $table->text('hero_description');
            $table->unsignedBigInteger('hero_button_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_translations', function (Blueprint $table) {
            $table->dropColumn('hero_title');
            $table->dropColumn('hero_description');
            $table->dropColumn('hero_button_id');
        });
    }
}
