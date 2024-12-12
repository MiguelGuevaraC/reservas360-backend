<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('people')->insert([
            'id' => 1,
            'typeofDocument' => 'DNI', // El tipo de documento, puedes modificarlo
            'documentNumber' => '12345678', // Número de documento
            'names' => 'Miguel Angel',
            'fatherSurname' => 'Guevara',
            'motherSurname' => 'Cajusol',
            'businessName' => null, // Nombre de la empresa
            'representativePersonDni' => null, // DNI del representante
            'representativePersonName' => null, // Nombre del representante
            'address' => 'Av. Principal 123', // Dirección
            'phone' => '987654321', // Teléfono
            'email' => 'guevaracajusolmiguel@gmail.com', // Correo electrónico
            'origin' => 'Perú', // Origen
            'ocupation' => 'Administrador', // Ocupación

        ]);

        DB::table('users')->insert([
            'id' => 1, // El ID del usuario
            'name' => 'Miguel Guevara', // Nombre del usuario
            'email' => 'guevaracajusolmiguel@gmail.com', // Correo electrónico
            'password' => Hash::make('12345'), // Contraseña hasheada usando Hash::make

            'person_id' => 1, // Relacionar con el ID de la persona
        ]);

    }
}
