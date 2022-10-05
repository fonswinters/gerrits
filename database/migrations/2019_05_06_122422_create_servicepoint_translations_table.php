<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicepointTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicepoint_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('servicepoint_id');
            $table->unsignedBigInteger('language_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('function')->nullable();
            $table->string('telephone_label')->nullable();
            $table->string('telephone_url')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();

            $table->foreign('servicepoint_id')->references('id')->on('servicepoints')->onDelete('cascade');
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
        Schema::dropIfExists('servicepoint_translations');
    }
}
