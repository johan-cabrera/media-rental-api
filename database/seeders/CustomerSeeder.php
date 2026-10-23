<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Banco Agrícola', 'email' => 'mercadeo@bancoagricola.com', 'phone' => '+503 2250-0000'],
            ['name' => 'Industrias La Constancia', 'email' => 'publicidad@ilc.com.sv', 'phone' => '+503 2222-1111'],
            ['name' => 'Tigo El Salvador', 'email' => 'campanas@tigo.com.sv', 'phone' => '+503 2500-0000'],
            ['name' => 'Claro El Salvador', 'email' => 'corporativo@claro.com.sv', 'phone' => '+503 2250-5555'],
            ['name' => 'Super Selectos', 'email' => 'medios@superselectos.com.sv', 'phone' => '+503 2267-7777'],
            ['name' => 'Pollo Campero El Salvador', 'email' => 'marketing@campero.com', 'phone' => '+503 2273-2000'],
            ['name' => 'Simán El Salvador', 'email' => 'publicidad@siman.com', 'phone' => '+503 2298-3000'],
            ['name' => 'Farmacias San Nicolás', 'email' => 'promociones@sannicolas.com', 'phone' => '+503 2555-5555'],
            ['name' => 'Universidad Don Bosco', 'email' => 'comunicaciones@udb.edu.sv', 'phone' => '+503 2251-8200'],
            ['name' => 'Grupo Q Automotriz', 'email' => 'ventas@grupoq.com', 'phone' => '+503 2248-6400'],
            ['name' => 'Teleperformance El Salvador', 'email' => 'recruiting@tp.com.sv', 'phone' => '+503 2507-9000'],
            ['name' => 'Aeroman', 'email' => 'talento@aeroman.com.sv', 'phone' => '+503 2347-1000'],
            ['name' => 'Bimbo de El Salvador', 'email' => 'contacto@bimbo.com.sv', 'phone' => '+503 2295-8000'],
            ['name' => 'Diana Snacks', 'email' => 'mercadeo@diana.com.sv', 'phone' => '+503 2250-4000'],
            ['name' => 'Súper Repuestos', 'email' => 'clientes@superrepuestos.com', 'phone' => '+503 2523-2323'],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }
    }
}
