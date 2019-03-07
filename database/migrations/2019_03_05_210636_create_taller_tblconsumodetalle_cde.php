<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTallerTblconsumodetalleCde extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taller.tblconsumodetalle_cde', function (Blueprint $table) {
            $table->increments('cde_id')->comment('ID DETALLE CONSUMO');
            $table->date('cde_fecha')->nullable()->comment('FECHA DIGITADA');
            $table->integer('cca_id')->comment('ID DE LA CABECERA DEL CONSUMO');
            $table->integer('veh_id')->comment('ID DE LA PLACA DEL VEHICULO');
            $table->integer('rut_id')->comment('ID RUTA');
            $table->integer('est_id')->comment('ID ESTACION');
            $table->integer('tri_idconductor')->nullable()->default(1)->comment('ID CONDUCTOR');
            $table->integer('tri_idcopiloto')->nullable()->default(1)->comment('ID COPILOTO');
            $table->integer('cde_kilometros')->nullable()->default(0)->comment('KILOMETROS RECORRIDOS');
            $table->integer('cde_xtanque')->nullable()->default(0)->comment('% STOP EN TANQUE');
            $table->decimal ('cde_qlttanque',7,3)->nullable()->default(0.0)->comment('Q - LT. STOP EN TANQUE');
            $table->integer('cde_xconsumida')->nullable()->default(0)->comment('% Q - CONSUMIDA');
            $table->decimal ('cde_qltconsumida',7,3)->nullable()->default(0.0)->comment('Q - LT. CONSUMIDA');
            $table->decimal ('cde_qabastecida',7,3)->nullable()->default(0.0)->comment('Q - ABASTECIDA');
            $table->string('cde_observaciones',255)->nullable()->default('-')->comment('CAMPO OBSERVACIONES');
            $table->decimal ('cde_ingreso',7,3)->nullable()->default(0.0)->comment('CANTIDAD DE INGRESO');
            $table->decimal ('cde_salida',7,3)->nullable()->default(0.0)->comment('CANTIDAD DE SALIDA');
            $table->decimal ('cde_stop',7,3)->nullable()->default(0.0)->comment('CANTIDAD STOP');
            $table->date('cde_fecregistro')->comment('FECHA REGISTRO');
            $table->date('cde_fecmodificacion')->nullable()->comment('FECHA ACTUALIZACION');
            $table->string('cde_usumodificacion',100)->default('-')->nullable()->comment('ULTIMO USUARIO QUE MODIFICO');
            $table->smallInteger('cde_estado')->default(1)->comment('ESTADO DEL DETALLE CONSUMO');
            $table->string('cde_anio',4)->comment('AÑO DE REGISTRO');
            $table->foreign('cca_id')->references('cca_id')->on('taller.tblconsumocabecera_cca');
            $table->foreign('veh_id')->references('veh_id')->on('taller.tblvehiculos_veh');
            $table->foreign('rut_id')->references('rut_id')->on('taller.tblrutas_rut');
            $table->foreign('est_id')->references('est_id')->on('taller.tblestaciones_est');
            $table->foreign('tri_idconductor')->references('tri_id')->on('taller.tbltripulantes_tri');
            $table->foreign('tri_idcopiloto')->references('tri_id')->on('taller.tbltripulantes_tri');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taller.tblconsumodetalle_cde');
    }
}
