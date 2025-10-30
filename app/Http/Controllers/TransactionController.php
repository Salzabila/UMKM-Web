<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Order;
use App\Models\Good;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // [REKOMENDASI] Gunakan fasad Log yang benar

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sortStatus = request('sort_status');

        // Start with base query without ordering
        $transactions = Transaction::query();

        // Search by transaction number
        if(request('search')) {
            $transactions->where('no_nota', 'like', '%' . request('search') . '%');
        }

        // Search by date range
        if(request('start_date')) {
            $transactions->whereDate('created_at', '>=', request('start_date'));
        }

        if(request('end_date')) {
            $transactions->whereDate('created_at', '<=', request('end_date'));
        }

        // Apply sorting based on status sort parameter
        if($sortStatus === 'failed_first') {
            // Show failed/gagal status first, then successful/lunas
            $transactions->orderByRaw("CASE
                WHEN LOWER(TRIM(status)) IN ('gagal', 'belum bayar') THEN 1
                WHEN LOWER(TRIM(status)) = 'lunas' THEN 2
                ELSE 3
            END ASC")
            ->orderBy('created_at', 'desc'); // Secondary sort by date
        } elseif($sortStatus === 'success_first') {
            // Show successful/lunas status first, then failed/gagal
            $transactions->orderByRaw("CASE
                WHEN LOWER(TRIM(status)) = 'lunas' THEN 1
                WHEN LOWER(TRIM(status)) IN ('gagal', 'belum bayar') THEN 2
                ELSE 3
            END ASC")
            ->orderBy('created_at', 'desc'); // Secondary sort by date
        } else {
            // Default: latest transactions first (reset state)
            $transactions->latest();
        }

        return view('dashboard.transactions.index', [
            'active' => 'transactions',
            'transactions' => $transactions->paginate(10)->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        $transaction->load('user');

        if (!$transaction) {
            return redirect('/dashboard/transactions')->with('error', 'Transaksi tidak ditemukan.');
        }

        if (!$transaction->user) {
            // If user doesn't exist, we can still show the form but with a warning
            session()->flash('warning', 'User yang terkait dengan transaksi ini tidak ditemukan.');
        }

        return view('dashboard.transactions.edit', [
            'active' => 'transaction',
            'transaction' => $transaction,
            'users' => User::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransactionRequest  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:LUNAS,BELUM BAYAR,gagal',
            'bayar' => 'required|numeric|min:0',
            // 'kembalian' => 'required|numeric', // Dihapus karena akan dihitung ulang
        ]);

        // [PERBAIKAN] Hitung ulang kembalian dengan logika yang benar
        // Rumus: Uang Bayar - Total Harga
        $validatedData['kembalian'] = $validatedData['bayar'] - $transaction->total_harga;

        // Simpan status lama sebelum update
        $oldStatus = strtolower(trim($transaction->status));
        $newStatus = strtolower(trim($validatedData['status']));

        // Log untuk debugging
        Log::info("Transaction Update - Old Status: {$oldStatus}, New Status: {$newStatus}");

        // Update transaction
        $transaction->update($validatedData);

        // Handle stock changes based on status change
        $this->handleStockUpdate($transaction->no_nota, $oldStatus, $newStatus);

        return redirect('/dashboard/transactions')->with('success', 'Transaksi berhasil diperbarui dan stok barang telah disesuaikan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        // If transaction was paid (LUNAS), restore stock before deleting
        if (strtolower(trim($transaction->status)) === 'lunas') {
            $this->restoreStock($transaction->no_nota);
        }

        // Delete related orders first
        Order::where('no_nota', $transaction->no_nota)->delete();

        // Delete transaction
        Transaction::destroy($transaction->id);

        return redirect('/dashboard/transactions')->with('success', 'Transaksi telah dihapus dan stok barang telah dikembalikan.');
    }

    /**
     * [BARU] Menghapus beberapa transaksi sekaligus.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:transactions,id',
        ]);

        $selectedIds = $request->input('selected_ids');

        if (empty($selectedIds)) {
            return redirect('/dashboard/transactions')->with('error', 'Tidak ada transaksi yang dipilih untuk dihapus.');
        }

        $transactions = Transaction::whereIn('id', $selectedIds)->get();
        $noNotas = [];

        foreach ($transactions as $transaction) {
            // Kumpulkan nomor nota
            $noNotas[] = $transaction->no_nota;
            // Jika statusnya LUNAS, kembalikan stok
            if (strtolower(trim($transaction->status)) === 'lunas') {
                $this->restoreStock($transaction->no_nota);
            }
        }

        // Hapus semua pesanan (orders) yang terkait dengan nomor nota yang dipilih
        if (!empty($noNotas)) {
            Order::whereIn('no_nota', $noNotas)->delete();
        }

        // Hapus semua transaksi yang dipilih
        $deletedCount = Transaction::destroy($selectedIds);

        if ($deletedCount > 0) {
            return redirect('/dashboard/transactions')->with('success', $deletedCount . ' transaksi berhasil dihapus dan stok (jika ada) telah dikembalikan.');
        }

        return redirect('/dashboard/transactions')->with('error', 'Gagal menghapus transaksi yang dipilih.');
    }

    /**
     * Export transactions to PDF
     */
    public function exportpdf()
    {
        $transactions = Transaction::with('user')->get();
        $pdf = PDF::loadview('transactions_pdf', ['transactions' => $transactions]);
        return $pdf->download('laporan-transaksi.pdf');
    }

    /**
     * Handle stock update based on status change
     */
    private function handleStockUpdate($no_nota, $oldStatus, $newStatus)
    {
        Log::info("HandleStockUpdate called - No Nota: {$no_nota}, Old: {$oldStatus}, New: {$newStatus}");

        $orders = Order::where('no_nota', $no_nota)->get();

        if ($orders->isEmpty()) {
            Log::info("No orders found for no_nota: {$no_nota}");
            return;
        }

        // Jika berubah dari status non-lunas ke lunas (stok harus berkurang)
        if ($oldStatus !== 'lunas' && $newStatus === 'lunas') {
            Log::info("Reducing stock - changing from {$oldStatus} to {$newStatus}");

            foreach ($orders as $order) {
                $good = Good::find($order->good_id);
                if ($good) {
                    $oldStock = $good->stok;

                    if ($good->stok >= $order->qty) {
                        // Kurangi stok
                        $newStock = $good->stok - $order->qty;
                        $good->update(['stok' => $newStock]);

                        Log::info("Stock reduced for good ID {$good->id}: {$oldStock} -> {$newStock} (qty: {$order->qty})");
                    } else {
                        Log::warning("Insufficient stock for good ID {$good->id}. Available: {$good->stok}, Required: {$order->qty}");
                    }
                }
            }
        }
        // Jika berubah dari lunas ke non-lunas (stok harus dikembalikan)
        elseif ($oldStatus === 'lunas' && $newStatus !== 'lunas') {
            Log::info("Restoring stock - changing from {$oldStatus} to {$newStatus}");

            foreach ($orders as $order) {
                $good = Good::find($order->good_id);
                if ($good) {
                    $oldStock = $good->stok;

                    // Kembalikan stok
                    $newStock = $good->stok + $order->qty;
                    $good->update(['stok' => $newStock]);

                    Log::info("Stock restored for good ID {$good->id}: {$oldStock} -> {$newStock} (qty: {$order->qty})");
                }
            }
        } else {
            Log::info("No stock change needed - both statuses are similar");
        }
    }

    /**
     * Restore stock when transaction is deleted
     */
    private function restoreStock($no_nota)
    {
        Log::info("Restoring stock for deleted transaction: {$no_nota}");

        $orders = Order::where('no_nota', $no_nota)->get();

        foreach ($orders as $order) {
            $good = Good::find($order->good_id);
            if ($good) {
                $oldStock = $good->stok;
                $newStock = $good->stok + $order->qty;
                $good->update(['stok' => $newStock]);

                Log::info("Stock restored for deleted transaction - Good ID {$good->id}: {$oldStock} -> {$newStock}");
            }
        }
    }
}
