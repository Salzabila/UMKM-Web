<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            [
                'nama' => 'UMKM Sari Rasa',
                'nomor_penanggung_jawab' => '081234567890',
                'alamat' => 'Jl. Mawar No. 15, Jakarta Selatan',
            ],
            [
                'nama' => 'UMKM Cahaya Handycraft',
                'nomor_penanggung_jawab' => '081234567891',
                'alamat' => 'Jl. Melati No. 22, Bandung',
            ],
            [
                'nama' => 'UMKM Bunda Fashion',
                'nomor_penanggung_jawab' => '081234567892',
                'alamat' => 'Jl. Anggrek No. 8, Surabaya',
            ],
            [
                'nama' => 'UMKM Berkah Snack',
                'nomor_penanggung_jawab' => '081234567893',
                'alamat' => 'Jl. Kenanga No. 12, Yogyakarta',
            ],
            [
                'nama' => 'UMKM Sejahtera',
                'nomor_penanggung_jawab' => '081234567894',
                'alamat' => 'Jl. Flamboyan No. 5, Semarang',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
