<?php $__env->startSection('container'); ?>
<style>
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
    background: linear-gradient(135deg, #28a745, #20c997); /* Warna Hijau */
    color: white;
    padding: 20px;
    border-radius: 20px 20px 0 0;
    position: relative;
    overflow: hidden;
}

.umkm-card-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 1.5rem; /* [RESPONSIVE] */
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

.btn-umkm, .btn-secondary-umkm, .btn-info-restock {
    border-radius: 15px;
    padding: 12px 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center; /* [RESPONSIVE]  */
    gap: 8px;
}

.btn-umkm {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-umkm:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
    color: white;
}

.btn-umkm:disabled,
.btn-umkm[disabled] {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
    color: white !important;
    opacity: 0.6;
    cursor: not-allowed;
    border: none;
}

.btn-secondary-umkm {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    border: none;
    color: white;
}

.btn-secondary-umkm:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
    color: white;
}

.btn-info-restock {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
    border: none;
    color: white;
    font-size: 0.9rem;
    padding: 10px 20px;
}

.btn-info-restock:hover {
    background: linear-gradient(135deg, #138496, #117a8b);
    color: white;
    transform: translateY(-1px);
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

.info-section {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
    border-radius: 15px;
    padding: 20px;
    border: 2px solid rgba(40, 167, 69, 0.2);
    margin-bottom: 20px;
}

.info-item {
    background: white;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 10px;
    border-left: 4px solid #28a745;
}

.required {
    color: #dc3545;
}

.calculation-info {
    background: rgba(40, 167, 69, 0.05);
    border-radius: 10px;
    padding: 15px;
    margin-top: 15px;
    border-left: 4px solid #28a745;
}

@media (min-width: 768px) {
    .page-title h1 {
        font-size: 2.5rem;
    }
    .card-body {
        padding: 2.5rem;
    }
}
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1>📦 RESTOCK BARANG</h1>
        <p>Tambah stok untuk barang: <strong><?php echo e($good->nama); ?></strong></p>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 col-md-12">
            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-plus-circle"></i>
                        Form Restock Barang
                    </h3>
                </div>

                <div class="card-body p-4">
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

                    <div class="info-section">
                        <h5 class="text-success mb-3">
                            <i class="bi bi-info-circle"></i>
                            Informasi Barang
                        </h5>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="info-item">
                                    <strong>Nama Barang:</strong><br>
                                    <span class="text-primary"><?php echo e($good->nama); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Supplier:</strong><br>
                                    <span class="text-muted"><?php echo e($good->category ? $good->category->nama : 'Tidak ada mitra'); ?></span>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="info-item">
                                    <strong>Stok Saat Ini:</strong><br>
                                    <span class="text-success fw-bold fs-4" id="current-stock"><?php echo e($good->stok); ?></span> unit
                                </div>
                                <div class="info-item">
                                    <strong>Harga Jual:</strong><br>
                                    <span class="text-success">Rp <?php echo e(number_format($good->harga, 0, ',', '.')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="post" action="/dashboard/restock/<?php echo e($good->id); ?>">
                        <?php echo method_field('put'); ?>
                        <?php echo csrf_field(); ?>

                        <div class="mb-4">
                            <label for="stok_tambahan" class="form-label">
                                <i class="bi bi-plus-circle text-success"></i>
                                Jumlah Stok Tambahan <span class="required">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control <?php $__errorArgs = ['stok_tambahan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="stok_tambahan" name="stok_tambahan"
                                       value="<?php echo e(old('stok_tambahan')); ?>"
                                       required min="1" placeholder="Masukkan jumlah stok yang akan ditambahkan..."
                                       oninput="calculateNewStock()">
                                <span class="input-group-text">unit</span>
                            </div>
                            <?php $__errorArgs = ['stok_tambahan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted mt-2 d-block">
                                <i class="bi bi-info-circle"></i>
                                Minimal 1 unit untuk menambah stok
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="keterangan" class="form-label">
                                <i class="bi bi-chat-text text-success"></i>
                                Keterangan (Opsional)
                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="keterangan" name="keterangan" rows="3"
                                      placeholder="Catatan tambahan untuk restock ini..."><?php echo e(old('keterangan')); ?></textarea>
                            <?php $__errorArgs = ['keterangan'];
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

                        <div id="calculation-info" class="calculation-info" style="display: none;">
                            <h6 class="text-success mb-2">
                                <i class="bi bi-calculator"></i> Perhitungan Stok
                            </h6>
                            <div class="row">
                                <div class="col-12 col-md-4 mb-2 mb-md-0">
                                    <p class="mb-1"><strong>Stok Lama:</strong> <span class="text-muted"><?php echo e($good->stok); ?> unit</span></p>
                                </div>
                                <div class="col-12 col-md-4 mb-2 mb-md-0">
                                    <p class="mb-1"><strong>Stok Tambahan:</strong> <span id="display-tambahan" class="text-info">0 unit</span></p>
                                </div>
                                <div class="col-12 col-md-4">
                                    <p class="mb-0"><strong>Total Stok Baru:</strong> <span id="display-total" class="text-success fw-bold"><?php echo e($good->stok); ?> unit</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid d-sm-flex justify-content-sm-between pt-3 mt-4 border-top gap-2">
                            <a href="/dashboard/restock" class="btn btn-secondary-umkm">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-umkm">
                                <i class="bi bi-plus-circle"></i>
                                Tambah Stok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function calculateNewStock() {
    const currentStock = <?php echo e($good->stok); ?>;
    const additionalStock = parseInt(document.getElementById('stok_tambahan').value) || 0;
    const calculationInfo = document.getElementById('calculation-info');

    if (additionalStock > 0) {
        const newTotal = currentStock + additionalStock;

        document.getElementById('display-tambahan').textContent = additionalStock + ' unit';
        document.getElementById('display-total').textContent = newTotal + ' unit';

        calculationInfo.style.display = 'block';
    } else {
        calculationInfo.style.display = 'none';
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LENOVO\Documents\SEMESTER 5\Magang\Gerai-umkm-mart-finalisasi-main\resources\views/dashboard/restock/edit.blade.php ENDPATH**/ ?>