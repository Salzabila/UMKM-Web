

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
        background: linear-gradient(135deg, #28a745, #20c997);
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

    .umkm-card-body {
        padding: 1.5rem; /* [RESPONSIVE] */
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 15px;
        border: 2px solid #e9ecef;
        padding: 12px 20px;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.9);
        font-size: 1rem;
    }

    .form-control:read-only, .form-select:disabled {
        background-color: #e9ecef;
        opacity: 1;
        cursor: not-allowed;
    }

    .form-control:focus, .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        background: white;
    }

    .btn-umkm, .btn-secondary-umkm {
        border-radius: 15px;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center; /* [RESPONSIVE] */
        gap: 8px;
    }

    .btn-umkm {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        color: white;
    }

    .btn-umkm:disabled {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        opacity: 0.65;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
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

    .tax-info {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 5px;
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
        <h1>💳 CHECKOUT PEMBAYARAN</h1>
        <p>Selesaikan transaksi untuk nota nomor <strong><?php echo e($no_nota); ?></strong></p>
    </div>

    <div class="row justify-content-center">
        
        <div class="col-xl-8 col-lg-10 col-md-12">
            <?php if(session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px;">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
             <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 15px;">
                    <strong>Terjadi Kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-wallet2"></i>
                        Formulir Pembayaran
                    </h3>
                </div>

                <div class="umkm-card-body">
                    <form method="post" action="/dashboard/cashiers/finishing">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($transaction->id); ?>">
                        <input type="hidden" name="no_nota" value="<?php echo e($no_nota); ?>">

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="tgl_transaksi" class="form-label">Tanggal Transaksi</label>
                                <input type="text" class="form-control" id="tgl_transaksi" value="<?php echo e($transaction->tgl_transaksi); ?>" readonly>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="admin_name" class="form-label">Administrator</label>
                                <input type="text" class="form-control" id="admin_name" value="<?php echo e($transaction->user->nama); ?>" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" name="nama_pembeli" id="payment_method" required onchange="calculateTotal()">
                                <option value="CASH" selected>CASH</option>
                                <option value="QRIS">QRIS</option>
                                <option value="P-Eats">P-Eats (Otomatis +12% pajak)</option>
                                <option value="TRANSFER">TRANSFER</option>
                            </select>
                            <div class="tax-info mt-2" id="tax_info" style="display: none;">
                                <i class="bi bi-info-circle-fill text-primary"></i> Pajak aplikasi 12% akan ditambahkan.
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="total_harga_display" class="form-label">Total Harga</label>
                                <input type="text" class="form-control" id="total_harga_display" readonly>
                                <input type="hidden" name="total_harga" id="total_harga">
                                <input type="hidden" id="original_total" value="<?php echo e($total_harga); ?>">
                            </div>
                           <div class="col-12 col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <input type="text" class="form-control" name="status" id="status" value="BELUM BAYAR" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="bayar_display" class="form-label">Jumlah Bayar</label>
                                <input type="text" class="form-control" id="bayar_display" placeholder="Masukkan Jumlah Bayar..." required>
                                <input type="hidden" name="bayar" id="bayar">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="kembalian_display" class="form-label">Kembalian</label>
                                <input type="text" class="form-control" id="kembalian_display" value="Rp 0" readonly>
                                <input type="hidden" name="kembalian" id="kembalian" value="0">
                            </div>
                        </div>

                        <div class="d-grid d-sm-flex justify-content-sm-end pt-4 gap-2">
                            <button class="btn-umkm" type="submit" id="btnSelesai">
                                <i class="bi bi-check2-circle"></i> Selesaikan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function formatNumber(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function unformatNumber(s) {
        return s.toString().replace(/[^0-9]/g, "");
    }

    function calculateChange() {
        const bayar = parseFloat(unformatNumber(document.getElementById('bayar').value)) || 0;
        const total = parseFloat(document.getElementById('total_harga').value) || 0;
        const kembalian = bayar - total;

        document.getElementById('kembalian').value = kembalian;
        document.getElementById('kembalian_display').value = 'Rp ' + formatNumber(kembalian);

        const statusField = document.getElementById("status");
        const btnSelesai = document.getElementById("btnSelesai");

        if (bayar >= total && total > 0) {
            statusField.value = "LUNAS";
            btnSelesai.disabled = false;
        } else {
            statusField.value = "BELUM BAYAR";
            btnSelesai.disabled = true;
        }
    }

    function calculateTotal() {
        const paymentMethod = document.getElementById("payment_method").value;
        const originalTotal = parseFloat(document.getElementById("original_total").value) || 0;
        const taxInfo = document.getElementById("tax_info");

        let finalTotal = originalTotal;

        if (paymentMethod === "P-Eats") {
            finalTotal = originalTotal * 1.12;
            taxInfo.style.display = "block";
        } else {
            taxInfo.style.display = "none";
        }

        finalTotal = Math.round(finalTotal);

        document.getElementById("total_harga").value = finalTotal;
        document.getElementById("total_harga_display").value = 'Rp ' + formatNumber(finalTotal);

        const bayarDisplayInput = document.getElementById('bayar_display');
        const bayarHiddenInput = document.getElementById('bayar');

        bayarDisplayInput.value = formatNumber(finalTotal);
        bayarHiddenInput.value = finalTotal;

        calculateChange();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const bayarDisplayInput = document.getElementById('bayar_display');
        const bayarHiddenInput = document.getElementById('bayar');

        const handleBayarInput = () => {
            let rawValue = unformatNumber(bayarDisplayInput.value);
            bayarHiddenInput.value = rawValue;

            let formattedValue = formatNumber(rawValue);
            if (bayarDisplayInput.value !== formattedValue) {
                bayarDisplayInput.value = formattedValue;
            }

            calculateChange();
        };

        bayarDisplayInput.addEventListener('input', handleBayarInput);

        calculateTotal();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang Pelindo\project\UMKM-Web\resources\views/dashboard/cashiers/checkout.blade.php ENDPATH**/ ?>