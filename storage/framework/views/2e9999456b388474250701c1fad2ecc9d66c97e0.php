<?php $__env->startSection('container'); ?>
<style>
    body {
        background-color: #f8fafc;
        color: #1d273b;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }

    .card {
        border: 1px solid #e6e7e9;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
        border-radius: 8px;
        margin-bottom: 1.5rem;
        background-color: #fff;
        height: 100%;
        animation: fadeInUp 0.4s ease-in-out;
    }
    .card-body { padding: 1.5rem; }
    .card-header {
        background: none;
        border-bottom: 1px solid #e6e7e9;
        padding: 1rem 1.5rem;
    }
    .card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1d273b;
        margin: 0;
    }

    .page-header {
        display: flex;
        flex-wrap: wrap; 
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        gap: 1rem; 
    }
    .page-title {
        font-size: 1.5rem; 
        font-weight: 600;
    }

    .date-filter .btn {
        font-weight: 500;
    }
    .date-filter .btn.active {
        background-color: #206bc4;
        color: white;
        font-weight: 600;
    }
    .date-filter-wrapper {
        overflow-x: auto;
        padding-bottom: 10px; 
    }
    .date-filter {
        flex-wrap: nowrap; 
    }

    .stat-card .stat-label {
        color: #6c757d;
        font-size: 0.875rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 600;
        line-height: 1.2;
        color: #1d273b;
        margin-top: 0.25rem;
    }
    .stat-card .stat-comparison {
        font-size: 0.8rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .stat-card .stat-comparison.positive { color: #2fb344; }
    .stat-card .stat-comparison.negative { color: #d63939; }

    .card-alert-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .card-alert-title {
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .list-group-flush .list-group-item {
        padding: 0.85rem 0.25rem;
    }
    .list-group-item .item-details .subtext {
        font-size: 0.8rem;
        color: #6c757d;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (min-width: 768px) {
        .page-title {
            font-size: 1.75rem;
        }
        .date-filter-wrapper {
            overflow-x: visible;
        }
        .date-filter {
            flex-wrap: wrap;
        }
    }

    .pagination-wrapper .pagination {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .pagination-wrapper .page-link {
        border: none;
        padding: 12px 16px;
        color: #28a745; 
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .pagination-wrapper .page-link:hover {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        transform: translateY(-1px);
    }
    .pagination-wrapper .page-item.active .page-link {
        background: linear-gradient(135deg, #28a745, #20c997);
        border-color: #28a745;
        color: white;
        font-weight: 700;
    }
    .pagination-wrapper .page-item .page-link[aria-label="Previous"],
    .pagination-wrapper .page-item .page-link[aria-label="Next"] {
        color: #28a745;
        font-weight: bold;
        font-size: 1.1rem;
    }
    .pagination-wrapper .page-item.disabled .page-link {
        color: #a5a5a5;
        background: transparent;
    }

</style>

        <div class="container-fluid py-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="mb-3">
                        <h2 class="page-title mb-1"><?php echo e($greeting); ?>, <?php echo e(auth()->user()->nama); ?>!</h2>
                        <p class="text-muted mb-0" id="live-clock"></p>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <?php if(!auth()->user()->isManajer()): ?>
                            <a href="/dashboard/goods/create" 
                            class="btn btn-primary btn-sm flex-grow-1 flex-sm-grow-0">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Barang
                            </a>
                            <a href="/dashboard/cashier/quick-transaction" 
                            class="btn btn-primary btn-sm flex-grow-1 flex-sm-grow-0">
                            <i class="bi bi-calculator me-2"></i>Tambah Transaksi
                            </a>
                        <?php else: ?>
                            <a href="/dashboard/transactions" 
                            class="btn btn-primary btn-sm flex-grow-1 flex-sm-grow-0">
                            <i class="bi bi-list-ul me-2"></i>Lihat Data Transaksi
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>


    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center mb-4">
        <div class="date-filter-wrapper mb-3 mb-md-0">
            <div class="btn-group date-filter">
                <a href="?range=all_time" class="btn btn-outline-secondary <?php if($range == 'all_time'): ?> active <?php endif; ?>">Semua Waktu</a>
                <a href="?range=today" class="btn btn-outline-secondary <?php if($range == 'today'): ?> active <?php endif; ?>">Hari Ini</a>
                <a href="?range=7_days" class="btn btn-outline-secondary <?php if($range == '7_days'): ?> active <?php endif; ?>">7 Hari</a>
                <a href="?range=30_days" class="btn btn-outline-secondary <?php if($range == '30_days'): ?> active <?php endif; ?>">30 Hari</a>
                <a href="?range=this_month" class="btn btn-outline-secondary <?php if($range == 'this_month'): ?> active <?php endif; ?>">Bulan Ini</a>
            </div>
        </div>
        <div class="text-muted fw-bold text-start text-md-end">Laporan untuk: <?php echo e($rangeTitle); ?></div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">
                        <span>Pendapatan</span>
                        <span class="stat-comparison <?php echo e($revenueComparison >= 0 ? 'positive' : 'negative'); ?>">
                            <i class="bi <?php echo e($revenueComparison >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short'); ?>"></i>
                            <?php echo e(number_format(abs($revenueComparison), 1)); ?>%
                        </span>
                    </div>
                    <div class="stat-value">Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">
                        <span>Laba Kotor</span>
                        <span class="stat-comparison <?php echo e($profitComparison >= 0 ? 'positive' : 'negative'); ?>">
                            <i class="bi <?php echo e($profitComparison >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short'); ?>"></i>
                            <?php echo e(number_format(abs($profitComparison), 1)); ?>%
                        </span>
                    </div>
                    <div class="stat-value text-success">Rp <?php echo e(number_format($totalProfit, 0, ',', '.')); ?></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">
                        <span>Biaya Operasional</span>
                    </div>
                    <div class="stat-value text-danger">Rp <?php echo e(number_format($totalExpenses, 0, ',', '.')); ?></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">
                        <span>Laba Bersih</span>
                    </div>
                    <div class="stat-value text-info">Rp <?php echo e(number_format($netProfit, 0, ',', '.')); ?></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">
                        <span>Transaksi</span>
                        <span class="stat-comparison <?php echo e($transactionComparison >= 0 ? 'positive' : 'negative'); ?>">
                             <i class="bi <?php echo e($transactionComparison >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short'); ?>"></i>
                            <?php echo e(number_format(abs($transactionComparison), 1)); ?>%
                        </span>
                    </div>
                    <div class="stat-value"><?php echo e(number_format($totalTransactions)); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Grafik Pendapatan & Laba</h3>
                    <div id="main-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Produk Terlaris</h3></div>
                <div class="card-body">
                    <div id="top-products-chart" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Mitra Terlaris</h3></div>
                <div class="card-body">
                    <div id="top-mitra-chart" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card card-alert">
                <div class="card-body">
                    <div class="card-alert-header">
                        <h3 class="card-alert-title"><i class="bi bi-box-seam-fill text-danger"></i>Stok Segera Habis</h3>
                        <?php if(!auth()->user()->isManajer()): ?>
                            <a href="<?php echo e(url('/dashboard/goods?status=low_stock')); ?>" class="btn btn-sm btn-light">Lihat Semua</a>
                        <?php endif; ?>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 mb-3 mb-md-0"><div id="low-stock-chart" style="min-height: 250px;"></div></div>
                        <div class="col-12 col-md-6">
                            <?php if($lowStockCount > 0): ?>
                                <ul class="list-group list-group-flush">
                                    <?php $__currentLoopData = $lowStockItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="list-group-item">
                                            <div class="fw-bold"><?php echo e($item->nama); ?> - <span class="text-danger fw-normal">Stok: <?php echo e($item->stok); ?></span></div>
                                            <div class="item-details"><div class="subtext">Mitra: <?php echo e($item->category->nama ?? 'N/A'); ?></div></div>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-center text-muted py-5"><i class="bi bi-check-circle-fill fs-1 text-success"></i><p class="mt-2">Stok Aman!</p></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-alert">
                <div class="card-body">
                     <div class="card-alert-header">
                        <h3 class="card-alert-title"><i class="bi bi-calendar-x-fill text-warning"></i>Segera Expired</h3>
                        <?php if(!auth()->user()->isManajer()): ?>
                            <a href="<?php echo e(url('/dashboard/goods?status=expiring_soon')); ?>" class="btn btn-sm btn-light">Lihat Semua</a>
                        <?php endif; ?>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 mb-3 mb-md-0"><div id="expiring-soon-chart" style="min-height: 250px;"></div></div>
                        <div class="col-12 col-md-6">
                            <?php if($expiringSoonCount > 0): ?>
                                <ul class="list-group list-group-flush">
                                    <?php $__currentLoopData = $expiringSoonItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-bold"><?php echo e($item->nama); ?></div>
                                                <div class="item-details"><div class="subtext">Exp: <?php echo e(\Carbon\Carbon::parse($item->expired_date)->format('d M Y')); ?></div></div>
                                            </div>
                                            <span class="fw-bold <?php echo e($item->days_remaining < 7 ? 'text-danger' : 'text-warning'); ?>"><?php echo e($item->days_remaining <= 0 ? 'Hari Ini' : $item->days_remaining . ' hari'); ?></span>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-center text-muted py-5"><i class="bi bi-shield-check-fill fs-1 text-success"></i><p class="mt-2">Aman dari Expired!</p></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const formatCurrency = (value) => "Rp " + new Intl.NumberFormat('id-ID').format(value);

        const optionsMainChart = {
            series: [
                { name: 'Pendapatan', data: <?php echo $mainChartRevenue; ?> },
                { name: 'Laba', data: <?php echo $mainChartProfit; ?> }
            ],
            chart: { type: 'area', height: 350, toolbar: { show: false }, zoom: { enabled: false } },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: { type: 'category', categories: <?php echo $mainChartLabels; ?>, tooltip: { enabled: false } },
            yaxis: { labels: { formatter: (value) => formatCurrency(value) } },
            tooltip: { y: { formatter: (value) => formatCurrency(value) } },
            colors: ['#206bc4', '#2fb344'],
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1, stops: [0, 90, 100] } },
            legend: { horizontalAlign: 'right' }
        };
        new ApexCharts(document.querySelector("#main-chart"), optionsMainChart).render();

        const optionsTopProducts = {
            series: [{ name: 'Unit Terjual', data: <?php echo $topProductsSeries; ?> }],
            chart: { type: 'bar', height: 250, toolbar: { show: false } },
            plotOptions: { bar: { borderRadius: 4, horizontal: true, distributed: true, } },
            dataLabels: { enabled: true, formatter: (val) => val + " unit", offsetX: 10, style: { colors: ['#333'] } },
            xaxis: { categories: <?php echo $topProductsLabels; ?> },
            legend: { show: false },
            tooltip: { y: { formatter: (val) => val + " unit terjual" } }
        };
        if (<?php echo $topProductsSeries; ?>.length > 0) {
            new ApexCharts(document.querySelector("#top-products-chart"), optionsTopProducts).render();
        } else {
            document.querySelector("#top-products-chart").innerHTML = '<div class="text-center text-muted py-5"><i class="bi bi-bar-chart-line fs-1"></i><p class="mt-2">Belum ada data penjualan.</p></div>';
        }

        const optionsTopMitra = {
            series: [{ name: 'Unit Terjual', data: <?php echo $topMitraSeries; ?> }],
            chart: { type: 'bar', height: 250, toolbar: { show: false } },
            plotOptions: { bar: { borderRadius: 4, horizontal: true, distributed: true, } },
            dataLabels: { enabled: true, formatter: (val) => val + " unit", offsetX: 10, style: { colors: ['#333'] } },
            xaxis: { categories: <?php echo $topMitraLabels; ?> },
            legend: { show: false },
            tooltip: { y: { formatter: (val) => val + " unit terjual" } }
        };
        if (<?php echo $topMitraSeries; ?>.length > 0) {
            new ApexCharts(document.querySelector("#top-mitra-chart"), optionsTopMitra).render();
        } else {
            document.querySelector("#top-mitra-chart").innerHTML = '<div class="text-center text-muted py-5"><i class="bi bi-bar-chart-line fs-1"></i><p class="mt-2">Belum ada data penjualan.</p></div>';
        }

        const optionsLowStock = {
            series: <?php echo $lowStockChart_series; ?>,
            chart: { type: 'donut', height: 280 },
            labels: <?php echo $lowStockChart_labels; ?>,
            plotOptions: { pie: { donut: { labels: { show: true, total: { show: true, label: 'Total Barang', formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0) } } } } },
            colors: ['#d63939', '#f59f00', '#2fb344'],
            legend: { position: 'bottom', fontSize: '12px', markers: { width: 10, height: 10, radius: 4 }, itemMargin: { horizontal: 10 } },
            tooltip: { y: { formatter: (val) => val + " barang" } }
        };
        new ApexCharts(document.querySelector("#low-stock-chart"), optionsLowStock).render();

        const optionsExpiringSoon = {
            series: <?php echo $expiringSoonChart_series; ?>,
            chart: { type: 'donut', height: 280 },
            labels: <?php echo $expiringSoonChart_labels; ?>,
            plotOptions: { pie: { donut: { labels: { show: true, total: { show: true, label: 'Total Barang', formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0) } } } } },
            colors: ['#d63939', '#f59f00', '#2fb344', '#6c757d'],
            legend: { position: 'bottom', fontSize: '12px', markers: { width: 10, height: 10, radius: 4 }, itemMargin: { horizontal: 10 } },
            tooltip: { y: { formatter: (val) => val + " barang" } }
        };
        new ApexCharts(document.querySelector("#expiring-soon-chart"), optionsExpiringSoon).render();

        function updateLiveClock() {
            const sekarang = new Date();
            const namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const clockElement = document.getElementById('live-clock');
            if (clockElement) {
                clockElement.textContent = `${namaHari[sekarang.getDay()]}, ${sekarang.getDate()} ${namaBulan[sekarang.getMonth()]} ${sekarang.getFullYear()} | ${String(sekarang.getHours()).padStart(2, '0')}:${String(sekarang.getMinutes()).padStart(2, '0')}`;
            }
        }
        updateLiveClock();
        setInterval(updateLiveClock, 1000);
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LENOVO\Documents\SEMESTER 5\Magang\Gerai-umkm-mart-finalisasi-main\resources\views/dashboard/index.blade.php ENDPATH**/ ?>