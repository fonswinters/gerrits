<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->string('name');
            $table->text('description');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('hero_title');
            $table->text('hero_description');
            $table->boolean('hero_active')->default(0);
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('language_id');
            $table->unsignedBigInteger('hero_button_id');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
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
        Schema::dropIfExists('project_translations');
    }
}
