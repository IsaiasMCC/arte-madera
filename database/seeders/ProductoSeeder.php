<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Tienda;
use Faker\Factory as Faker;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $categorias = Categoria::all();
        $tiendas = Tienda::all();

        if ($categorias->count() == 0 || $tiendas->count() == 0) {
            $this->command->info('No hay categorías o tiendas para asignar productos.');
            return;
        }

        $productos = [
            ['nombre' => 'Figura de Búho Tallado', 'descripcion' => 'Pequeña escultura de búho hecha en madera de nogal.', 'precio' => 35.50, 'stock' => 10],
            ['nombre' => 'Mesa de Centro Artesanal', 'descripcion' => 'Mesa de centro tallada a mano en madera de roble.', 'precio' => 250.00, 'stock' => 5],
            ['nombre' => 'Portarretratos Rústico', 'descripcion' => 'Marco de fotos hecho en madera reciclada.', 'precio' => 15.75, 'stock' => 20],
            ['nombre' => 'Flauta de Madera', 'descripcion' => 'Flauta artesanal hecha en madera de cedro.', 'precio' => 45.00, 'stock' => 8],
            ['nombre' => 'Juguete de Encastre', 'descripcion' => 'Juguete educativo de madera para niños.', 'precio' => 25.00, 'stock' => 15],
        ];

        foreach ($productos as $prod) {
            Producto::create([
                'nombre' => $prod['nombre'],
                'descripcion' => $prod['descripcion'],
                'precio' => $prod['precio'],
                'stock' => $prod['stock'],
                'categoria_id' => $categorias->random()->id,
                'tienda_id' => $tiendas->random()->id,
                'estado' => true,
                'foto' => null
            ]);
        }
    }
}
