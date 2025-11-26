<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\Pedido;
use Faker\Factory as Faker;

class PagoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $pedidos = Pedido::all();

        if ($pedidos->count() == 0) {
            $this->command->info('No hay pedidos para asignar pagos.');
            return;
        }

        $tiposPago = ['EFECTIVO', 'TARJETA', 'TRANSFERENCIA'];

        foreach ($pedidos as $pedido) {
            Pago::create([
                'pedido_id' => $pedido->id,
                'fecha' => $faker->date('Y-m-d'),
                'hora' => $faker->time('H:i'),
                'monto' => $pedido->total, // podrías ajustar para pagos parciales si quieres
                'tipo_pago' => $tiposPago[array_rand($tiposPago)],
            ]);
        }
    }
}
