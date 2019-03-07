<?php

use Illuminate\Database\Seeder;

class EstacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('taller.tblestaciones_est')->insert([
            [
                'est_descripcion' => 'AREQUIPA',
                'est_fecregistro' => date('d-m-Y'),
            ],
            [
                'est_descripcion' => 'ARRIOLA',
                'est_fecregistro' => date('d-m-Y'),
            ],
            [
                'est_descripcion' => 'CHICLAYO',
                'est_fecregistro' => date('d-m-Y'),
            ],
            [
                'est_descripcion' => 'CUZCO',
                'est_fecregistro' => date('d-m-Y'),
            ],
            [
                'est_descripcion' => 'TACNA',
                'est_fecregistro' => date('d-m-Y'),
            ],
            [
                'est_descripcion' => 'TALARA',
                'est_fecregistro' => date('d-m-Y'),
            ],
            [
                'est_descripcion' => 'TRUJILLO',
                'est_fecregistro' => date('d-m-Y'),
            ],
            [
                'est_descripcion' => 'TUMBES',
                'est_fecregistro' => date('d-m-Y'),
            ],
            [
                'est_descripcion' => 'PUNO',
                'est_fecregistro' => date('d-m-Y'),
            ]
        ]);
    }
}
