<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTallerTblrutasestacionRte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taller.tblrutasestacion_rte', function (Blueprint $table) {
            $table->increments('rte_id')->comment('ID PUENTE RUTA - ESTACION');
            $table->integer('rut_id')->comment('ID DE LA RUTA');
            $table->integer('est_id')->comment('ID DE LA ESTACION');
            $table->integer('rte_consumo')->default(0)->comment('CONSUMO DE LA RUTA');
            $table->string('rte_usumodificacion',100)->default('-')->nullable()->comment('ULTIMO USUARIO QUE MODIFICO');
            $table->date('rte_fecregistro')->comment('FECHA REGISTRO');
            $table->date('rte_fecmodificacion')->nullable()->comment('FECHA ACTUALIZACION');
            $table->string('rte_anio')->comment('AÑO DE CREACION');
            $table->smallInteger('rte_estado')->default(1)->comment('ESTADO RUTA, 1 ACTIVO - 0 INACTIVO');
            $table->foreign('rut_id')->references('rut_id')->on('taller.tblrutas_rut');
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
        Schema::dropIfExists('taller.tblrutasestacion_rte');
    }
}