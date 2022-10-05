<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferenceTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reference_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reference_id');
            $table->unsignedBigInteger('language_id');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->text('quote');
            $table->timestamps();

            $table->foreign('reference_id')->references('id')->on('references')->onDelete('cascade');
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
        Schema::dropIfExists('reference_translations');
    }
}
