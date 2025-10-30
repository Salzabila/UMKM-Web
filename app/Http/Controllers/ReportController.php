<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Good;
use App\Models\User;
use App\Models\Category;
use App\Models\ReturnBarang;
use App\Models\Restock;
use App\Models\BiayaOperasional;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Get statistics for dashboard
        $totalTransactions = Transaction::count();
        $successfulTransactions = Transaction::where('status', 'LUNAS')->count();
        $totalRevenue = Transaction::where('status', 'LUNAS')->sum('total_harga');
        $totalProducts = Good::count();
        $totalUsers = User::count();
        $totalSuppliers = Category::count();
        $totalReturns = ReturnBarang::count();

        // Menghitung total biaya operasional
        $totalExpenses = BiayaOperasional::sum('nominal');

        // Recent transactions
        $recentTransactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Fetch all categories for the filter dropdowns
        $categories = Category::orderBy('nama', 'asc')->get();

        return view('dashboard.reports.index', [
            'totalTransactions' => $totalTransactions,
            'successfulTransactions' => $successfulTransactions,
            'totalRevenue' => $totalRevenue,
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalSuppliers' => $totalSuppliers,
            'totalReturns' => $totalReturns,
            'totalExpenses' => $totalExpenses,
            'recentTransactions' => $recentTransactions,
            'categories' => $categories,
            'active' => 'rekap'
        ]);
    }

    public function cetakTransaksi(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $startDate = Carbon::parse($request->tgl_awal)->startOfDay();
        $endDate = Carbon::parse($request->tgl_akhir)->endOfDay();
        $categoryId = $request->input('category_id');

        $query = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->with(['orders.good.category']);

        if ($categoryId) {
            $query->whereHas('orders.good.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $paymentMethodTotals = [];
        $grandTotal = 0;

        foreach ($transactions as $transaction) {
            $totalQtyBarang = 0;
            $namaBarangList = [];
            $barcodeList = [];
            $categoryListForTransaction = [];
            $transactionRevenue = 0; 

            foreach ($transaction->orders as $order) {
                $totalQtyBarang += $order->qty;
                if ($order->good) {
                    $namaBarangList[] = $order->good->nama;
                    $barcodeList[] = $order->good->barcode;
                    if ($order->good->category) {
                        $categoryListForTransaction[] = $order->good->category->nama;
                    }
                    $transactionRevenue += ($order->qty * $order->good->harga);
                }
            }
            $transaction->total_qty_barang = $totalQtyBarang;
            $transaction->nama_barang_list = implode(', ', array_unique($namaBarangList));
            $transaction->barcode_list = implode(', ', array_unique($barcodeList));
            $transaction->category_list = implode(', ', array_unique($categoryListForTransaction));

            $method = $transaction->metode_pembayaran ?? 'Lain-lain';
            if (!isset($paymentMethodTotals[$method])) {
                $paymentMethodTotals[$method] = 0;
            }
            // Gunakan total harga dari order untuk paymentMethodTotals juga
            $paymentMethodTotals[$method] += $transactionRevenue;

            // Hanya tambahkan ke grandTotal jika status transaksi adalah 'LUNAS'
            if ($transaction->status == 'LUNAS') {
                $grandTotal += $transactionRevenue; 
            }
        }

        $data = [
            'transactions' => $transactions,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'grandTotal' => $grandTotal,
            'paymentMethodTotals' => $paymentMethodTotals,
            'generated_at' => now()
        ];

        $pdf = Pdf::loadView('transactions_pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $filename = 'Laporan_Transaksi_' . $startDate->format('d-m-Y') . 'sampai' . $endDate->format('d-m-Y') . '.pdf';
        return $pdf->download($filename);
    }

    public function cetakBarang(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'nullable|date',
            'tgl_akhir' => 'nullable|date|after_or_equal:tgl_awal',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $startDate = $request->input('tgl_awal');
        $endDate = $request->input('tgl_akhir');
        $categoryId = $request->input('category_id');

        $goods = Good::with('category')
            ->when($startDate, fn($q) => $q->whereDate('tgl_masuk', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('tgl_masuk', '<=', $endDate))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->orderBy('nama', 'asc')
            ->get();

        $data = [
            'goods' => $goods,
            'generated_at' => now(),
            'start_date' => $startDate ? Carbon::parse($startDate) : null,
            'end_date' => $endDate ? Carbon::parse($endDate) : null,
        ];

        $pdf = Pdf::loadView('good_pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        $filename = 'Laporan_Data_Barang_' . now()->format('d-m-Y_H-i-s') . '.pdf';
        return $pdf->download($filename);
    }

    public function cetakRestockReturn(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $startDate = Carbon::parse($request->tgl_awal)->startOfDay();
        $endDate = Carbon::parse($request->tgl_akhir)->endOfDay();
        $categoryId = $request->input('category_id');

        $returns = ReturnBarang::with(['good.category'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($categoryId, fn($q) => $q->whereHas('good.category', fn($sub) => $sub->where('id', $categoryId)))
            ->get()->map(function ($item) {
                $item->type = 'Return';
                $item->quantity = $item->qty_return;
                $item->value = ($item->qty_return ?? 0) * ($item->good->harga ?? 0);
                return $item;
            });

        $restocks = Restock::with(['good.category'])
            ->whereBetween('tgl_restock', [$startDate, $endDate])
            ->when($categoryId, fn($q) => $q->whereHas('good.category', fn($sub) => $sub->where('id', $categoryId)))
            ->get()->map(function ($item) {
                $item->type = 'Restock';
                $item->quantity = $item->qty_restock;
                $item->value = ($item->qty_restock ?? 0) * ($item->good->harga_asli ?? $item->good->harga ?? 0);
                return $item;
            });

        $combinedData = $returns->merge($restocks)->sortBy('created_at');

        $totalReturns = $returns->count();
        $totalRestocks = $restocks->count();
        $totalNilaiReturn = $returns->sum('value');
        $totalNilaiRestock = $restocks->sum('value');

        $data = [
            'combinedData' => $combinedData,
            'tgl_awal' => $startDate,
            'tgl_akhir' => $endDate,
            'total_returns' => $totalReturns,
            'total_restocks' => $totalRestocks,
            'total_nilai_return' => $totalNilaiReturn,
            'total_nilai_restock' => $totalNilaiRestock,
            'generated_at' => now()
        ];

        $pdf = Pdf::loadView('restock_return_pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        $filename = 'Laporan_Restock_Return_' . $startDate->format('d-m-Y') . 'sampai' . $endDate->format('d-m-Y') . '.pdf';
        return $pdf->download($filename);
    }

    public function cetakBiayaOperasional(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal'
        ]);

        $startDate = Carbon::parse($request->tgl_awal)->startOfDay();
        $endDate = Carbon::parse($request->tgl_akhir)->endOfDay();

        $biayaOperasional = BiayaOperasional::whereBetween('tanggal', [$startDate, $endDate])
                            ->orderBy('tanggal', 'asc')
                            ->get();

        $totalBiaya = $biayaOperasional->sum('nominal');

        $data = [
            'biayaOperasional' => $biayaOperasional,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalBiaya' => $totalBiaya,
            'generated_at' => now()
        ];

        $pdf = Pdf::loadView('biaya_operasional_pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        $filename = 'Laporan_Biaya_Operasional_' . $startDate->format('d-m-Y') . 'sampai' . $endDate->format('d-m-Y') . '.pdf';
        return $pdf->download($filename);
    }

    public function cetakLaporanKeuangan(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal'
        ]);

        $startDate = Carbon::parse($request->tgl_awal)->startOfDay();
        $endDate = Carbon::parse($request->tgl_akhir)->endOfDay();

        $totalDebit = 0;
        $totalCredit = 0;

        // 1. Get Income (Credit) from Transactions, grouped by Category and Date
        $incomeData = Transaction::whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.status', 'LUNAS')
            ->join('orders', 'transactions.no_nota', '=', 'orders.no_nota')
            ->join('goods', 'orders.good_id', '=', 'goods.id')
            ->leftJoin('categories', 'goods.category_id', '=', 'categories.id')
            ->selectRaw('DATE(transactions.created_at) as transaction_date,
                         COALESCE(categories.nama, "Tanpa Kategori") as category_name,
                         SUM(orders.qty * goods.harga) as total_revenue_per_category')
            ->groupBy('transaction_date', 'category_name')
            ->orderBy('transaction_date', 'asc')
            ->get()
            ->map(function ($item) use (&$totalCredit) {
                $totalCredit += $item->total_revenue_per_category;
                return [
                    'tanggal' => $item->transaction_date,
                    'mitra' => $item->category_name,
                    'status' => 'CREDIT',
                    'nominal' => $item->total_revenue_per_category,
                    'keterangan' => 'Pendapatan dari ' . $item->category_name
                ];
            });

        // 2. Get Expenses (Debit) from BiayaOperasional
        $expenseData = BiayaOperasional::whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function ($item) use (&$totalDebit) {
                $totalDebit += $item->nominal;
                return [
                    'tanggal' => $item->tanggal->format('Y-m-d'),
                    'mitra' => 'Biaya Operasional',
                    'status' => 'DEBET',
                    'nominal' => $item->nominal,
                    'keterangan' => $item->uraian
                ];
            });

        // Combine and sort all data
        $reportData = $incomeData->merge($expenseData)->sortBy('tanggal')->values();

        $data = [
            'reportData' => $reportData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'saldoAkhir' => $totalCredit - $totalDebit,
            'generated_at' => now()
        ];

        $pdf = Pdf::loadView('laporan_keuangan_pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        $filename = 'Laporan_Keuangan_' . $startDate->format('d-m-Y') . 'sampai' . $endDate->format('d-m-Y') . '.pdf';
        return $pdf->download($filename);
    }
}