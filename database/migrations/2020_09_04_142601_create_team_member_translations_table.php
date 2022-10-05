<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamMemberTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_member_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_member_id');
            $table->unsignedBigInteger('language_id');
            $table->string('function');
            $table->timestamps();

            $table->foreign('team_member_id')->references('id')->on('team_members')->onDelete('cascade');
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
        Schema::dropIfExists('team_member_translations');
    }
}
