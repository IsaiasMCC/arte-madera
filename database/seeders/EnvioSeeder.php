<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Envio;
use App\Models\Pedido;
use Faker\Factory as Faker;

class EnvioSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $pedidos = Pedido::all();

        if ($pedidos->count() == 0) {
            $this->command->info('No hay pedidos para asignar envíos.');
            return;
        }

        $estados = ['PENDIENTE', 'PREPARANDO', 'EN_CAMINO', 'ENTREGADO'];

        foreach ($pedidos as $pedido) {
            Envio::create([
                'pedido_id' => $pedido->id,
                'direccion' => $faker->streetAddress,
                'ciudad' => $faker->city,
                'codigo_postal' => $faker->postcode,
                'estado' => $estados[array_rand($estados)],
            ]);
        }
    }
}
