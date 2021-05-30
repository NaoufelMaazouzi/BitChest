<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On ajoute un user admin en base de données
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@admin.fr',
                'password' => Hash::make('admin'), // crypté le mot de passe ,
            ]
        ]);
    }
}
