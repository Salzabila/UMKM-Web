<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Good;
use Illuminate\Support\Facades\DB;

class GoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('goods')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $goods = [
            // UMKM Sari Rasa (category_id: 1) - Makanan
            [
                'category_id' => 1,
                'nama' => 'Keripik Singkong Original',
                'type' => 'makanan',
                'barcode' => '8991234567001',
                'tgl_masuk' => '2025-10-01',
                'expired_date' => '2026-04-01',
                'stok' => 50,
                'harga_asli' => 12000,
                'harga' => 12000,
                'min_qty_grosir' => 10,
                'harga_grosir' => 10000,
                'is_grosir_active' => true,
                'is_tebus_murah_active' => false,
                'markup_percentage' => 50,
            ],
            [
                'category_id' => 1,
                'nama' => 'Keripik Pisang Coklat',
                'type' => 'makanan',
                'barcode' => '8991234567002',
                'tgl_masuk' => '2025-10-05',
                'expired_date' => '2026-04-05',
                'stok' => 35,
                'harga_asli' => 13000,
                'harga' => 13000,
                'min_qty_grosir' => 10,
                'harga_grosir' => 11000,
                'is_grosir_active' => true,
                'is_tebus_murah_active' => false,
                'markup_percentage' => 44,
            ],
            [
                'category_id' => 1,
                'nama' => 'Sambal Teri Kacang',
                'type' => 'makanan',
                'barcode' => '8991234567003',
                'tgl_masuk' => '2025-10-10',
                'expired_date' => '2026-01-10',
                'stok' => 25,
                'harga_asli' => 20000,
                'harga' => 20000,
                'min_qty_grosir' => 5,
                'harga_grosir' => 18000,
                'is_grosir_active' => true,
                'is_tebus_murah_active' => false,
                'markup_percentage' => 33,
            ],
            
            // UMKM Cahaya Handycraft (category_id: 2) - Handycraft
            [
                'category_id' => 2,
                'nama' => 'Tas Rajut Mini',
                'type' => 'handycraft',
                'barcode' => '8991234567004',
                'tgl_masuk' => '2025-09-15',
                'expired_date' => null,
                'stok' => 15,
                'harga_asli' => 55000,
                'harga' => 55000,
                'min_qty_grosir' => 3,
                'harga_grosir' => 50000,
                'is_grosir_active' => true,
                'is_tebus_murah_active' => false,
                'markup_percentage' => 57,
            ],
            [
                'category_id' => 2,
                'nama' => 'Gantungan Kunci Kulit',
                'type' => 'handycraft',
                'barcode' => '8991234567005',
                'tgl_masuk' => '2025-09-20',
                'expired_date' => null,
                'stok' => 40,
                'harga_asli' => 10000,
                'harga' => 10000,
                'min_qty_grosir' => 20,
                'harga_grosir' => 8000,
                'is_grosir_active' => true,
                'is_tebus_murah_active' => false,
                'markup_percentage' => 100,
            ],
            [
                'category_id' => 2,
                'nama' => 'Tempat Tisu Batik',
                'type' => 'handycraft',
                'barcode' => '8991234567006',
                'tgl_masuk' => '2025-10-01',
                'expired_date' => null,
                'stok' => 20,
                'harga_asli' => 40000,
                'harga' => 40000,
                'min_qty_grosir' => 5,
                'harga_grosir' => 35000,
                'is_grosir_active' => true,
                'is_tebus_murah_active' => false,
                'markup_percentage' => 60,
            ],
            
            // UMKM Bunda Fashion (category_id: 3) - Fashion
            [
                'category_id' => 3,
                'nama' => 'Jilbab Segi Empat Motif',
                'type' => 'fashion',
                'barcode' => '8991234567007',
                'tgl_masuk' => '2025-10-12',
                'expired_date' => null,
                'stok' => 30,
                'harga_asli' => 35000,
                'harga' => 35000,
                'min_qty_grosir' => 5,
                'harga_grosir' => 30000,
                'is_grosir_active' => true,
                'is_tebus_murah_active' => false,
                'markup_percentage' => 75,
            ],
            [
                'category_id' => 3,
                'nama' => 'Kaos Oblong Anak',
                'type' => 'fashion',
                'barcode' => '8991234567008',
                'tgl_masuk' => '2025-10-15',
                'expired_date' => null,
                'stok' => 45,
                'harga_asli' => 25000,
                'harga' => 25000,
                'min_qty_grosir' => 10,
                'harga_grosir' => 22000,
                'is_grosir_active' => true,
                'is_tebus_murah_active' => false,
                'markup_percentage' => 67,
            ],
            
            // UMKM Berkah Snack (category_id: 4) - Makanan
            [
                'category_id' => 4,
                'nama' => 'Kue Kering Nastar',
                'type' => 'makanan',
                'barcode' => '8991234567009',
                'tgl_masuk' => '2025-10-20',
                'expired_date' => '2025-12-20',
                'stok' => 20,
                'harga_asli' => 60000,
                'harga' => 60000,
                'min_qty_grosir' => 3,
                'harga_grosir' => 55000,
                'is_grosir_active' => true,
                'is_tebus_murah_active' => false,
                'markup_percentage' => 50,
            ],
            [
                'category_id' => 4,
                'nama' => 'Kue Kering Kastengel',
                'type' => 'makanan',
                'barcode' => '8991234567010',
                'tgl_masuk' => '2025-10-20',
                'expired_date' => '2025-12-20',
                'stok' => 18,
                'harga_asli' => 65000,
                'harga' => 65000,
                'min_qty_grosir' => 3,
                'harga_grosir' => 60000,
                'is_grosir_active' => true,
                'is_tebus_murah_active' => false,
                'markup_percentage' => 44,
            ],
            
            // UMKM Sejahtera (category_id: 5) - Non Makanan
            [
                'category_id' => 5,
                'nama' => 'Sabun Cuci Piring Herbal',
                'type' => 'non_makanan',
                'barcode' => '8991234567011',
                'tgl_masuk' => '2025-10-05',
                'expired_date' => '2027-10-05',
                'stok' => 60,
                'harga_asli' => 18000,
                'harga' => 18000,
                'min_qty_grosir' => 12,
                'harga_grosir' => 16000,
                'is_grosir_active' => true,
                'is_tebus_murah_active' => false,
                'markup_percentage' => 50,
            ],
            [
                'category_id' => 5,
                'nama' => 'Pewangi Pakaian Alami',
                'type' => 'non_makanan',
                'barcode' => '8991234567012',
                'tgl_masuk' => '2025-10-08',
                'expired_date' => '2027-10-08',
                'stok' => 40,
                'harga_asli' => 22000,
                'harga' => 22000,
                'min_qty_grosir' => 10,
                'harga_grosir' => 20000,
                'is_grosir_active' => true,
                'is_tebus_murah_active' => false,
                'markup_percentage' => 47,
            ],
        ];

        foreach ($goods as $good) {
            Good::create($good);
        }
    }
}
