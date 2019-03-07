<?php

use Illuminate\Database\Seeder;

class TripulantesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('taller.tbltripulantes_tri')->insert([
            [
                'tri_nrodoc' => '47600274',
                'tri_nombre' => 'GONZALO JAVIER',
                'tri_apaterno' => 'CENTENO',
                'tri_amaterno' => 'ZAPATA',
            ],
            [
                'tri_nrodoc' => '48761612',
                'tri_nombre' => 'EDMUNDO AURELIO',
                'tri_apaterno' => 'APARICIO',
                'tri_amaterno' => 'MALDONADO',
            ],
            [
                'tri_nrodoc' => '74414515',
                'tri_nombre' => 'CARLOS',
                'tri_apaterno' => 'MARTINEZ',
                'tri_amaterno' => 'VELAZQUES',
            ],
            [
                'tri_nrodoc' => '47856332',
                'tri_nombre' => 'JORGE JUAN',
                'tri_apaterno' => 'MAMANI',
                'tri_amaterno' => 'CORRALES',
            ],
            [
                'tri_nrodoc' => '47852554',
                'tri_nombre' => 'VICTOR',
                'tri_apaterno' => 'CACERES',
                'tri_amaterno' => 'MARTIN',
            ],
        ]);
    }
}
