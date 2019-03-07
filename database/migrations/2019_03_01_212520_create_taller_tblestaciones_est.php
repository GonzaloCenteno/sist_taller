<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTallerTblestacionesEst extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taller.tblestaciones_est', function (Blueprint $table) {
            $table->increments('est_id')->comment('ID ESTACION');
            $table->string('est_descripcion',100)->unique()->comment('DESCRIPCION ESTACION');
            $table->string('est_usumodificacion',100)->default('-')->nullable()->comment('ULTIMO USUARIO QUE MODIFICO');
            $table->date('est_fecregistro')->comment('FECHA REGISTRO');
            $table->date('est_fecmodificacion')->nullable()->comment('FECHA ACTUALIZACION');
            $table->smallInteger('est_estado')->default(1)->comment('ESTADO ESTACION, 1 ACTIVO - 0 INACTIVO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taller.tblestaciones_est');
    }
}
