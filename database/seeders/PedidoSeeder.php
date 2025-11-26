<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\User;
use App\Models\Tienda;
use Faker\Factory as Faker;

class PedidoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $usuarios = User::all();
        $tiendas = Tienda::all();

        if ($usuarios->count() == 0 || $tiendas->count() == 0) {
            $this->command->info('Necesitas usuarios y tiendas antes de crear pedidos.');
            return;
        }

        // Crear 20 pedidos de ejemplo
        for ($i = 0; $i < 20; $i++) {
            Pedido::create([
                'fecha' => $faker->date('Y-m-d'),
                'hora' => $faker->time('H:i'),
                'total' => $faker->randomFloat(2, 50, 500),
                'estado' => $faker->randomElement(['PENDIENTE', 'FINALIZADO', 'CANCELADO']),
                'user_id' => $usuarios->random()->id,
                'tienda_id' => $tiendas->random()->id,
            ]);
        }
    }
}
