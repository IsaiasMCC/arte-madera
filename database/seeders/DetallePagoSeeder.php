<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\DetallePago;
use Faker\Factory as Faker;

class DetallePagoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $pagos = Pago::all();

        if ($pagos->count() == 0) {
            $this->command->info('No hay pagos para asignar detalles.');
            return;
        }

        foreach ($pagos as $pago) {
            // Podemos simular 1 a 3 detalles por pago
            $numDetalles = rand(1, 3);
            $restante = $pago->monto;

            for ($i = 0; $i < $numDetalles; $i++) {
                // Si es el último detalle, le damos todo el saldo restante
                $monto = ($i === $numDetalles - 1) ? $restante : $faker->randomFloat(2, 1, $restante);
                $saldo = $restante - $monto;
                $restante -= $monto;

                DetallePago::create([
                    'pago_id' => $pago->id,
                    'fecha' => $faker->date('Y-m-d'),
                    'hora' => $faker->time('H:i'),
                    'monto' => $monto,
                    'saldo' => $saldo,
                ]);
            }
        }
    }
}
