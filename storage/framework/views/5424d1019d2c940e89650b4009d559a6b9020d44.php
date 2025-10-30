

<?php $__env->startSection('container'); ?>
<style>
    body {
        background-color: #D3D3D3;
    }

    .umkm-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.2);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        overflow: hidden;
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

    .umkm-card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .umkm-card-body {
        padding: 1.5rem; /* [RESPONSIVE] */
        background: white;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-control, .form-select {
        border-radius: 15px;
        border: 2px solid #e9ecef;
        padding: 12px 20px;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.9);
        font-size: 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        background: white;
        transform: translateY(-1px);
    }

    .input-group-text {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        border-radius: 15px 0 0 15px;
        font-weight: 600;
    }

    .input-group .form-control {
        border-radius: 0 15px 15px 0;
        border-left: none;
    }

    .btn-umkm, .btn-secondary-umkm {
        background: linear-gradient(135deg, #28a745, #20c997);
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
        justify-content: center; /* [RESPONSIVE] */
        gap: 8px;
    }

    .btn-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-secondary-umkm {
        background: linear-gradient(135deg, #6c757d, #5a6268);
    }

    .btn-secondary-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
        color: white;
        text-decoration: none;
    }

    .alert-umkm {
        border-radius: 15px;
        border: none;
        padding: 15px 20px;
        background: linear-gradient(135deg, rgba(23, 162, 184, 0.1), rgba(19, 132, 150, 0.1));
        border-left: 4px solid #17a2b8;
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

    .form-section {
        background: rgba(248, 249, 250, 0.5);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .section-title {
        color: #28a745;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .required { color: #dc3545; }

    .expired-section, .wholesale-section, .tebus-murah-section {
        display: none;
        border-radius: 15px;
        padding: 20px;
        margin-top: 15px;
    }
    .expired-section { background: rgba(255, 193, 7, 0.1); border: 2px solid rgba(255, 193, 7, 0.3); }
    .wholesale-section { background: rgba(40, 167, 69, 0.1); border: 2px solid rgba(40, 167, 69, 0.3); }
    .tebus-murah-section { background: rgba(220, 53, 69, 0.1); border: 2px solid rgba(220, 53, 69, 0.3); }

    .expired-section.show, .wholesale-section.show, .tebus-murah-section.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    .price-info, .wholesale-info, .tebus-murah-info {
        border-radius: 10px;
        padding: 15px;
        margin-top: 15px;
    }
    .price-info { background: rgba(23, 162, 184, 0.05); border-left: 4px solid #17a2b8; }
    .wholesale-info { background: rgba(40, 167, 69, 0.05); border-left: 4px solid #28a745; }
    .tebus-murah-info { background: rgba(220, 53, 69, 0.05); border-left: 4px solid #dc3545; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (min-width: 768px) {
        .page-title h1 {
            font-size: 2.5rem;
        }
        .umkm-card-body {
            padding: 30px;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1>➕ TAMBAH BARANG BARU</h1>
        <p>Tambahkan produk baru ke inventori GERAI UMKM MART</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 col-md-12">
            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-plus-circle"></i>
                        Form Tambah Barang
                    </h3>
                </div>

                <div class="umkm-card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/dashboard/goods" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="form-section">
                            <div class="section-title">
                                <i class="bi bi-info-circle"></i>
                                Informasi Dasar
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="category_id" class="form-label">
                                        <i class="bi bi-building text-success"></i>
                                        Mitra <span class="required">*</span>
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="category_id" id="category_id" required>
                                        <option value="">-- Pilih Mitra --</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                                <?php echo e($category->nama); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="tgl_masuk" class="form-label">
                                        <i class="bi bi-calendar-event text-success"></i>
                                        Tanggal Masuk <span class="required">*</span>
                                    </label>
                                    <input type="date" class="form-control <?php $__errorArgs = ['tgl_masuk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="tgl_masuk" name="tgl_masuk" value="<?php echo e(old('tgl_masuk', date('Y-m-d'))); ?>" required>
                                    <?php $__errorArgs = ['tgl_masuk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="flex-grow-1">
                                        <label for="nama" class="form-label">
                                            <i class="bi bi-box text-success"></i>
                                            Nama Barang <span class="required">*</span>
                                        </label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="nama" name="nama" value="<?php echo e(old('nama')); ?>" required autofocus placeholder="Masukkan nama barang...">
                                        <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_existing_barcode" name="use_existing_barcode" <?php echo e(old('use_existing_barcode') ? 'checked' : ''); ?> onchange="toggleExistingBarcodeSection()">
                                        <label class="form-check-label fw-bold" for="use_existing_barcode" style="font-size: 0.95rem;">
                                            <i class="bi bi-qr-code text-info"></i> Barcode Existing
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="existing-barcode-section" class="form-section" style="display: none; background: rgba(23, 162, 184, 0.1); border: 2px solid rgba(23, 162, 184, 0.3);">
                            <div class="section-title">
                                <i class="bi bi-qr-code text-info"></i>
                                Barcode Pabrikan
                            </div>
                            <div class="mb-3">
                                <label for="existing_barcode" class="form-label">
                                    <i class="bi bi-scanner text-info"></i>
                                    Pindai Barcode <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control <?php $__errorArgs = ['existing_barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="existing_barcode" name="existing_barcode" value="<?php echo e(old('existing_barcode')); ?>" placeholder="Arahkan scanner ke barcode pabrikan..." autocomplete="off">
                                <?php $__errorArgs = ['existing_barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle"></i> Gunakan scanner barcode untuk membaca barcode pabrikan</small>
                            </div>
                            <div id="barcode-success-message" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
                                <i class="bi bi-check-circle"></i> <strong>BARCODE BERHASIL DI PINDAI DAN DI SIMPAN</strong>
                                <div class="mt-2">
                                    <strong>Nomor Barcode:</strong> <span id="barcode-display" class="badge bg-info"></span>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title">
                                <i class="bi bi-tags"></i>
                                Jenis & Masa Berlaku
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">
                                    <i class="bi bi-collection text-success"></i>
                                    Jenis Barang <span class="required">*</span>
                                </label>
                                <select class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="type" id="type" required onchange="toggleExpiredField(); calculateSellingPrice();">
                                    <option value="">-- Pilih Jenis Barang --</option>
                                    <option value="makanan" <?php echo e(old('type') == 'makanan' ? 'selected' : ''); ?>>Makanan & Minuman</option>
                                    <option value="non_makanan" <?php echo e(old('type') == 'non_makanan' ? 'selected' : ''); ?>>Non Makanan & Minuman</option>
                                    <option value="lainnya" <?php echo e(old('type') == 'lainnya' ? 'selected' : ''); ?>>Lainnya</option>
                                    <option value="handycraft" <?php echo e(old('type') == 'handycraft' ? 'selected' : ''); ?>>Handycraft</option>
                                    <option value="fashion" <?php echo e(old('type') == 'fashion' ? 'selected' : ''); ?>>Fashion</option>
                                </select>
                                <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div id="expired-section" class="expired-section">
                                <label for="expired_date" class="form-label">
                                    <i class="bi bi-calendar-x text-warning"></i>
                                    Tanggal Expired <span class="required">*</span>
                                </label>
                                <input type="date" class="form-control <?php $__errorArgs = ['expired_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="expired_date" name="expired_date" value="<?php echo e(old('expired_date')); ?>" min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>">
                                <?php $__errorArgs = ['expired_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle"></i> Tanggal expired harus setelah hari ini</small>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title">
                                <i class="bi bi-currency-dollar"></i>
                                Harga & Stok
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="harga_asli_display" class="form-label">
                                        <i class="bi bi-cash text-success"></i>
                                        Harga Asli <span class="required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control <?php $__errorArgs = ['harga_asli'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="harga_asli_display" value="<?php echo e(old('harga_asli')); ?>" required placeholder="0" oninput="formatRupiah(this); calculateSellingPrice();">
                                        <input type="hidden" name="harga_asli" id="harga_asli" value="<?php echo e(old('harga_asli')); ?>">
                                    </div>
                                    <?php $__errorArgs = ['harga_asli'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="text-muted mt-2 d-block">Harga asli sebelum markup</small>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="stok" class="form-label">
                                        <i class="bi bi-boxes text-success"></i>
                                        Stok <span class="required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control <?php $__errorArgs = ['stok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="stok" name="stok" value="<?php echo e(old('stok')); ?>" required min="0" placeholder="0">
                                        <span class="input-group-text">unit</span>
                                    </div>
                                    <?php $__errorArgs = ['stok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="markup_percentage" class="form-label">
                                    <i class="bi bi-percent text-success"></i>
                                    Persentase Markup (Opsional)
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control <?php $__errorArgs = ['markup_percentage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="markup_percentage" name="markup_percentage" value="<?php echo e(old('markup_percentage')); ?>" min="0" max="100" step="0.01" placeholder="Contoh: 5 (untuk 5%)" oninput="calculateSellingPrice();">
                                    <span class="input-group-text">%</span>
                                </div>
                                <?php $__errorArgs = ['markup_percentage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted mt-2 d-block">Biarkan kosong untuk markup otomatis (Makanan: 2%, Non-Makanan: 5%)</small>
                            </div>
                            <div id="price-info" class="price-info" style="display: none;">
                                <h6 class="text-info mb-2"><i class="bi bi-calculator"></i> Informasi Harga Jual</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Harga Asli:</strong> <span id="display-harga-asli" class="text-muted">Rp 0</span></p>
                                        <p class="mb-1"><strong>Markup:</strong> <span id="markup-percent" class="text-info">0%</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Harga Jual:</strong> <span id="display-harga-jual" class="text-success fw-bold">Rp 0</span></p>
                                        <p class="mb-0"><strong>Selisih:</strong> <span id="display-profit" class="text-success">Rp 0</span></p> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title"><i class="bi bi-cart-plus"></i> Pengaturan Grosir</div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="is_grosir_active" name="is_grosir_active" <?php echo e(old('is_grosir_active') ? 'checked' : ''); ?> onchange="toggleWholesaleSection()">
                                <label class="form-check-label fw-bold" for="is_grosir_active"><i class="bi bi-shop text-success"></i> Aktifkan Harga Grosir</label>
                                <small class="text-muted d-block">Centang untuk memberikan harga khusus untuk pembelian dalam jumlah besar</small>
                            </div>
                            <div id="wholesale-section" class="wholesale-section">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="min_qty_grosir" class="form-label"><i class="bi bi-123 text-success"></i> Minimal Pembelian Grosir</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control <?php $__errorArgs = ['min_qty_grosir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="min_qty_grosir" name="min_qty_grosir" value="<?php echo e(old('min_qty_grosir')); ?>" min="2" placeholder="10" onchange="calculateWholesaleSavings()">
                                            <span class="input-group-text">unit</span>
                                        </div>
                                        <?php $__errorArgs = ['min_qty_grosir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <small class="text-muted">Minimal 2 unit untuk grosir</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="harga_grosir" class="form-label"><i class="bi bi-cash-coin text-success"></i> Harga Grosir per Unit</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control <?php $__errorArgs = ['harga_grosir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="harga_grosir" name="harga_grosir" value="<?php echo e(old('harga_grosir')); ?>" min="0" placeholder="0" onchange="calculateWholesaleSavings()">
                                        </div>
                                        <?php $__errorArgs = ['harga_grosir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <small class="text-muted">Harga per unit untuk pembelian grosir</small>
                                    </div>
                                </div>
                                <div id="wholesale-info" class="wholesale-info" style="display: none;">
                                    <h6 class="text-success mb-2"><i class="bi bi-calculator"></i> Informasi Penghematan Grosir</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Hemat per unit:</strong> <span id="savings-per-unit" class="text-success">Rp 0</span></p>
                                            <p class="mb-1"><strong>Persentase hemat:</strong> <span id="savings-percent" class="text-success">0%</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Contoh pembelian <span id="example-qty">10</span> unit:</strong></p>
                                            <p class="mb-1">Eceran: <span id="retail-total" class="text-muted">Rp 0</span></p>
                                            <p class="mb-1">Grosir: <span id="wholesale-total" class="text-success fw-bold">Rp 0</span></p>
                                            <p class="mb-0">Hemat: <span id="total-savings" class="text-success fw-bold">Rp 0</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title"><i class="bi bi-tag-fill" style="color: #dc3545;"></i> Pengaturan Tebus Murah</div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="is_tebus_murah_active" name="is_tebus_murah_active" <?php echo e(old('is_tebus_murah_active') ? 'checked' : ''); ?> onchange="toggleTebusMusahSection()" style="background-color: #dc3545; border-color: #dc3545;">
                                <label class="form-check-label fw-bold" for="is_tebus_murah_active"><i class="bi bi-percent text-danger"></i> Aktifkan Harga Tebus Murah</label>
                                <small class="text-muted d-block">Centang untuk memberikan harga khusus ketika total pembelian mencapai nilai tertentu</small>
                            </div>
                            <div id="tebus-murah-section" class="tebus-murah-section">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="min_total_tebus_murah" class="form-label"><i class="bi bi-cash-stack text-danger"></i> Minimal Pembelian</label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white;">Rp</span>
                                            <input type="number" class="form-control <?php $__errorArgs = ['min_total_tebus_murah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="min_total_tebus_murah" name="min_total_tebus_murah" value="<?php echo e(old('min_total_tebus_murah')); ?>" min="0" placeholder="100000" onchange="calculateTebusMusahSavings()">
                                        </div>
                                        <?php $__errorArgs = ['min_total_tebus_murah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <small class="text-muted">Total nilai transaksi minimal</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="harga_tebus_murah" class="form-label"><i class="bi bi-tag text-danger"></i> Harga Tebus Murah</label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white;">Rp</span>
                                            <input type="number" class="form-control <?php $__errorArgs = ['harga_tebus_murah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="harga_tebus_murah" name="harga_tebus_murah" value="<?php echo e(old('harga_tebus_murah')); ?>" min="0" placeholder="0" onchange="calculateTebusMusahSavings()">
                                        </div>
                                        <?php $__errorArgs = ['harga_tebus_murah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <small class="text-muted">Harga khusus untuk barang ini</small>
                                    </div>
                                </div>
                                <div id="tebus-murah-info" class="tebus-murah-info" style="display: none;">
                                    <h6 class="text-danger mb-2"><i class="bi bi-calculator"></i> Informasi Penghematan Tebus Murah</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Hemat per unit:</strong> <span id="tebus-savings-per-unit" class="text-danger">Rp 0</span></p>
                                            <p class="mb-1"><strong>Persentase hemat:</strong> <span id="tebus-savings-percent" class="text-danger">0%</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Syarat:</strong> Total transaksi min. <span id="min-total-display" class="text-danger fw-bold">Rp 0</span></p>
                                            <p class="mb-1">Harga normal: <span id="normal-price" class="text-muted">Rp 0</span></p>
                                            <p class="mb-0">Harga tebus murah: <span id="tebus-price" class="text-danger fw-bold">Rp 0</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-umkm">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle-fill text-info me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <strong>Informasi Barcode</strong>
                                    <p class="mb-0">Barcode akan dibuat otomatis setelah barang berhasil disimpan. Anda dapat mencetak barcode dari halaman edit barang.</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid d-sm-flex justify-content-sm-between gap-2 pt-3 mt-3">
                            <a href="/dashboard/goods" class="btn btn-secondary-umkm">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-umkm">
                                <i class="bi bi-save"></i>
                                Simpan Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function formatRupiah(input) {
    let value = input.value;
    let number_string = value.replace(/[^,\d]/g, '').toString();
    document.getElementById('harga_asli').value = number_string;
    let split = number_string.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    input.value = rupiah;
}

function toggleExpiredField() {
    const typeSelect = document.getElementById('type');
    const expiredSection = document.getElementById('expired-section');
    const expiredInput = document.getElementById('expired_date');
    if (typeSelect.value === 'makanan' || typeSelect.value === 'non_makanan') {
        expiredSection.classList.add('show');
        expiredInput.required = true;
    } else {
        expiredSection.classList.remove('show');
        expiredInput.required = false;
        expiredInput.value = '';
    }
}

function calculateSellingPrice() {
    const hargaAsli = parseFloat(document.getElementById('harga_asli').value) || 0;
    const typeSelect = document.getElementById('type');
    const markupPercentageInput = document.getElementById('markup_percentage'); // [BARU]
    const priceInfo = document.getElementById('price-info');

    if (hargaAsli > 0 && typeSelect.value) {
        let markup;
        let markupDisplay;

        if (markupPercentageInput.value !== '' && !isNaN(parseFloat(markupPercentageInput.value))) {
            markup = parseFloat(markupPercentageInput.value) / 100;
            markupDisplay = parseFloat(markupPercentageInput.value).toFixed(0) + '% (Manual)';
        } else {
            markup = typeSelect.value === 'makanan' ? 0.02 : 0.05;
            markupDisplay = (markup * 100).toFixed(0) + '% (Otomatis)';
        }

        const hargaJual = hargaAsli + (hargaAsli * markup);
        const selisih = hargaJual - hargaAsli; 

        document.getElementById('display-harga-asli').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaAsli);
        document.getElementById('markup-percent').textContent = markupDisplay;
        document.getElementById('display-harga-jual').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaJual);
        document.getElementById('display-profit').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(selisih); // [UBAH] profit menjadi selisih
        priceInfo.style.display = 'block';
    } else {
        priceInfo.style.display = 'none';
    }
}

function toggleWholesaleSection() {
    const checkbox = document.getElementById('is_grosir_active');
    const wholesaleSection = document.getElementById('wholesale-section');
    const minQtyInput = document.getElementById('min_qty_grosir');
    const wholesalePriceInput = document.getElementById('harga_grosir');
    if (checkbox.checked) {
        wholesaleSection.classList.add('show');
        minQtyInput.required = true;
        wholesalePriceInput.required = true;
    } else {
        wholesaleSection.classList.remove('show');
        minQtyInput.required = false;
        wholesalePriceInput.required = false;
        minQtyInput.value = '';
        wholesalePriceInput.value = '';
        document.getElementById('wholesale-info').style.display = 'none';
    }
}

function toggleTebusMusahSection() {
    const checkbox = document.getElementById('is_tebus_murah_active');
    const tebusMusahSection = document.getElementById('tebus-murah-section');
    const minTotalInput = document.getElementById('min_total_tebus_murah');
    const tebusMusahPriceInput = document.getElementById('harga_tebus_murah');
    if (checkbox.checked) {
        tebusMusahSection.classList.add('show');
        minTotalInput.required = true;
        tebusMusahPriceInput.required = true;
    } else {
        tebusMusahSection.classList.remove('show');
        minTotalInput.required = false;
        tebusMusahPriceInput.required = false;
        minTotalInput.value = '';
        tebusMusahPriceInput.value = '';
        document.getElementById('tebus-murah-info').style.display = 'none';
    }
}

function calculateWholesaleSavings() {
    const hargaAsli = parseFloat(document.getElementById('harga_asli').value) || 0;
    const typeSelect = document.getElementById('type');
    const markupPercentageInput = document.getElementById('markup_percentage');
    const wholesalePrice = parseFloat(document.getElementById('harga_grosir').value) || 0;
    const minQty = parseInt(document.getElementById('min_qty_grosir').value) || 10;
    const wholesaleInfo = document.getElementById('wholesale-info');

    if (hargaAsli > 0 && typeSelect.value) {
        let markup;
        if (markupPercentageInput.value !== '' && !isNaN(parseFloat(markupPercentageInput.value))) {
            markup = parseFloat(markupPercentageInput.value) / 100;
        } else {
            markup = typeSelect.value === 'makanan' ? 0.02 : 0.05;
        }
        const retailPrice = hargaAsli + (hargaAsli * markup);

        if (wholesalePrice > 0 && wholesalePrice < retailPrice) {
            const savingsPerUnit = retailPrice - wholesalePrice;
            const savingsPercent = ((savingsPerUnit / retailPrice) * 100).toFixed(1);
            const retailTotal = retailPrice * minQty;
            const wholesaleTotal = wholesalePrice * minQty;
            const totalSavings = retailTotal - wholesaleTotal;
            document.getElementById('savings-per-unit').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(savingsPerUnit);
            document.getElementById('savings-percent').textContent = savingsPercent + '%';
            document.getElementById('example-qty').textContent = minQty;
            document.getElementById('retail-total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(retailTotal);
            document.getElementById('wholesale-total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(wholesaleTotal);
            document.getElementById('total-savings').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalSavings);
            wholesaleInfo.style.display = 'block';
        } else {
            wholesaleInfo.style.display = 'none';
        }
    } else {
        wholesaleInfo.style.display = 'none';
    }
}

function calculateTebusMusahSavings() {
    const hargaAsli = parseFloat(document.getElementById('harga_asli').value) || 0;
    const typeSelect = document.getElementById('type');
    const markupPercentageInput = document.getElementById('markup_percentage'); 
    const tebusMusahPrice = parseFloat(document.getElementById('harga_tebus_murah').value) || 0;
    const minTotal = parseFloat(document.getElementById('min_total_tebus_murah').value) || 0;
    const tebusMusahInfo = document.getElementById('tebus-murah-info');

    if (hargaAsli > 0 && typeSelect.value) {
        let markup;
        if (markupPercentageInput.value !== '' && !isNaN(parseFloat(markupPercentageInput.value))) {
            markup = parseFloat(markupPercentageInput.value) / 100;
        } else {
            markup = typeSelect.value === 'makanan' ? 0.02 : 0.05;
        }
        const retailPrice = hargaAsli + (hargaAsli * markup);

        if (tebusMusahPrice > 0 && tebusMusahPrice < retailPrice && minTotal > 0) {
            const savingsPerUnit = retailPrice - tebusMusahPrice;
            const savingsPercent = ((savingsPerUnit / retailPrice) * 100).toFixed(1);
            document.getElementById('tebus-savings-per-unit').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(savingsPerUnit);
            document.getElementById('tebus-savings-percent').textContent = savingsPercent + '%';
            document.getElementById('min-total-display').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(minTotal);
            document.getElementById('normal-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(retailPrice);
            document.getElementById('tebus-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(tebusMusahPrice);
            tebusMusahInfo.style.display = 'block';
        } else {
            tebusMusahInfo.style.display = 'none';
        }
    } else {
        tebusMusahInfo.style.display = 'none';
    }
}

function toggleExistingBarcodeSection() {
    const checkbox = document.getElementById('use_existing_barcode');
    const existingBarcodeSection = document.getElementById('existing-barcode-section');
    const existingBarcodeInput = document.getElementById('existing_barcode');
    
    if (checkbox.checked) {
        existingBarcodeSection.style.display = 'block';
        existingBarcodeInput.focus();
        existingBarcodeInput.required = true;
    } else {
        existingBarcodeSection.style.display = 'none';
        existingBarcodeInput.required = false;
        existingBarcodeInput.value = '';
        document.getElementById('barcode-success-message').style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const existingBarcodeInput = document.getElementById('existing_barcode');
    
    if (existingBarcodeInput) {
        existingBarcodeInput.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                const successMessage = document.getElementById('barcode-success-message');
                const barcodeDisplay = document.getElementById('barcode-display');
                barcodeDisplay.textContent = this.value;
                successMessage.style.display = 'block';
            }
        });
    }
    
    toggleExistingBarcodeSection();
    toggleExpiredField();
    toggleWholesaleSection();
    toggleTebusMusahSection();
    calculateSellingPrice();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\SEMESTER 6\KERJA PRAKTEK PELINDO\project umkm\NEW\kasirku-main\resources\views/dashboard/goods/create.blade.php ENDPATH**/ ?>