<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacancyTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancy_translations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('vacancy_id');
            $table->unsignedBigInteger('language_id');

            $table->string('name');
            $table->string('slug');
            $table->text('description');

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->string('level');
            $table->string('experience');
            $table->string('salary');
            $table->string('hours');

            $table->boolean('hero_active')->default(0);
            $table->text('hero_title');
            $table->text('hero_description');
            $table->unsignedBigInteger('hero_button_id')->nullable();
            $table->timestamps();

            $table->foreign('vacancy_id')->references('id')->on('vacancies')->onDelete('cascade');
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
        Schema::dropIfExists('vacancy_translations');
    }
}
