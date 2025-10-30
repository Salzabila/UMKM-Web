<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user')->orderBy('created_at', 'desc');

        // Filter by date range
        if ($request->has('start_date')) {
            $query->whereDate('tgl_transaksi', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('tgl_transaksi', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by no_nota
        if ($request->has('search')) {
            $query->where('no_nota', 'like', '%' . $request->search . '%');
        }

        // Pagination
        $perPage = $request->input('per_page', 20);
        $transactions = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $transactions->items(),
            'current_page' => $transactions->currentPage(),
            'last_page' => $transactions->lastPage(),
            'per_page' => $transactions->perPage(),
            'total' => $transactions->total(),
        ]);
    }

    public function show($id)
    {
        $transaction = Transaction::with('user')->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    public function today(Request $request)
    {
        $today = Carbon::today();
        
        $transactions = Transaction::with('user')
            ->whereDate('tgl_transaksi', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.good_id' => 'required|exists:goods,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            $items = $request->items;
            $totalHarga = 0;
            
            // Validate stock and calculate total
            foreach ($items as $item) {
                $product = Good::find($item['good_id']);
                
                if ($product->stok < $item['qty']) {
                    throw new \Exception("Stok {$product->nama} tidak mencukupi");
                }
                
                $totalHarga += $item['harga'] * $item['qty'];
            }

            // Validate payment
            if ($request->bayar < $totalHarga) {
                throw new \Exception("Jumlah bayar tidak mencukupi");
            }

            $kembalian = $request->bayar - $totalHarga;

            // Generate nomor nota
            $lastTransaction = Transaction::whereDate('created_at', Carbon::today())
                ->orderBy('id', 'desc')
                ->first();
            
            $sequence = 1;
            if ($lastTransaction) {
                $lastNo = explode('-', $lastTransaction->no_nota);
                $sequence = intval($lastNo[2]) + 1;
            }
            
            $noNota = 'TRX-' . date('Ymd') . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // Create transaction
            $transaction = Transaction::create([
                'no_nota' => $noNota,
                'user_id' => $request->user()->id,
                'tgl_transaksi' => now(),
                'status' => 'paid',
                'total_harga' => $totalHarga,
                'bayar' => $request->bayar,
                'kembalian' => $kembalian,
            ]);

            // Update stock and create transaction details (if you have detail table)
            foreach ($items as $item) {
                $product = Good::find($item['good_id']);
                $product->decrement('stok', $item['qty']);
                
                // If you have transaction_details table, insert here
                // TransactionDetail::create([...]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'data' => $transaction->load('user')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function printReceipt($id)
    {
        $transaction = Transaction::with('user')->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        // Return data for mobile to format receipt
        return response()->json([
            'success' => true,
            'data' => [
                'transaction' => $transaction,
                'shop_name' => 'UMKM POS',
                'shop_address' => 'Jl. Contoh No. 123',
                'shop_phone' => '08123456789',
            ]
        ]);
    }
}
