<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users');
        DB::table('users')->insert(array(
            0 =>
            array(

                'name' => 'Christian Diaz',
                'email' => 'chrisaban08@gmail.com',
                'password' => Hash::make('Admin2026.#'),
                'rol_id' => 1,
                'empresa_id' => 1,
                'remember_token' => Str::random(10),
                'created_at' => '2026-02-01 09:00:00',
                'updated_at' => '2026-02-01 09:00:00',

            ),
            1 =>
            array(
                'name' => 'Adair Vazquez',
                'email' => 'adairvazquezcr@gmail.com',
                'password' => Hash::make('0010alan'),
                'rol_id' => 1,
                'empresa_id' => 1,
                'remember_token' => Str::random(10),
                'created_at' => '2026-02-01 09:00:00',
                'updated_at' => '2026-02-01 09:00:00',
            )
        ));
    }
}
