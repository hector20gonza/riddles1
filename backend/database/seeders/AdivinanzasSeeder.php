<?php

namespace Database\Seeders;

use App\Models\adivinanzas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdivinanzasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $adivinanzas = [
            
            [
                'pregunta' => 'Largo como una serpiente, pesado como un tronco, al que de los pies a la cabeza siempre encuentras en el bronce.',
                'respuesta' => 'El metro',
            ],
            
            [
                'pregunta' => 'No tiene huesos y lleva su carga a cuestas.',
                'respuesta' => 'La mochila',
            ],
            [
                'pregunta' => 'En el agua me crié, en el agua me metí, y si no te digo cómo me llamo no sabes quién soy yo.',
                'respuesta' => 'El pez',
            ],
            [
                'pregunta' => 'Tiene ojos y no puede ver, tiene pico y no puede comer.',
                'respuesta' => 'La aguja',
            ],
            [
                'pregunta' => 'La encuentras en el campo, en la playa y en la ciudad, si quieres jugar con ella, siempre debes estar en su mitad.',
                'respuesta' => 'La pelota',
            ],
            [
                'pregunta' => 'Blanco por dentro, verde por fuera; si quieres que te lo diga, espera.',
                'respuesta' => 'La pera',
            ],
            [
                'pregunta' => 'De muchos colores me adornan, con mi dulce música te alegraré, si me das una vuelta y me giras, mi danza te mostraré.',
                'respuesta' => 'El carrusel',
            ],
            [
                'pregunta' => 'Cuatro patas tiene un pato, dos alante y dos atrás, y cuando camina por el agua nunca se moja los pies.',
                'respuesta' => 'El pato',
            ],
        ];

        foreach ($adivinanzas as $adivinanza) {
            adivinanzas::create($adivinanza);
        }
    }
}
