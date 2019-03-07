<?php

use Illuminate\Database\Seeder;

class RutaEstacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('taller.tblrutasestacion_rte')->insert([
            [
                'rut_id' => 1,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 1,
                'est_id' => 2,
                'rte_consumo' => 40,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 1,
                'est_id' => 1,
                'rte_consumo' => 140,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 2,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 2,
                'est_id' => 2,
                'rte_consumo' => 70,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 2,
                'est_id' => 4,
                'rte_consumo' => 50,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 2,
                'est_id' => 1,
                'rte_consumo' => 150,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R3
            [
                'rut_id' => 3,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 3,
                'est_id' => 4,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 3,
                'est_id' => 2,
                'rte_consumo' => 120,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 3,
                'est_id' => 7,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 3,
                'est_id' => 2,
                'rte_consumo' => 120,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 3,
                'est_id' => 1,
                'rte_consumo' => 145,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R4
            [
                'rut_id' => 4,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 4,
                'est_id' => 2,
                'rte_consumo' => 70,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 4,
                'est_id' => 5,
                'rte_consumo' => 30,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 4,
                'est_id' => 1,
                'rte_consumo' => 145,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R5
            [
                'rut_id' => 5,
                'est_id' => 1,
                'rte_consumo' => 145,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 5,
                'est_id' => 2,
                'rte_consumo' => 70,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 5,
                'est_id' => 5,
                'rte_consumo' => 100,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 5,
                'est_id' => 2,
                'rte_consumo' => 120,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 5,
                'est_id' => 1,
                'rte_consumo' => 140,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R6
            [
                'rut_id' => 6,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 6,
                'est_id' => 5,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 6,
                'est_id' => 2,
                'rte_consumo' => 110,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 6,
                'est_id' => 1,
                'rte_consumo' => 130,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R7
            [
                'rut_id' => 7,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 7,
                'est_id' => 4,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 7,
                'est_id' => 2,
                'rte_consumo' => 120,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 7,
                'est_id' => 1,
                'rte_consumo' => 150,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R8
            [
                'rut_id' => 8,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 8,
                'est_id' => 4,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 8,
                'est_id' => 1,
                'rte_consumo' => 110,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R9
            [
                'rut_id' => 9,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 9,
                'est_id' => 2,
                'rte_consumo' => 80,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 9,
                'est_id' => 7,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 9,
                'est_id' => 2,
                'rte_consumo' => 80,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 9,
                'est_id' => 1,
                'rte_consumo' => 140,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R10
            [
                'rut_id' => 10,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 10,
                'est_id' => 5,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 10,
                'est_id' => 4,
                'rte_consumo' => 10,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 10,
                'est_id' => 5,
                'rte_consumo' => 45,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 10,
                'est_id' => 1,
                'rte_consumo' => 155,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R11
            [
                'rut_id' => 11,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 11,
                'est_id' => 5,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 11,
                'est_id' => 1,
                'rte_consumo' => 75,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R12
            [
                'rut_id' => 12,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 12,
                'est_id' => 4,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 12,
                'est_id' => 2,
                'rte_consumo' => 150,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 12,
                'est_id' => 3,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 12,
                'est_id' => 2,
                'rte_consumo' => 130,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 12,
                'est_id' => 1,
                'rte_consumo' => 150,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R13
            [
                'rut_id' => 13,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 13,
                'est_id' => 2,
                'rte_consumo' => 80,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 13,
                'est_id' => 3,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 13,
                'est_id' => 2,
                'rte_consumo' => 120,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 13,
                'est_id' => 1,
                'rte_consumo' => 135,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R14
            [
                'rut_id' => 14,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 14,
                'est_id' => 2,
                'rte_consumo' => 70,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 14,
                'est_id' => 8,
                'rte_consumo' => 110,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 14,
                'est_id' => 2,
                'rte_consumo' => 110,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 14,
                'est_id' => 1,
                'rte_consumo' => 140,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R15
            [
                'rut_id' => 15,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 15,
                'est_id' => 2,
                'rte_consumo' => 70,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 15,
                'est_id' => 3,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 15,
                'est_id' => 2,
                'rte_consumo' => 150,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 15,
                'est_id' => 5,
                'rte_consumo' => 20,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 15,
                'est_id' => 1,
                'rte_consumo' => 140,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            //R16
            [
                'rut_id' => 16,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 16,
                'est_id' => 2,
                'rte_consumo' => 70,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 16,
                'est_id' => 6,
                'rte_consumo' => 80,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 16,
                'est_id' => 2,
                'rte_consumo' => 100,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 16,
                'est_id' => 1,
                'rte_consumo' => 145,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 17,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 17,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 17,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 18,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 18,
                'est_id' => 9,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 18,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
            [
                'rut_id' => 19,
                'est_id' => 1,
                'rte_consumo' => 0,
                'rte_fecregistro' => date('d-m-Y'),
                'rte_anio' => date('Y')
            ],
        ]);
    }
}
