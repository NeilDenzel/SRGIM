<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@tiendamilagritos.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Aurea Huamán',
            'email' => 'aurea@tiendamilagritos.com',
            'password' => Hash::make('aurea123'),
            'role' => 'admin',
        ]);

        $categorias = [
            'Abarrotes',
            'Lácteos',
            'Bebidas',
            'Golosinas',
            'Limpieza',
            'Higiene',
            'Conservas',
            'Panadería',
        ];

        foreach ($categorias as $cat) {
            Categoria::create(['nombre' => $cat]);
        }
    }
}
