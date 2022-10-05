<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('countries', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('name')->default('');
			$table->string('long_name')->default('');
			$table->char('iso_2', 2)->nullable();
			$table->char('iso_3', 3)->nullable();
			$table->string('iso_number', 6)->nullable();
			$table->string('calling_code', 8)->nullable();
			$table->string('cctld', 5)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('countries');
	}

}
