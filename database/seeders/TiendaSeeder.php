<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tienda;

class TiendaSeeder extends Seeder
{
    public function run()
    {
        $tiendas = [
            ['nombre' => 'Tienda Central', 'nit' => '123456789', 'telefono' => '591-1234567', 'ubicacion' => 'Calle Principal #123'],
            ['nombre' => 'Tienda Norte', 'nit' => '987654321', 'telefono' => '591-7654321', 'ubicacion' => 'Av. Norte #456'],
            ['nombre' => 'Tienda Sur', 'nit' => '456789123', 'telefono' => '591-4567890', 'ubicacion' => 'Av. Sur #789'],
        ];

        foreach ($tiendas as $tienda) {
            Tienda::create($tienda);
        }
    }
}
