<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Primero permisos y roles
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        // Usuarios
        $this->call([
            UserSeeder::class,
        ]);

        // Metodo Pagos

        $this->call([
            MetodoPagoSeeder::class,
        ]);

        // Tiendas
        $this->call([
            TiendaSeeder::class,
        ]);

        // Categorías (de arte en madera)
        $this->call([
            CategoriaSeeder::class,
        ]);

        // Productos (relacionados con categorías y tiendas)
        $this->call([
            ProductoSeeder::class,
        ]);

        // Pedidos (relacionados con usuarios y tiendas)
        $this->call([
            PedidoSeeder::class,
        ]);

        // Detalle de pedidos (relacionados con productos y pedidos)
        $this->call([
            DetallePedidoSeeder::class,
        ]);

        // Envíos (relacionados con pedidos)
        $this->call([
            EnvioSeeder::class,
        ]);

        // Pagos (relacionados con pedidos)
        $this->call([
            PagoSeeder::class,
        ]);

        // Detalle de pagos (relacionados con pagos)
        $this->call([
            DetallePagoSeeder::class,
        ]);
    }
}
