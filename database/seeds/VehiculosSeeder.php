<?php

use Illuminate\Database\Seeder;

class VehiculosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('taller.tblvehiculos_veh')->insert([
            [
                'veh_placa' => 'V6E-959',
            ],
            [
                'veh_placa' => 'VCF-958',
            ],
            [
                'veh_placa' => 'VDC-953',
            ],
            [
                'veh_placa' => 'V6E-912',
            ],
            [
                'veh_placa' => 'VDC-911',
            ],
        ]);
    }
}
