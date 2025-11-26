<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Faker\Factory as Faker;

class DetallePedidoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $pedidos = Pedido::all();
        $productos = Producto::all();

        if ($pedidos->count() == 0 || $productos->count() == 0) {
            $this->command->info('Necesitas pedidos y productos antes de crear detalles de pedidos.');
            return;
        }

        foreach ($pedidos as $pedido) {
            // Cada pedido puede tener entre 1 y 5 productos
            $productosSeleccionados = $productos->random(rand(1, 5));

            foreach ($productosSeleccionados as $producto) {
                $cantidad = $faker->numberBetween(1, 10);
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'subtotal' => $producto->precio, // subtotal unitario, si quieres total por cantidad multiplica luego
                ]);
            }
        }
    }
}
