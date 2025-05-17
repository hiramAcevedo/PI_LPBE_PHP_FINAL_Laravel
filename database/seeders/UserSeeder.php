<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        // Crear usuario administrador
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        // Crear usuario hiramwoki
        User::create([
            'name' => 'Hiram Woki',
            'email' => 'hiramwoki@example.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
        ]);

        // Crear usuarios adicionales
        User::create([
            'name' => 'Usuario 1',
            'email' => 'usuario1@example.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
        ]);

        User::create([
            'name' => 'Usuario 2',
            'email' => 'usuario2@example.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
        ]);

        User::create([
            'name' => 'Usuario 3',
            'email' => 'usuario3@example.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
        ]);
    }
}
