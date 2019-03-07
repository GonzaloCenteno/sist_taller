<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call('EstacionesSeeder');
        $this->call('RutasSeeder');
        $this->call('RutaEstacionSeeder');
        $this->call('CapacidadSeeder');
        //$this->call('VehiculosSeeder');
        //$this->call('TripulantesSeeder');
    }
}
