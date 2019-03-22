<?php

use Illuminate\Database\Seeder;

class RutasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('taller.tblrutas_rut')->insert([
            [
                'rut_descripcion' => 'R1',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R2',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R3',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R4',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R5',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R6',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R7',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R8',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R9',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R10',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R11',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R12',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R13',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R14',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R15',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R16',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R17',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'R18',
                'rut_fecregistro' => date('d-m-Y'),
            ],
            [
                'rut_descripcion' => 'OTR',
                'rut_fecregistro' => date('d-m-Y'),
            ]
        ]);
    }
}
