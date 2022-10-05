<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('active');
            $table->string('code_name')->nullable();
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
        Schema::dropIfExists('services');
    }
}
