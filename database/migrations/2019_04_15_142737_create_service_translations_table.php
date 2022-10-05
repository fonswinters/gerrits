<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('language_id');
            $table->string('slug');
            $table->string('name');
            $table->text('description');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('hero_description');
            $table->text('hero_title');
            $table->boolean('hero_active')->default(0);
            $table->unsignedBigInteger('hero_button_id');
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
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
        Schema::dropIfExists('service_translations');
    }
}
