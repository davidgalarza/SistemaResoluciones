<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use App\Configuraciones;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create([
            'nombre' => 'ADMINISTRADOR',
            'descripcion' => 'ADMINISTRADOR'
        ]);

        $abogadoRole = Role::create([
            'nombre' => 'ABOGADO',
            'descripcion' => 'ABOGADO'
        ]);

        $user = User::create([
            'name' => 'Administrador',
            'email' => 'administrador'.'@fisei.com',
            'password' => Hash::make('12345678')
        ]);

        $user->asignarRol($adminRole);

        $user = User::create([
            'name' => 'Abogado',
            'email' => 'abogado'.'@fisei.com',
            'password' => Hash::make('12345678')
        ]);
        


        $user->asignarRol($abogadoRole);

        $conf = Configuraciones::create([
            'key' => 'PERIODO',
            'value' => 'SEPTIEMPRE 2020 - FEBRERRO 2021',
        ]);
        
    }
}
