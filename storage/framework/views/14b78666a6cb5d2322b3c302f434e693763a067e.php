

<?php $__env->startSection('container'); ?>
    <style>
        .cashier-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .cashier-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .cashier-card-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 20px;
            border-radius: 20px 20px 0 0;
            position: relative;
            overflow: hidden;
        }

        .cashier-card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            transition: all 0.3s ease;
        }

        .cashier-card-header:hover::before {
            right: -30%;
        }

        .cashier-card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cashier-card-body {
            padding: 25px;
            overflow: hidden;
        }

        .btn-cashier {
        background: linear-gradient(135deg, #206BC4, #4A90E2);
        border: none;
        border-radius: 15px;
        padding: 10px 20px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;

        width: auto;
        max-width: max-content;
        white-space: nowrap;
        }

        .btn-cashier:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-cashier-sm {
            padding: 5px 12px;
            font-size: 0.875rem;
            text-transform: none;
            letter-spacing: normal;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-print {
            background: #17a2b8;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.875rem;
        }

        .btn-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
            background: #17a2b8;
            color: white;
            text-decoration: none;
        }

        .form-control,
        .form-select {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 12px 20px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            background: white;
        }

        .table-cashier {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .table-cashier thead th {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            padding: 15px 12px;
            white-space: nowrap;
        }

        .table-cashier tbody td {
            padding: 15px 12px;
            vertical-align: middle;
            border-bottom: 1px solid #f8f9fa;
        }

        .table-cashier tbody tr:last-child td {
            border-bottom: none;
        }

        .table-cashier tbody tr:hover {
            background-color: #f8f9fa;
        }

        .page-title {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .page-title h1 {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 15px;
            color: #ffffff;
            text-shadow: 0 3px 6px rgba(0,0,0,0.4);
        }

        .page-title p {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
            opacity: 1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

         /* Pagination  */
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


        @media (min-width: 768px) {
            .page-title h1 {
                font-size: 2.5rem;
            }
        }
    </style>

    <div class="container-fluid py-4">
        <div class="page-title">
            <h1><i class="bi bi-cash-register"></i> SISTEM KASIR</h1>
            <p>Lakukan transaksi penjualan dan lihat riwayat transaksi</p>
        </div>

        <?php if(session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="border-radius: 15px; border: none;">
                <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="cashier-card">
            <div class="cashier-card-header">
                <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center">
                    <h3 class="cashier-card-title mb-2 mb-md-0">
                        <i class="bi bi-receipt"></i>
                        Riwayat Transaksi
                    </h3>
                    <a href="/dashboard/cashier/quick-transaction" class="btn btn-cashier btn-cashier-sm w-100 w-md-auto mt-2 mt-md-0">
                        <i class="bi bi-plus-circle"></i>
                        Tambah Transaksi
                    </a>
                </div>
            </div>

            <div class="cashier-card-body">
                <div class="table-responsive">
                    <table class="table table-cashier">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>No. Nota</th>
                                <th>Waktu Transaksi</th>
                                <th class="d-none d-lg-table-cell">Petugas</th>
                                <th class="d-none d-md-table-cell">Metode Bayar</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><strong><?php echo e(($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration); ?></strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-hash text-success"></i>
                                        <?php echo e($transaction->no_nota); ?>

                                    </td>
                                    <td style="white-space:nowrap;">
                                        <i class="bi bi-clock text-muted me-1"></i>
                                        <?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s')); ?>

                                    </td>
                                    <td class="d-none d-lg-table-cell"><?php echo e($transaction->user->nama); ?></td>
                                    <td class="d-none d-md-table-cell"><?php echo e($transaction->metode_pembayaran); ?></td>
                                    <td>
                                        <?php if(strtolower(trim($transaction->status)) == 'lunas'): ?>
                                            <span class="badge bg-success"><?php echo e($transaction->status); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark"><?php echo e($transaction->status); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong>Rp <?php echo e(number_format($transaction->total_harga, 0, ',', '.')); ?></strong></td>
                                    <td class="text-center">
                                        <a href="/dashboard/cashiers/print-nota?no_nota=<?php echo e($transaction->no_nota); ?>"
                                            class="btn btn-print" target="_blank" title="Cetak Nota">
                                            <i class="bi bi-printer"></i>
                                            <span class="d-none d-md-inline">Cetak Nota</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-cart-x display-4 d-block mb-3"></i>
                                            <h5>Belum ada transaksi hari ini</h5>
                                            <p>Silakan buat transaksi baru untuk memulai</p>
                                            <a href="/dashboard/cashier/quick-transaction" class="btn btn-cashier">
                                                <i class="bi bi-plus-circle"></i>
                                                Buat Transaksi Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <?php if($transactions->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <div class="pagination-wrapper">
                            <?php echo e($transactions->links()); ?>

                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function handleFormSubmit(form) {
            const button = form.querySelector('button[type="submit"]');
            if (button) {
                const originalButtonHTML = button.innerHTML;

                button.disabled = true;
                button.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="d-none d-md-inline">Loading...</span>
                `;

                setTimeout(function () {
                    button.disabled = false;
                    button.innerHTML = originalButtonHTML;
                }, 3000);
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\SEMESTER 6\KERJA PRAKTEK PELINDO\project umkm\NEW\kasirku-main\resources\views/dashboard/cashiers/index.blade.php ENDPATH**/ ?>