<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacancyProcessTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancy_process_translations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('vacancy_process_id');
            $table->unsignedBigInteger('language_id');

            $table->string('name');
            $table->text('description');

            $table->timestamps();

            $table->foreign('vacancy_process_id')->references('id')->on('vacancy_process')->onDelete('cascade');
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
        Schema::dropIfExists('vacancy_process_translations');
    }
}
