<?php

use Illuminate\Database\Seeder;

class CapacidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('taller.tblcapacidad_cap')->insert([
            [
                'cap_val' => 50,
                'cap_fecregistro' => date('d-m-Y'),
            ],
            [
                'cap_val' => 100,
                'cap_fecregistro' => date('d-m-Y'),
            ],
            [
                'cap_val' => 150,
                'cap_fecregistro' => date('d-m-Y'),
            ],
        ]);
    }
}
