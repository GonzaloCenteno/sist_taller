<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTallerTblconsumocabeceraCca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taller.tblconsumocabecera_cca', function (Blueprint $table) {
            $table->increments('cca_id')->comment('ID DE REGISTRO Y NUMERO DE VALE CONSUMO');
            $table->integer('cca_nrovale')->unique()->comment('NUMERO DE VALE');
            $table->timestampTz('cca_fecregistro')->comment('FECHA REGISTRO CABECERA');
            $table->smallInteger('cca_estado')->default(1)->comment('ESTADO DE LA CABECERA CONSUMO');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taller.tblconsumocabecera_cca');
    }
}