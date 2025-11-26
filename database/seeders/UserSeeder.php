<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //ADMINISTRADORES
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'Administrador'], ['guard_name' => 'web']);
        $roleSuperAdmin->syncPermissions(Permission::all());
        $admin = User::firstOrCreate([
            'ci' => '12345678',
            'nombres' => 'Juan',
            'apellidos' => 'Perez',
            'email' => 'juan@gmail.com',
            'password' => Hash::make('12345678'),
            'estado' => true,
            'rol_id' => 1
        ]);

        if (!$admin->hasRole('Administrador')) {
            $admin->assignRole($roleSuperAdmin);
        }
        //SUPERVISOR
        DB::table('users')->insert([
            [
                'ci' => '589854',
                'nombres' => 'Maria',
                'apellidos' => 'Mendez',
                'email' => 'maria@gmail.com',
                'password' => Hash::make('12345678'),
                'estado' => true,
                'rol_id' => 2
            ],
        ]);
        //CLIENTES
        DB::table('users')->insert([
            [
                'ci' => '9653247',
                'nombres' => 'Josue',
                'apellidos' => 'Camacho',
                'email' => 'josue@gmail.com',
                'password' => Hash::make('12345678'),
                'estado' => true,
                'rol_id' => 3
            ],
        ]);

    }
}
