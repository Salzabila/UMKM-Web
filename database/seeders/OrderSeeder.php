<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('orders')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $orders = [
            // Orders untuk TRX-2025-10-001
            [
                'no_nota' => 'TRX-2025-10-001',
                'good_id' => 1, // Keripik Singkong Original
                'qty' => 3,
                'price' => 12000,
                'subtotal' => 36000,
            ],
            [
                'no_nota' => 'TRX-2025-10-001',
                'good_id' => 2, // Keripik Pisang Coklat
                'qty' => 3,
                'price' => 13000,
                'subtotal' => 39000,
            ],
            
            // Orders untuk TRX-2025-10-002
            [
                'no_nota' => 'TRX-2025-10-002',
                'good_id' => 4, // Tas Rajut Mini
                'qty' => 2,
                'price' => 55000,
                'subtotal' => 110000,
            ],
            [
                'no_nota' => 'TRX-2025-10-002',
                'good_id' => 5, // Gantungan Kunci Kulit
                'qty' => 1,
                'price' => 10000,
                'subtotal' => 10000,
            ],
            
            // Orders untuk TRX-2025-10-003
            [
                'no_nota' => 'TRX-2025-10-003',
                'good_id' => 4, // Tas Rajut Mini
                'qty' => 1,
                'price' => 55000,
                'subtotal' => 55000,
            ],
            
            // Orders untuk TRX-2025-10-004
            [
                'no_nota' => 'TRX-2025-10-004',
                'good_id' => 9, // Kue Kering Nastar
                'qty' => 3,
                'price' => 60000,
                'subtotal' => 180000,
            ],
            
            // Orders untuk TRX-2025-10-005
            [
                'no_nota' => 'TRX-2025-10-005',
                'good_id' => 3, // Sambal Teri Kacang
                'qty' => 2,
                'price' => 20000,
                'subtotal' => 40000,
            ],
            [
                'no_nota' => 'TRX-2025-10-005',
                'good_id' => 8, // Kaos Oblong Anak
                'qty' => 2,
                'price' => 25000,
                'subtotal' => 50000,
            ],
            
            // Orders untuk TRX-2025-10-006
            [
                'no_nota' => 'TRX-2025-10-006',
                'good_id' => 10, // Kue Kering Kastengel
                'qty' => 1,
                'price' => 65000,
                'subtotal' => 65000,
            ],
            
            // Orders untuk TRX-2025-10-007
            [
                'no_nota' => 'TRX-2025-10-007',
                'good_id' => 11, // Sabun Cuci Piring Herbal
                'qty' => 5,
                'price' => 18000,
                'subtotal' => 90000,
            ],
            [
                'no_nota' => 'TRX-2025-10-007',
                'good_id' => 10, // Kue Kering Kastengel
                'qty' => 2,
                'price' => 65000,
                'subtotal' => 130000,
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}
