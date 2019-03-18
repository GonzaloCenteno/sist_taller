<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTallerTblcontrolCon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taller.tblcontrol_con', function (Blueprint $table) {
            $table->increments('con_id')->comment('ID CONTROL');
            $table->integer('est_id')->comment('ID DE LA ESTACION');
            $table->date('con_fecregistro')->comment('FECHA REGISTRO');
            $table->decimal('con_cantidad',7,3)->default(0.0)->comment('CANTIDAD EN LITROS');
            $table->decimal('con_ingreso',7,3)->default(0.0)->comment('INGRESO EN LITROS');
            $table->date('con_fecinicio')->nullable()->comment('FECHA INICIO');
            $table->date('con_fecfin')->nullable()->comment('FECHA FIN');
            $table->decimal('con_totsalida',7,3)->default(0.0)->comment('CANTIDAD TOTAL EN TANQUE');
            $table->decimal('con_stop',7,3)->default(0.0)->comment('STOP EN TANQUE');
            $table->foreign('est_id')->references('est_id')->on('taller.tblestaciones_est');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taller.tblcontrol_con');
    }
}
