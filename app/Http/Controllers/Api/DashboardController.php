<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Good;
use App\Models\BiayaOperasional;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $today = Carbon::today();
        
        // Transaksi hari ini
        $todayTransactions = Transaction::whereDate('tgl_transaksi', $today)
            ->where('status', 'paid')
            ->get();

        $totalPendapatanHariIni = $todayTransactions->sum('total_harga');
        $totalTransaksiHariIni = $todayTransactions->count();

        // Transaksi bulan ini
        $monthlyTransactions = Transaction::whereMonth('tgl_transaksi', $today->month)
            ->whereYear('tgl_transaksi', $today->year)
            ->where('status', 'paid')
            ->get();

        $totalPendapatanBulanIni = $monthlyTransactions->sum('total_harga');

        // Biaya operasional bulan ini
        $biayaOperasional = BiayaOperasional::whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->sum('nominal');

        // Produk dengan stok menipis (< 10)
        $lowStockProducts = Good::where('stok', '<=', 10)->count();

        // Produk akan expired (< 7 hari)
        $willExpireProducts = Good::whereNotNull('expired_date')
            ->whereDate('expired_date', '<=', $today->copy()->addDays(7))
            ->whereDate('expired_date', '>=', $today)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'today' => [
                    'total_pendapatan' => $totalPendapatanHariIni,
                    'total_transaksi' => $totalTransaksiHariIni,
                ],
                'monthly' => [
                    'total_pendapatan' => $totalPendapatanBulanIni,
                    'biaya_operasional' => $biayaOperasional,
                    'laba_bersih' => $totalPendapatanBulanIni - $biayaOperasional,
                ],
                'alerts' => [
                    'low_stock_count' => $lowStockProducts,
                    'will_expire_count' => $willExpireProducts,
                ]
            ]
        ]);
    }

    public function salesChart(Request $request)
    {
        $days = $request->input('days', 7);
        $endDate = Carbon::today();
        $startDate = $endDate->copy()->subDays($days);

        $sales = Transaction::selectRaw('DATE(tgl_transaksi) as date, SUM(total_harga) as total')
            ->whereBetween('tgl_transaksi', [$startDate, $endDate])
            ->where('status', 'paid')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sales
        ]);
    }

    public function topProducts(Request $request)
    {
        $limit = $request->input('limit', 10);
        
        // Query untuk produk terlaris
        $topProducts = DB::table('goods')
            ->select('goods.id', 'goods.nama', 'goods.harga_jual', DB::raw('COUNT(transactions.id) as total_sold'))
            ->join('transactions', 'transactions.good_id', '=', 'goods.id')
            ->where('transactions.status', 'paid')
            ->groupBy('goods.id', 'goods.nama', 'goods.harga_jual')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $topProducts
        ]);
    }
}

