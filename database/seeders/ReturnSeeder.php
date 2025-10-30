<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReturnBarang;
use Illuminate\Support\Facades\DB;

class ReturnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('returns')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $returns = [
            [
                'good_id' => 2,
                'user_id' => 2,
                'tgl_return' => '2025-10-18',
                'qty_return' => 5,
                'alasan' => 'Rusak',
                'keterangan' => 'Kemasan rusak saat pengiriman',
            ],
            [
                'good_id' => 9,
                'user_id' => 1,
                'tgl_return' => '2025-10-22',
                'qty_return' => 2,
                'alasan' => 'Kadaluarsa',
                'keterangan' => 'Mendekati tanggal kadaluarsa',
            ],
            [
                'good_id' => 1,
                'user_id' => 2,
                'tgl_return' => '2025-10-25',
                'qty_return' => 3,
                'alasan' => 'Cacat',
                'keterangan' => 'Produk cacat produksi',
            ],
            [
                'good_id' => 6,
                'user_id' => 1,
                'tgl_return' => '2025-10-26',
                'qty_return' => 1,
                'alasan' => 'Salah Kirim',
                'keterangan' => 'Salah warna yang dikirim',
            ],
        ];

        foreach ($returns as $return) {
            ReturnBarang::create($return);
        }
    }
}
