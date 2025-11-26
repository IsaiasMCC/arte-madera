<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MetodoPago;

class MetodoPagoSeeder extends Seeder
{
    public function run()
    {
        $metodos = [
            ['nombre' => 'Efectivo', 'descripcion' => 'Pago en efectivo al momento de la entrega'],
            ['nombre' => 'Tarjeta de Crédito', 'descripcion' => 'Pago con tarjeta de crédito'],
            ['nombre' => 'Tarjeta de Débito', 'descripcion' => 'Pago con tarjeta de débito'],
            ['nombre' => 'Transferencia Bancaria', 'descripcion' => 'Pago mediante transferencia bancaria'],
            ['nombre' => 'PayPal', 'descripcion' => 'Pago online mediante PayPal'],
        ];

        foreach ($metodos as $m) {
            MetodoPago::create($m);
        }
    }
}
