<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdlCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adl_companies', function (Blueprint $table) {
            $table->bigIncrements('ai_company_id');
            $table->integer('company_ai_user_id');
            $table->string('tx_company_description');
            $table->string('tx_company_ruc');
            $table->string('tx_company_telephone');
            $table->string('tx_company_direction');
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
        Schema::dropIfExists('adl_companies');
    }
}
