<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('lft')->nullable();
            $table->integer('rgt')->nullable();
            $table->integer('tree')->nullable();
            $table->integer('site_id')->nullable();

            $table->boolean('active');
            $table->string('name');
            $table->string('email');
            $table->string('linkedinurl');

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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('team_members');
        Schema::enableForeignKeyConstraints();
    }
}
