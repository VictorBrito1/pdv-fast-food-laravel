<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $now = new \DateTime();

        DB::table('products')->insert([
            'name' => 'Pizza',
            'price' => 40,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('products')->insert([
            'name' => 'Parmegiana de carne',
            'price' => 70,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('products')->insert([
            'name' => 'Lasanha de frango',
            'price' => 50,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('products')->insert([
            'name' => 'Hambúrguer',
            'price' => 25,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
