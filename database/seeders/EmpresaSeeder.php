<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('empresas');
        DB::table('empresas')->insert(array(
            0 =>
            array(
                'nombre_empresa' => 'Zephyrea',
                'created_at' => '2026-02-01 09:00:00',
                'updated_at' => '2026-02-01 09:00:00',
            ),
            1 =>
            array(
                'nombre_empresa' => 'Kentucky Fried Chicken',
                'created_at' => '2026-02-01 09:00:00',
                'updated_at' => '2026-02-01 09:00:00',
            ),
            2 =>
            array(
                'nombre_empresa' => 'McDonalds',
                'created_at' => '2026-02-01 09:00:00',
                'updated_at' => '2026-02-01 09:00:00',
            ),
        ));
    }
}
