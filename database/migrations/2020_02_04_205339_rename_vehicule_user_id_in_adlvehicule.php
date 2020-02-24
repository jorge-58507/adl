<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameVehiculeUserIdInAdlvehicule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adl_vehicules', function (Blueprint $table) {
            $table->renameColumn('vehicule_ai_user_id', 'vehicule_ai_company_id');
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
            $table->renameColumn('vehicule_ai_company_id', 'vehicule_ai_user_id');
        });
    }
}
