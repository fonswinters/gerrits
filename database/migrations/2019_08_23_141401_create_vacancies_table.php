<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('active');
            $table->string('code_name');
            $table->string('servicepoint_heading')->nullable();
            $table->bigInteger('lft')->nullable();
            $table->bigInteger('rgt')->nullable();
            $table->unsignedBigInteger('tree')->nullable();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->unsignedBigInteger('servicepoint_id')->nullable();
            $table->unsignedBigInteger('servicepoint_button_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancies');
    }
}
