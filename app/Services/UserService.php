<?php
namespace App\Services;

use App\Models\Person;
use App\Models\User;

class UserService
{

    public function getUserById(int $id): ?User
    {
        return User::find($id);
    }

    public function createUser(array $data): User
    {
        // Verificar si la persona existe; si no, crearla

        $identifier = isset($data['documentNumber']) ? ['documentNumber' => $data['documentNumber']] : ['email' => $data['email'] ?? null];

        $person = Person::firstOrCreate(
            $identifier, // Condición para encontrar una persona existente
            [            // Solo los campos que no se repiten en el 'firstOrCreate'

                'names'     => $data['names'] ?? null,
                'phone'     => $data['phone'] ?? null,
                'email'     => $data['email'] ?? null,
                'ocupation' => $data['ocupation'] ?? null,
                'state'     => "1" ?? null,
            ]
        );

        $name = isset($data['typeofDocument']) && $data['typeofDocument'] === 'DNI'
        ? (isset($data['names']) ? $data['names'] : '') . ' ' .
        (isset($data['father_surname']) ? $data['father_surname'] : '') . '' .
        (isset($data['mother_surname']) ? $data['mother_surname'] : '')
        : (isset($data['businessName']) ? $data['businessName'] : '');

        // Crear y devolver el usuario, asociándolo con la persona encontrada o creada
        return User::create([
            'name'              => $name,
            'password'          => bcrypt($data['password']),
            'email'             => $data['email'],
            'email_verified_at' => now(),

        ]);
    }

}
