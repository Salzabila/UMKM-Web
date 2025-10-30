<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transactions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $transactions = [
            [
                'no_nota' => 'TRX-2025-10-001',
                'tgl_transaksi' => '2025-10-15',
                'user_id' => 3, // Kasir
                'metode_pembayaran' => 'Tunai',
                'status' => 'sukses',
                'total_harga' => 75000,
                'bayar' => 100000,
                'kembalian' => 25000,
            ],
            [
                'no_nota' => 'TRX-2025-10-002',
                'tgl_transaksi' => '2025-10-16',
                'user_id' => 3,
                'metode_pembayaran' => 'QRIS',
                'status' => 'sukses',
                'total_harga' => 120000,
                'bayar' => 120000,
                'kembalian' => 0,
            ],
            [
                'no_nota' => 'TRX-2025-10-003',
                'tgl_transaksi' => '2025-10-17',
                'user_id' => 3,
                'metode_pembayaran' => 'Transfer',
                'status' => 'sukses',
                'total_harga' => 55000,
                'bayar' => 55000,
                'kembalian' => 0,
            ],
            [
                'no_nota' => 'TRX-2025-10-004',
                'tgl_transaksi' => '2025-10-18',
                'user_id' => 3,
                'metode_pembayaran' => 'Tunai',
                'status' => 'sukses',
                'total_harga' => 180000,
                'bayar' => 200000,
                'kembalian' => 20000,
            ],
            [
                'no_nota' => 'TRX-2025-10-005',
                'tgl_transaksi' => '2025-10-19',
                'user_id' => 3,
                'metode_pembayaran' => 'QRIS',
                'status' => 'sukses',
                'total_harga' => 90000,
                'bayar' => 90000,
                'kembalian' => 0,
            ],
            [
                'no_nota' => 'TRX-2025-10-006',
                'tgl_transaksi' => '2025-10-20',
                'user_id' => 3,
                'metode_pembayaran' => 'Tunai',
                'status' => 'sukses',
                'total_harga' => 65000,
                'bayar' => 70000,
                'kembalian' => 5000,
            ],
            [
                'no_nota' => 'TRX-2025-10-007',
                'tgl_transaksi' => '2025-10-21',
                'user_id' => 3,
                'metode_pembayaran' => 'Transfer',
                'status' => 'sukses',
                'total_harga' => 220000,
                'bayar' => 220000,
                'kembalian' => 0,
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}
