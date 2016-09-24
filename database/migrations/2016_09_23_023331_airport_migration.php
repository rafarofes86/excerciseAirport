<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AirportMigration extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('airport', function(Blueprint $table)
		{  
			$table->increments('id');
                        $table->string('airport_name');
                        $table->string('city');
                        $table->string('country');
                        $table->string('faa');
                        $table->string('icao');
                        $table->float('latitude');
                        $table->float('longitude');
                        $table->integer('altitude');
                        $table->string('timezone');
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
		Schema::drop('airport');
	}

}
