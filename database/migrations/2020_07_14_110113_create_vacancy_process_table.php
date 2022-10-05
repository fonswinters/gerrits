<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacancyProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancy_process', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('lft')->nullable();
            $table->bigInteger('rgt')->nullable();
            $table->unsignedBigInteger('tree')->nullable();
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
        Schema::dropIfExists('vacancy_process');
    }
}
