<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateButtonTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('button_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('button_id');
            $table->unsignedBigInteger('language_id');
            $table->string('label')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();

            $table->foreign('button_id')->references('id')->on('buttons')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('button_translations');
    }
}
