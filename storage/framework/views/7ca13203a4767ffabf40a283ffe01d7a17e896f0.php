

<?php $__env->startSection('container'); ?>
<style>
    .umkm-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.2);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .umkm-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    .umkm-card-header {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 20px;
        border-radius: 20px 20px 0 0;
        position: relative;
        overflow: hidden;
    }
    .umkm-card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.1);
        transform: rotate(45deg);
        transition: all 0.3s ease;
    }
    .umkm-card-header:hover::before {
        right: -30%;
    }
    .umkm-card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .umkm-card-body {
        padding: 1rem;
    }
    .btn-umkm {
        background: linear-gradient(135deg, #206BC4, #4A90E2);
        border: none;
        border-radius: 15px;
        padding: 12px 25px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        color: white;
        text-decoration: none;
    }
    .btn-umkm-sm {
        padding: 8px 15px;
        font-size: 0.875rem;
    }
    .form-control, .form-select {
        border-radius: 15px;
        border: 2px solid #e9ecef;
        padding: 12px 20px;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.9);
    }
    .form-control:focus, .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        background: white;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .table-umkm {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .table-umkm thead th {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        padding: 15px 12px;
        white-space: nowrap;
    }
    .table-umkm tbody td {
        padding: 15px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f8f9fa;
    }
    .table-umkm tbody tr:hover {
        background-color: #f8f9fa;
    }
    .page-title {
        color: white;
        text-align: center;
        margin-bottom: 30px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
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
    .search-section {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .type-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 10px;
        font-weight: 600;
        white-space: nowrap;
    }
    .type-makanan { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
    .type-non-makanan { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
    .type-handycraft { background: linear-gradient(135deg, #6f42c1, #8a2be2); color: white; }
    .type-fashion { background: linear-gradient(135deg, #e83e8c, #d63384); color: white; }
    .type-lainnya { background: linear-gradient(135deg, #6c757d, #5a6268); color: white; }
    .expired-badge {
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 8px;
        font-weight: 600;
        margin-left: 5px;
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

    .expired-danger { background: #dc3545; color: white; animation: pulse 1s infinite; }
    .expired-warning { background: #ffc107; color: #000; }
    .expired-success { background: #28a745; color: white; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    .action-dropdown .dropdown-toggle::after { display: none; }
    @media (min-width: 768px) {
        .page-title h1 { font-size: 2.5rem; }
        .umkm-card-body { padding: 25px; }
    }
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1>📦 MANAJEMEN DATA BARANG</h1>
        <p>Kelola inventori dan stok barang GERAI UMKM MART</p>
    </div>

    <?php if(session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
            <i class="bi bi-x-circle-fill me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="umkm-card">
        <div class="umkm-card-header">
            <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center w-100 gap-2">
                <h3 class="umkm-card-title mb-2 mb-md-0">
                    <i class="bi bi-box-seam"></i>
                    Data Barang
                </h3>
                <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
                    <form id="bulk-delete-form" action="<?php echo e(route('goods.bulkDelete')); ?>" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="button" id="bulk-delete-button" class="btn btn-danger btn-umkm-sm" style="display: none;">
                            <i class="bi bi-trash-fill"></i> Hapus Terpilih
                        </button>
                    </form>
                    <a href="/dashboard/goods/cetakbarcode" class="btn-umkm btn-umkm-sm">
                        <i class="bi bi-qr-code"></i>
                        Cetak Barcode Barang
                    </a>
                    <a href="/dashboard/goods/create" class="btn-umkm btn-umkm-sm">
                        <i class="bi bi-plus-circle"></i>
                        Tambah Barang
                    </a>
                </div>
            </div>
        </div>

        <div class="umkm-card-body">
            <div class="search-section">
                <form action="/dashboard/goods" method="GET" id="search-form">
                    <div class="row align-items-end">
                        <div class="col-12 col-md-5 mb-3 mb-md-0">
                            <label class="form-label text-white fw-bold">
                                <i class="bi bi-search me-2"></i>Cari Nama/Barcode
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Masukkan nama barang atau barcode..."
                                    name="search" value="<?php echo e(request('search')); ?>" id="search-input">
                                <button class="btn btn-umkm" type="submit">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-3 mb-md-0">
                            <label class="form-label text-white fw-bold">
                                <i class="bi bi-building me-2"></i>Filter Mitra Binaan
                            </label>
                            <select class="form-select" name="category_id" id="category-filter">
                                <option value="all">Semua Mitra</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>>
                                        <?php echo e($category->nama); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-3 text-md-end mt-3 mt-md-0">
                            <div class="text-white">
                                <small><i class="bi bi-info-circle me-1"></i>Total: <?php echo e($goods->total()); ?> barang</small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-umkm">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align: center;">
                                <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                            </th>
                            <th style="width: 5%;">NO</th>
                            <th style="width: 10%;">
                                Tgl Masuk
                                <button type="button" class="btn btn-sm btn-light ms-2 sort-toggle"
                                    data-sort-param="tgl_masuk"
                                    data-sort-order="<?php echo e(request('sort_tgl_masuk', 'none')); ?>"
                                    title="Urutkan berdasarkan tanggal masuk">
                                    <i class="bi <?php echo e(request('sort_tgl_masuk') == 'asc' ? 'bi-sort-up' : (request('sort_tgl_masuk') == 'desc' ? 'bi-sort-down' : 'bi-arrow-down-up')); ?>"></i>
                                </button>
                            </th>
                            <th style="width: 24%;">Nama Barang</th>
                            <th style="width: 13%;">Jenis</th>
                            <th style="width: 14%;">
                                Expired
                                <button type="button" class="btn btn-sm btn-light ms-2 sort-toggle"
                                    data-sort-param="expired"
                                    data-sort-order="<?php echo e(request('sort_expired', 'none')); ?>"
                                    title="Urutkan berdasarkan tanggal expired">
                                    <i class="bi <?php echo e(request('sort_expired') == 'asc' ? 'bi-sort-up' : (request('sort_expired') == 'desc' ? 'bi-sort-down' : 'bi-arrow-down-up')); ?>"></i>
                                </button>
                            </th>
                            <th style="width: 13%;">Mitra Binaan</th>
                            <th style="width: 7%;">
                                Stok
                                <button type="button" class="btn btn-sm btn-light ms-2 sort-toggle"
                                    data-sort-param="stok"
                                    data-sort-order="<?php echo e(request('sort_stok', 'none')); ?>"
                                    title="Urutkan berdasarkan stok">
                                    <i class="bi <?php echo e(request('sort_stok') == 'asc' ? 'bi-sort-up' : (request('sort_stok') == 'desc' ? 'bi-sort-down' : 'bi-arrow-down-up')); ?>"></i>
                                </button>
                            </th>
                            <th style="width: 9%;">
                                Harga
                                <button type="button" class="btn btn-sm btn-light ms-2 sort-toggle"
                                    data-sort-param="harga"
                                    data-sort-order="<?php echo e(request('sort_harga', 'none')); ?>"
                                    title="Urutkan berdasarkan harga">
                                    <i class="bi <?php echo e(request('sort_harga') == 'asc' ? 'bi-sort-up' : (request('sort_harga') == 'desc' ? 'bi-sort-down' : 'bi-arrow-down-up')); ?>"></i>
                                </button>
                            </th>
                            <th style="width: 5%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $goods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $good): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center">
                                <input class="form-check-input item-checkbox" type="checkbox" value="<?php echo e($good->id); ?>">
                            </td>
                            <td><strong><?php echo e($goods->firstItem() + $key); ?></strong></td>
                            <td>
                                <i class="bi bi-calendar3 text-success me-1"></i>
                                <?php echo e(\Carbon\Carbon::parse($good->tgl_masuk)->format('d/m/Y')); ?>

                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-box text-primary me-2"></i>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <strong><?php echo e($good->nama); ?></strong>
                                            <?php if($good->is_grosir_active): ?>
                                                <span class="wholesale-indicator" title="Barang Grosir - Min <?php echo e($good->min_qty_grosir); ?> unit: Rp <?php echo e(number_format($good->harga_grosir, 0, ',', '.')); ?>">
                                                    🏷️ GROSIR
                                                </span>
                                            <?php endif; ?>
                                            <?php if($good->is_tebus_murah_active): ?>
                                                <span class="tebus-murah-indicator" title="Tebus Murah - Min. Transaksi Rp <?php echo e(number_format($good->min_total_tebus_murah, 0, ',', '.')); ?>: Harga Spesial Rp <?php echo e(number_format($good->harga_tebus_murah, 0, ',', '.')); ?>">
                                                    % TEBUS MURAH
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($good->barcode): ?>
                                            <small class="text-muted"><?php echo e($good->barcode); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="type-badge type-<?php echo e(str_replace('_', '-', $good->type)); ?>">
                                    <?php if($good->type == 'makanan'): ?>
                                        Makanan & Minuman
                                    <?php elseif($good->type == 'non_makanan'): ?>
                                        Non Makanan & Minuman
                                    <?php elseif($good->type == 'handycraft'): ?>
                                        Handycraft
                                    <?php elseif($good->type == 'fashion'): ?>
                                        Fashion
                                    <?php else: ?>
                                        Lainnya
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td style="white-space: nowrap;">
                                <?php if($good->expired_date): ?>
                                    <div>
                                        <small class="text-muted"><?php echo e($good->expired_date->format('d/m/Y')); ?></small>
                                        <?php $status = $good->getExpirationStatus(); ?>
                                        <?php if($status == 'expired'): ?> <span class="expired-badge expired-danger">⚠️ EXPIRED</span>
                                        <?php elseif($status == 'expiring_soon'): ?> <span class="expired-badge expired-warning">⏰ <?php echo e($good->getDaysUntilExpiration()); ?> hari</span>
                                        <?php else: ?> <span class="expired-badge expired-success">✅ <?php echo e($good->getDaysUntilExpiration()); ?> hari</span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted"><i class="bi bi-dash-circle"></i> Tidak ada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <i class="bi bi-building text-info me-1"></i>
                                <?php echo e($good->category ? $good->category->nama : 'Tidak ada mitra'); ?>

                            </td>
                            <td>
                                <span class="badge <?php echo e($good->stok <= 5 ? 'bg-danger' : ($good->stok <= 20 ? 'bg-warning' : 'bg-success')); ?>">
                                    <?php echo e($good->stok); ?> unit
                                </span>
                            </td>
                            <td>
                                <strong class="text-success">
                                    Rp <?php echo e(number_format($good->harga, 0, ',', '.')); ?>

                                </strong>
                            </td>
                            <td class="text-center">
                                <div class="dropup action-dropdown">
                                    <button class="btn btn-action dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical fs-5"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="/dashboard/goods/<?php echo e($good->id); ?>/edit"><i class="bi bi-pencil-square text-warning"></i> Edit</a></li>
                                        <li>
                                            <form action="/dashboard/goods/<?php echo e($good->id); ?>" method="post" class="dropdown-item-form">
                                                <?php echo method_field('delete'); ?>
                                                <?php echo csrf_field(); ?>
                                                <button type="button" class="dropdown-item text-danger" onclick="showDeleteModal(this.form, '<?php echo e($good->nama); ?>')"><i class="bi bi-trash"></i> Hapus</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                    <h5>Belum ada data barang</h5>
                                    <p>Silakan tambah barang baru untuk memulai</p>
                                    <a href="/dashboard/goods/create" class="btn-umkm"><i class="bi bi-plus-circle"></i> Tambah Barang Pertama</a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($goods->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                 <?php echo e($goods->appends(request()->query())->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border-bottom: none; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
            </div>
            <div class="modal-body fs-5 text-center py-4">
                Apakah Anda yakin ingin menghapus barang <br><strong id="itemNameToDelete" class="text-danger"></strong>?
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton" style="border-radius: 10px;">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="bulkDeleteConfirmationModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border-bottom: none; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title" id="bulkDeleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
            </div>
            <div class="modal-body fs-5 text-center py-4">
                Apakah Anda yakin ingin menghapus <strong id="bulkDeleteCount" class="text-danger"></strong> barang yang dipilih?
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmBulkDeleteButton" style="border-radius: 10px;">Ya, Hapus Semua</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModalElement = document.getElementById('deleteConfirmationModal');
        const deleteModal = new bootstrap.Modal(deleteModalElement);
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        const itemNameToDeleteSpan = document.getElementById('itemNameToDelete');
        let currentDeleteForm = null;

        window.showDeleteModal = function(formElement, itemName) {
            currentDeleteForm = formElement;
            itemNameToDeleteSpan.textContent = itemName;
            deleteModal.show();
        }

        confirmDeleteButton.addEventListener('click', function() {
            if (currentDeleteForm) {
                deleteModal.hide();
                currentDeleteForm.submit();
            }
        });

        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const bulkDeleteButton = document.getElementById('bulk-delete-button');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');
        const bulkDeleteModal = new bootstrap.Modal(document.getElementById('bulkDeleteConfirmationModal'));
        const bulkDeleteCountSpan = document.getElementById('bulkDeleteCount');
        const confirmBulkDeleteButton = document.getElementById('confirmBulkDeleteButton');

        function updateBulkDeleteButtonState() {
            const selectedCount = document.querySelectorAll('.item-checkbox:checked').length;
            if (selectedCount > 0) {
                bulkDeleteButton.style.display = 'inline-flex';
                const textNode = bulkDeleteButton.querySelector('.bi').nextSibling;
                if (textNode) {
                    textNode.textContent = ` Hapus ${selectedCount} Terpilih`;
                }
            } else {
                bulkDeleteButton.style.display = 'none';
            }
            if (itemCheckboxes.length > 0) {
                selectAllCheckbox.checked = selectedCount > 0 && selectedCount === itemCheckboxes.length;
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkDeleteButtonState();
            });
        }

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkDeleteButtonState);
        });

        if (bulkDeleteButton) {
            bulkDeleteButton.addEventListener('click', function() {
                const selectedCount = document.querySelectorAll('.item-checkbox:checked').length;
                if (selectedCount > 0) {
                    bulkDeleteCountSpan.textContent = selectedCount;
                    bulkDeleteModal.show();
                }
            });
        }

        if (confirmBulkDeleteButton) {
            confirmBulkDeleteButton.addEventListener('click', function() {
                bulkDeleteForm.querySelectorAll('input[name="selected_ids[]"]').forEach(input => input.remove());
                document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'selected_ids[]';
                    hiddenInput.value = checkbox.value;
                    bulkDeleteForm.appendChild(hiddenInput);
                });
                bulkDeleteForm.submit();
            });
        }

        updateBulkDeleteButtonState();

        const sortToggles = document.querySelectorAll('.sort-toggle');
        sortToggles.forEach(toggleButton => {
            toggleButton.addEventListener('click', function() {
                const sortParam = this.dataset.sortParam;
                let currentSortOrder = this.dataset.sortOrder;
                let newSortOrder;

                if (currentSortOrder === 'none') { newSortOrder = 'asc'; }
                else if (currentSortOrder === 'asc') { newSortOrder = 'desc'; }
                else { newSortOrder = 'none'; }

                const url = new URL(window.location.href);
                url.searchParams.delete('sort_expired');
                url.searchParams.delete('sort_tgl_masuk');
                url.searchParams.delete('sort_stok');
                url.searchParams.delete('sort_harga');

                if (newSortOrder !== 'none') {
                    url.searchParams.set(`sort_${sortParam}`, newSortOrder);
                }

                url.searchParams.delete('page');
                window.location.href = url.toString();
            });
        });

        const categoryFilterSelect = document.getElementById('category-filter');
        if (categoryFilterSelect) {
            categoryFilterSelect.addEventListener('change', function() {
                document.getElementById('search-form').submit();
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang Pelindo\project\UMKM-Web\resources\views/dashboard/goods/index.blade.php ENDPATH**/ ?>