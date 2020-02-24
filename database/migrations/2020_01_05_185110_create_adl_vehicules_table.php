<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdlVehiculesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return voidw
	 */
	public function up()
	{
		Schema::create('adl_vehicules', function (Blueprint $table) {
			$table->bigIncrements('ai_vehicule_id');
			$table->integer('vehicule_ai_user_id');
			$table->string('tx_vehicule_licenseplate');
			$table->string('tx_vehicule_brand');
			$table->string('tx_vehicule_model');
			$table->string('tx_vehicule_slug');
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
        Schema::dropIfExists('adl_vehicules');
    }
}
