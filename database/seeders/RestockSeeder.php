<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Restock;
use Illuminate\Support\Facades\DB;

class RestockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('restocks')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $restocks = [
            // Restock history untuk beberapa barang
            [
                'good_id' => 1,
                'user_id' => 1,
                'qty_restock' => 30,
                'stok_sebelum' => 20,
                'keterangan' => 'Restock awal bulan',
                'tgl_restock' => '2025-10-01',
            ],
            [
                'good_id' => 1,
                'user_id' => 2,
                'qty_restock' => 20,
                'stok_sebelum' => 30,
                'keterangan' => 'Tambahan stok minggu kedua',
                'tgl_restock' => '2025-10-15',
            ],
            [
                'good_id' => 2,
                'user_id' => 1,
                'qty_restock' => 35,
                'stok_sebelum' => 0,
                'keterangan' => 'Stok awal produk baru',
                'tgl_restock' => '2025-10-05',
            ],
            [
                'good_id' => 3,
                'user_id' => 2,
                'qty_restock' => 25,
                'stok_sebelum' => 0,
                'keterangan' => 'Stok awal produk baru',
                'tgl_restock' => '2025-10-10',
            ],
            [
                'good_id' => 4,
                'user_id' => 1,
                'qty_restock' => 15,
                'stok_sebelum' => 0,
                'keterangan' => 'Stok awal produk handycraft',
                'tgl_restock' => '2025-09-15',
            ],
            [
                'good_id' => 5,
                'user_id' => 2,
                'qty_restock' => 40,
                'stok_sebelum' => 0,
                'keterangan' => 'Stok awal gantungan kunci',
                'tgl_restock' => '2025-09-20',
            ],
            [
                'good_id' => 7,
                'user_id' => 1,
                'qty_restock' => 30,
                'stok_sebelum' => 0,
                'keterangan' => 'Stok awal jilbab',
                'tgl_restock' => '2025-10-12',
            ],
            [
                'good_id' => 11,
                'user_id' => 2,
                'qty_restock' => 50,
                'stok_sebelum' => 10,
                'keterangan' => 'Restock sabun cuci piring',
                'tgl_restock' => '2025-10-20',
            ],
        ];

        foreach ($restocks as $restock) {
            Restock::create($restock);
        }
    }
}
