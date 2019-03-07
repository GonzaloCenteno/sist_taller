<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTallerTblcapacidadCap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taller.tblcapacidad_cap', function (Blueprint $table) {
            $table->increments('cap_id')->comment('ID CAPACIDAD VEHICULO');
            $table->integer('cap_val')->comment('CAPACIDAD DEL VEHICULO');
            $table->date('cap_fecregistro')->comment('FECHA REGISTRO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taller.tblcapacidad_cap');
    }
}
