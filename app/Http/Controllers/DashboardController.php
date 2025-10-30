<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Good;
use App\Models\Transaction;
use App\Models\Category; 
use App\Models\User;
use App\Models\BiayaOperasional;
use App\Models\Order; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // --- 1. PENGATURAN RENTANG TANGGAL ---
        $range = $request->get('range', 'all_time');
        list($startDate, $endDate, $rangeTitle) = $this->parseDateRange($range);
        list($prevStartDate, $prevEndDate, $prevRangeTitle) = $this->parseDateRange($range, true);

        // --- 2. DATA UNTUK KARTU STATISTIK UTAMA ---
        $totalRevenue = Transaction::where('status', 'LUNAS')->whereBetween('created_at', [$startDate, $endDate])->sum('total_harga');
        $totalTransactions = Transaction::where('status', 'LUNAS')->whereBetween('created_at', [$startDate, $endDate])->count();
        $totalProfit = $this->calculateProfit($startDate, $endDate);
        $totalExpenses = BiayaOperasional::whereBetween('created_at', [$startDate, $endDate])->sum('nominal');
        $netProfit = $totalProfit - $totalExpenses;

        $prevTotalRevenue = Transaction::where('status', 'LUNAS')->whereBetween('created_at', [$prevStartDate, $prevEndDate])->sum('total_harga');
        $prevTotalProfit = $this->calculateProfit($prevStartDate, $prevEndDate);
        $prevTotalTransactions = Transaction::where('status', 'LUNAS')->whereBetween('created_at', [$prevStartDate, $prevEndDate])->count();

        $revenueComparison = $this->calculatePercentageChange($totalRevenue, $prevTotalRevenue);
        $profitComparison = $this->calculatePercentageChange($totalProfit, $prevTotalProfit);
        $transactionComparison = $this->calculatePercentageChange($totalTransactions, $prevTotalTransactions);

        // --- 3. DATA UNTUK GRAFIK UTAMA (PENDAPATAN & LABA) ---
        $chartData = $this->getChartData($startDate, $endDate, $range);

        // --- 4. DATA UNTUK KARTU INFORMASI TAMBAHAN ---
        $topSellingProducts = Order::join('transactions', 'orders.no_nota', '=', 'transactions.no_nota')
            ->join('goods', 'orders.good_id', '=', 'goods.id')
            ->where('transactions.status', 'LUNAS')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->select('goods.nama', DB::raw('SUM(orders.qty) as total_terjual'))
            ->groupBy('goods.nama')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        // [BARU] Siapkan data untuk grafik Produk Terlaris
        $topProductsLabels = $topSellingProducts->pluck('nama');
        $topProductsSeries = $topSellingProducts->pluck('total_terjual');

        $topSellingMitra = Order::join('transactions', 'orders.no_nota', '=', 'transactions.no_nota')
            ->join('goods', 'orders.good_id', '=', 'goods.id')
            ->join('categories', 'goods.category_id', '=', 'categories.id')
            ->where('transactions.status', 'LUNAS')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->select('categories.nama', DB::raw('SUM(orders.qty) as total_terjual'))
            ->groupBy('categories.nama')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        // [BARU] Siapkan data untuk grafik Mitra Terlaris
        $topMitraLabels = $topSellingMitra->pluck('nama');
        $topMitraSeries = $topSellingMitra->pluck('total_terjual');

        // --- 5. DATA BARANG KRITIS (TIDAK TERPENGARUH FILTER TANGGAL) ---
        $lowStockThreshold = 5;
        $lowStockItems = Good::where('stok', '<=', $lowStockThreshold)->orderBy('stok', 'asc')->limit(5)->get();
        $lowStockCount = Good::where('stok', '<=', $lowStockThreshold)->count();
        $veryLowStockCount = Good::whereBetween('stok', [1, 2])->count();
        $lowStockMidCount = Good::whereBetween('stok', [3, $lowStockThreshold])->count();
        $normalStockCount = Good::where('stok', '>', $lowStockThreshold)->count();

        $expiringSoonThresholdDays = 30;
        $now = Carbon::now();
        $expiringSoonItems = Good::whereNotNull('expired_date')->whereBetween('expired_date', [$now, $now->copy()->addDays($expiringSoonThresholdDays)])->select('*', DB::raw('DATEDIFF(expired_date, NOW()) as days_remaining'))->orderBy('expired_date', 'asc')->limit(5)->get();
        $expiringSoonCount = Good::whereNotNull('expired_date')->whereBetween('expired_date', [$now, $now->copy()->addDays($expiringSoonThresholdDays)])->count();
        $expiredVerySoonCount = Good::whereNotNull('expired_date')->whereBetween('expired_date', [$now, $now->copy()->addDays(6)])->count();
        $expiredSoonCount = Good::whereNotNull('expired_date')->whereBetween('expired_date', [$now->copy()->addDays(7), $now->copy()->addDays(30)])->count();
        $expiredSafeCount = Good::whereNotNull('expired_date')->where('expired_date', '>', $now->copy()->addDays(30))->count();
        $noExpiredDateCount = Good::whereNull('expired_date')->count();

        // --- 6. UCAPAN SELAMAT ---
        $hour = Carbon::now()->hour;
        $greeting = "Selamat Datang";
        if ($hour < 11) $greeting = "Selamat Pagi";
        elseif ($hour < 15) $greeting = "Selamat Siang";
        elseif ($hour < 19) $greeting = "Selamat Sore";
        else $greeting = "Selamat Malam";

        return view('dashboard.index', [
            'active' => 'dashboard',
            'greeting' => $greeting,
            'range' => $range,
            'rangeTitle' => $rangeTitle,
            'totalRevenue' => $totalRevenue,
            'totalProfit' => $totalProfit,
            'totalTransactions' => $totalTransactions,
            'netProfit' => $netProfit,
            'totalExpenses' => $totalExpenses, 
            'revenueComparison' => $revenueComparison,
            'profitComparison' => $profitComparison,
            'transactionComparison' => $transactionComparison,
            'mainChartLabels' => json_encode($chartData['labels']),
            'mainChartRevenue' => json_encode($chartData['revenues']),
            'mainChartProfit' => json_encode($chartData['profits']),

            // Mengirim data untuk grafik, bukan koleksi
            'topProductsLabels' => json_encode($topProductsLabels),
            'topProductsSeries' => json_encode($topProductsSeries),
            'topMitraLabels' => json_encode($topMitraLabels),
            'topMitraSeries' => json_encode($topMitraSeries),

            'lowStockItems' => $lowStockItems,
            'lowStockCount' => $lowStockCount,
            'lowStockChart_series' => json_encode([$veryLowStockCount, $lowStockMidCount, $normalStockCount]),
            'lowStockChart_labels' => json_encode(['Sangat Menipis (1-2)', "Menipis (3-{$lowStockThreshold})", "Stok Cukup (>{$lowStockThreshold})"]),
            'expiringSoonItems' => $expiringSoonItems,
            'expiringSoonCount' => $expiringSoonCount,
            'expiringSoonChart_series' => json_encode([$expiredVerySoonCount, $expiredSoonCount, $expiredSafeCount, $noExpiredDateCount]),
            'expiringSoonChart_labels' => json_encode(['Expired < 7 Hari', 'Expired 7-30 Hari', 'Expired > 30 Hari', 'Tanpa Tgl Expired']),
        ]);
    }

    private function calculateProfit($startDate, $endDate)
    {
        return Order::join('transactions', 'orders.no_nota', '=', 'transactions.no_nota')
            ->join('goods', 'orders.good_id', '=', 'goods.id')
            ->where('transactions.status', 'LUNAS')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->sum(DB::raw('orders.qty * (orders.price - goods.harga_asli)'));
    }

    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $previous) / $previous) * 100;
    }

    private function getChartData($startDate, $endDate, $range)
    {
        $labels = [];
        $revenues = [];
        $profits = [];
        $currentDate = $startDate->copy();

        if ($range === 'all_time' || $startDate->diffInDays($endDate) > 60) {
            $queryData = Order::join('transactions', 'orders.no_nota', '=', 'transactions.no_nota')
                ->join('goods', 'orders.good_id', '=', 'goods.id')
                ->where('transactions.status', 'LUNAS')
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->select(
                    DB::raw("DATE_FORMAT(transactions.created_at, '%b %Y') as month"),
                    DB::raw('SUM(orders.qty * orders.price) as total_revenue'),
                    DB::raw('SUM(orders.qty * (orders.price - goods.harga_asli)) as total_profit')
                )
                ->groupBy(DB::raw("YEAR(transactions.created_at)"), DB::raw("MONTH(transactions.created_at)"), 'month')
                ->orderByRaw('MIN(transactions.created_at)')
                ->get();

            foreach ($queryData as $data) {
                $labels[] = $data->month;
                $revenues[] = $data->total_revenue;
                $profits[] = $data->total_profit;
            }
        } else {
            while ($currentDate <= $endDate) {
                $labels[] = $currentDate->format('d M');
                $revenues[] = Transaction::where('status', 'LUNAS')->whereDate('created_at', $currentDate)->sum('total_harga');
                $profits[] = $this->calculateProfit($currentDate, $currentDate->copy()->endOfDay());
                $currentDate->addDay();
            }
        }
        return ['labels' => $labels, 'revenues' => $revenues, 'profits' => $profits];
    }

    private function parseDateRange($range, $getPrevious = false)
    {
        $now = Carbon::now();
        $title = '';

        if ($range === 'today') {
            $startDate = $now->copy()->startOfDay();
            $endDate = $now->copy()->endOfDay();
            if ($getPrevious) { $startDate->subDay(); $endDate->subDay(); }
            $title = 'Hari Ini';
        } elseif ($range === '7_days') {
            $startDate = $now->copy()->subDays(6)->startOfDay();
            $endDate = $now->copy()->endOfDay();
            if ($getPrevious) { $startDate->subDays(7); $endDate->subDays(7); }
            $title = '7 Hari Terakhir';
        } elseif ($range === '30_days') {
            $startDate = $now->copy()->subDays(29)->startOfDay();
            $endDate = $now->copy()->endOfDay();
            if ($getPrevious) { $startDate->subDays(30); $endDate->subDays(30); }
            $title = '30 Hari Terakhir';
        } elseif ($range === 'this_month') {
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now->copy()->endOfMonth();
            if ($getPrevious) { $startDate->subMonth(); $endDate = $startDate->copy()->endOfMonth(); }
            $title = 'Bulan Ini';
        } else {
            $range = 'all_time';
            $startDate = Carbon::create(2000, 1, 1);
            $endDate = Carbon::now();
            if ($getPrevious) {
                $startDate = Carbon::create(1999, 1, 1);
                $endDate = Carbon::create(1999, 12, 31);
            }
            $title = 'Semua Waktu';
        }
        return [$startDate, $endDate, $title];
    }
}
