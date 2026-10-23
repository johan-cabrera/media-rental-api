<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $mediaList = [
            [
                'name'          => 'Valla Carretera al Puerto Km 12',
                'type'          => 'billboard',
                'location'      => 'La Libertad',
                'dimensions'    => '12x4m',
                'price_per_day' => 120.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Pantalla LED Centro Comercial Galerías',
                'type'          => 'screen',
                'location'      => 'San Salvador',
                'dimensions'    => '6x4m',
                'price_per_day' => 250.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Mupi Blvd. Los Próceres #4',
                'type'          => 'mupi',
                'location'      => 'San Salvador',
                'dimensions'    => '1.2x1.8m',
                'price_per_day' => 45.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Muro Publicitario Masferrer Norte',
                'type'          => 'wall',
                'location'      => 'San Salvador',
                'dimensions'    => '15x8m',
                'price_per_day' => 180.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Pantalla Digital Redondel Masferrer',
                'type'          => 'screen',
                'location'      => 'San Salvador',
                'dimensions'    => '8x5m',
                'price_per_day' => 300.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Valla Autopista Comalapa Km 25',
                'type'          => 'billboard',
                'location'      => 'La Paz',
                'dimensions'    => '14x5m',
                'price_per_day' => 110.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Mupi Parada La Gran Vía',
                'type'          => 'mupi',
                'location'      => 'Antiguo Cuscatlán',
                'dimensions'    => '1.2x1.8m',
                'price_per_day' => 55.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Valla Bulevar Monseñor Romero Este',
                'type'          => 'billboard',
                'location'      => 'Santa Tecla',
                'dimensions'    => '12x5m',
                'price_per_day' => 140.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Pantalla LED Plaza Salvador del Mundo',
                'type'          => 'screen',
                'location'      => 'San Salvador',
                'dimensions'    => '10x6m',
                'price_per_day' => 350.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Muro Perimetral Zona Rosa',
                'type'          => 'wall',
                'location'      => 'San Salvador',
                'dimensions'    => '20x6m',
                'price_per_day' => 200.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Mupi Centro Histórico Catedral',
                'type'          => 'mupi',
                'location'      => 'San Salvador',
                'dimensions'    => '1.2x1.8m',
                'price_per_day' => 40.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Valla Entrada Santa Ana ByPass',
                'type'          => 'billboard',
                'location'      => 'Santa Ana',
                'dimensions'    => '10x4m',
                'price_per_day' => 90.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Muro Publicitario San Miguel Roosevelt',
                'type'          => 'wall',
                'location'      => 'San Miguel',
                'dimensions'    => '12x6m',
                'price_per_day' => 130.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Pantalla Curva Multiplaza Entrada Principal',
                'type'          => 'screen',
                'location'      => 'Antiguo Cuscatlán',
                'dimensions'    => '7x4m',
                'price_per_day' => 280.00,
                'status'        => 'active',
            ],
            [
                'name'          => 'Valla Carretera de Oro Km 8',
                'type'          => 'billboard',
                'location'      => 'Soyapango',
                'dimensions'    => '12x4m',
                'price_per_day' => 95.00,
                'status'        => 'inactive',
            ],
        ];

        foreach ($mediaList as $mediaData) {
            Media::create($mediaData);
        }
    }
}
