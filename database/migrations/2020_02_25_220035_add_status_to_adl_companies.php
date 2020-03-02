<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToAdlCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adl_companies', function (Blueprint $table) {
            $table->integer('int_company_status')->default(1)->after('tx_company_direction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adl_companies', function (Blueprint $table) {
            $table->dropColumn('int_company_status');
        });
    }
}
