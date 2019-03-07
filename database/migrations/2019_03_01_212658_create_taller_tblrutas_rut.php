<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTallerTblrutasRut extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taller.tblrutas_rut', function (Blueprint $table) {
            $table->increments('rut_id')->comment('ID DE LA RUTA');
            $table->string('rut_descripcion',3)->unique()->comment('DESCRIPCION DE LA RUTA');
            $table->string('rut_usumodificacion',100)->default('-')->nullable()->comment('ULTIMO USUARIO QUE MODIFICO');
            $table->date('rut_fecregistro')->comment('FECHA REGISTRO');
            $table->date('rut_fecmodificacion')->nullable()->comment('FECHA ACTUALIZACION');
            $table->smallInteger('rut_estado')->default(1)->comment('ESTADO RUTA, 1 ACTIVO - 0 INACTIVO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taller.tblrutas_rut');
    }
}