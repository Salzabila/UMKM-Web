<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BiayaOperasional;
use Illuminate\Support\Facades\DB;

class BiayaOperasionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('biaya_operasional')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $biayaOperasional = [
            [
                'uraian' => 'Listrik Bulan Oktober',
                'nominal' => 500000,
                'tanggal' => '2025-10-05',
                'qty' => 1,
            ],
            [
                'uraian' => 'Gaji Karyawan Kasir',
                'nominal' => 3000000,
                'tanggal' => '2025-10-01',
                'qty' => 1,
            ],
            [
                'uraian' => 'Plastik Kemasan',
                'nominal' => 50000,
                'tanggal' => '2025-10-10',
                'qty' => 5,
            ],
            [
                'uraian' => 'Biaya Transportasi Pengiriman',
                'nominal' => 150000,
                'tanggal' => '2025-10-12',
                'qty' => 3,
            ],
            [
                'uraian' => 'Perawatan Peralatan Toko',
                'nominal' => 200000,
                'tanggal' => '2025-10-15',
                'qty' => 1,
            ],
            [
                'uraian' => 'Internet & Telpon',
                'nominal' => 300000,
                'tanggal' => '2025-10-05',
                'qty' => 1,
            ],
            [
                'uraian' => 'Label Harga & Stiker',
                'nominal' => 75000,
                'tanggal' => '2025-10-18',
                'qty' => 2,
            ],
            [
                'uraian' => 'Biaya Kebersihan',
                'nominal' => 100000,
                'tanggal' => '2025-10-20',
                'qty' => 1,
            ],
            [
                'uraian' => 'Alat Tulis Kantor',
                'nominal' => 120000,
                'tanggal' => '2025-10-22',
                'qty' => 1,
            ],
            [
                'uraian' => 'Sewa Tempat Usaha',
                'nominal' => 2000000,
                'tanggal' => '2025-10-01',
                'qty' => 1,
            ],
        ];

        foreach ($biayaOperasional as $biaya) {
            BiayaOperasional::create($biaya);
        }
    }
}
