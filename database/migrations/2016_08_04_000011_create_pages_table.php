<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->boolean('active');
			$table->string('code_name')->nullable();
            $table->boolean('has_wildcard')->default(0);
            $table->string('servicepoint_heading');
            $table->unsignedBigInteger('servicepoint_button_id');
			$table->bigInteger('lft')->nullable();
			$table->bigInteger('rgt')->nullable();
			$table->unsignedBigInteger('tree')->nullable();
			$table->unsignedBigInteger('site_id')->nullable();
			$table->timestamps();
            $table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pages');
	}

}
