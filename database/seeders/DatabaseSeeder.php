<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call([
           UserSeeder::class,
           CategorySeeder::class,
           GoodSeeder::class,
           RestockSeeder::class,
           TransactionSeeder::class,
           OrderSeeder::class,
           ReturnSeeder::class,
           BiayaOperasionalSeeder::class,
       ]);
    }
}
