<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdlDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adl_datas', function (Blueprint $table) {
            $table->bigIncrements('ai_data_id');
            $table->integer('data_ai_user_id');
            $table->integer('data_ai_vehicule_id');
            $table->string('tx_data_date');
            $table->longText('tx_data_sample');
            $table->string('tx_data_slug');
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
        Schema::dropIfExists('adl_datas');
    }
}
