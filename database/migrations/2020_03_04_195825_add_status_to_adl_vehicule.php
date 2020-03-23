<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToAdlVehicule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adl_vehicules', function (Blueprint $table) {
            $table->integer('int_vehicule_status')->default(1)->after('tx_vehicule_slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adl_vehicules', function (Blueprint $table) {
            $table->dropColumn('int_vehicule_status');
        });
    }
}
