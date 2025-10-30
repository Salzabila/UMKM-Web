<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::create([
            'nama' => 'Admin',
            'username' => 'admin',
            'role' => 'ADMIN',
            'password' => 'admin123',
        ]);

        User::create([
            'nama' => 'Manajer',
            'username' => 'manajer',
            'role' => 'MANAJER',
            'password' => 'manajer123',
        ]);

        User::create([
            'nama' => 'Kasir-1',
            'username' => 'kasir',
            'role' => 'KASIR',
            'password' => 'kasir123',
        ]);
    }
}
