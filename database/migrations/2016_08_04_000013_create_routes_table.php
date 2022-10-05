<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('routes', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('route');
			$table->string('alias');
			$table->unsignedBigInteger('routable_id');
			$table->string('routable_type');
			$table->unsignedBigInteger('site_id');
			$table->unsignedBigInteger('language_id');
			$table->timestamps();

            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('site_id')->references('id')->on('sites');

//            $table->unique('alias', 'site_id'); //There can be no 2 or more records having the same site_id and alias.
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('routes');
	}

}
