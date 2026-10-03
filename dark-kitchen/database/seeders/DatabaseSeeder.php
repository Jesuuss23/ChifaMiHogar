<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Administrador
        User::updateOrCreate(
            ['email' => 'admin@chifamihogar.com'],
            [
                'name' => 'Admin Chifa Mi Hogar',
                'password' => Hash::make('admin1234'), // Cambia esta contraseña luego
            ]
        );

        // Configuraciones iniciales del negocio
        Setting::updateOrCreate(['key' => 'store_open'], ['value' => '1']); // 1 = abierto, 0 = cerrado
        Setting::updateOrCreate(['key' => 'whatsapp_number'], ['value' => '51987654321']); // Pon tu número aquí (con código de país)
        Setting::updateOrCreate(['key' => 'yape_qr'], ['value' => null]);
    }
}