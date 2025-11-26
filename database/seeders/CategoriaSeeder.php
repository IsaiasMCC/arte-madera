<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            ['nombre' => 'Esculturas de Madera', 'descripcion' => 'Esculturas artísticas talladas en madera de diferentes tamaños y estilos.'],
            ['nombre' => 'Muebles Artesanales', 'descripcion' => 'Muebles hechos a mano en madera sólida, únicos y personalizados.'],
            ['nombre' => 'Decoración', 'descripcion' => 'Elementos decorativos de madera para el hogar y oficina.'],
            ['nombre' => 'Instrumentos Musicales', 'descripcion' => 'Instrumentos de madera hechos artesanalmente, desde flautas hasta guitarras.'],
            ['nombre' => 'Juguetes de Madera', 'descripcion' => 'Juguetes educativos y artesanales hechos en madera segura y durable.'],
        ];

        foreach ($categorias as $cat) {
            Categoria::create($cat);
        }
    }
}
