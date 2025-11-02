

<?php $__env->startSection('container'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <style>
        body {
            background-color: #D3D3D3;
        }

        .search-results {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            position: absolute;
            z-index: 1000;
            width: calc(100% - 30px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .search-results-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .search-results-item:last-child {
            border-bottom: none;
        }

        .search-results-item:hover {
            background-color: #f8f9fa;
        }

        .search-results-item .product-name {
            font-weight: bold;
            color: #333;
        }

        .search-results-item .product-details {
            font-size: 0.85em;
            color: #666;
        }

        #qr-reader {
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
        }

        /* Hide camera section by default */
        #camera-scan-section {
            display: none;
        }

        /* Show only on mobile devices */
        .mobile-only {
            display: none;
        }

        .mobile-only.show-mobile {
            display: block;
        }

        /* Qty control buttons */
        .qty-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .qty-btn {
            width: 25px;
            height: 25px;
            border: none;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            opacity: 0.8;
        }

        .qty-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .qty-display {
            min-width: 30px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }

        .tebus-murah-item {
            transition: all 0.3s ease;
            border-left: 4px solid #dc3545;
        }

        .tebus-murah-item:hover {
            background-color: #fff5f5;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.15);
        }

        .tebus-murah-badge {
            background: linear-gradient(45deg, #dc3545, #e74c3c);
            color: white;
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: bold;
        }

        .savings-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            font-size: 0.7rem;
            padding: 1px 6px;
            border-radius: 8px;
        }
    </style>

    
    <div class="container-fluid py-3">
        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-3 px-3 mb-3 border-bottom"
            style="background: linear-gradient(90deg, #4e54c8, #8f94fb); border-radius: 10px;">
            <h1 class="h4 text-light mb-2 mb-md-0 text-center text-md-start">
                <i class="bi bi-cart-plus-fill me-2"></i> Buat Pesanan - Nota:
                <span class="text-warning fw-semibold"><?php echo e($no_nota); ?></span>
            </h1>
        </div>

        <?php if(session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div id="dynamic-alerts"></div>

        <div class="row g-4">
            
            <div class="col-12 col-lg-8 mb-4 mb-lg-0">
                <div class="card shadow-sm border-0 mb-4" style="background: white;">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-upc-scan" style="font-size: 3rem; color: #198754;"></i>
                        <h5 class="card-title mt-3 fw-bold">Sistem Kasir Siap Untuk Digunakan</h5>
                        <p class="card-text text-muted">Silakan pindai barcode produk menggunakan scanner untuk menampilkan detail produk.</p>
                    </div>
                </div>

                <div id="barcode-result" class="alert alert-success border-0 shadow-sm mb-4" style="display: none;">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h5 class="fw-bold text-success mb-3"><i class="bi bi-check-circle-fill"></i> Produk Ditemukan!</h5>
                            <div class="product-info bg-light p-3 rounded">
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <p class="mb-2"><strong>Kode:</strong> <span id="detected-barcode" class="badge bg-secondary fs-6"></span></p>
                                        <p class="mb-2"><strong>Nama:</strong> <span id="product-name" class="fw-bold text-dark fs-5"></span></p>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <p class="mb-2"><strong>Harga:</strong> <span id="product-price" class="fw-bold text-success fs-4"></span></p>
                                        <p class="mb-2"><strong>Stok:</strong> <span id="product-stock" class="badge bg-info fs-6"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mt-3 mt-lg-0">
                            <div class="d-grid gap-2">
                                <label for="scan-qty" class="form-label fw-semibold fs-5">Jumlah:</label>
                                <input type="number" class="form-control form-control-lg text-center fw-bold" id="scan-qty" value="1" min="1" max="999" style="font-size: 1.5rem;" onchange="updateBarcodePrice()" oninput="updateBarcodePrice()">
                                <div id="price-info" class="text-center mb-2">
                                    <div class="fw-bold fs-5">Total: <span id="total-price" class="text-success">Rp 0</span></div>
                                    <div id="price-type" class="small text-muted"></div>
                                </div>
                                <button type="button" id="add-scanned-item" class="btn btn-success btn-lg py-3">
                                    <i class="bi bi-plus-circle"></i> TAMBAH
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card shadow-sm border-0" style="background: white;">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0 fw-bold">
                            <i class="bi bi-pencil-square fs-3"></i> INPUT MANUAL
                        </h4>
                        <small class="opacity-75">Pilih produk jika barcode tidak tersedia</small>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <form id="manual-form">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="no_nota" value="<?php echo e($no_nota); ?>">
                            <input type="hidden" name="good_id" id="manual-good-id">

                            <div class="mb-4 position-relative">
                                <label for="product-search-input" class="form-label fs-5 fw-semibold text-dark"><i class="bi bi-box-seam text-primary"></i> Cari Produk</label>
                                <input type="text" class="form-control form-control-lg border-primary" id="product-search-input" placeholder="Ketik nama atau barcode produk..." autocomplete="off" style="font-size: 1.1rem;">
                                <div id="search-results" class="search-results" style="display: none;"></div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="qty" class="form-label fs-5 fw-semibold text-dark"><i class="bi bi-123 text-primary"></i> Jumlah</label>
                                    <input type="number" class="form-control form-control-lg border-primary text-center fw-bold" id="qty" name="qty" min="1" value="1" onchange="calculateSubtotal()" oninput="calculateSubtotal()" required style="font-size: 1.3rem;">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="subtotal" class="form-label fs-5 fw-semibold text-dark"><i class="bi bi-calculator text-primary"></i> Subtotal</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-primary text-white border-primary fw-bold">Rp</span>
                                        <input type="number" class="form-control border-primary text-end fw-bold" id="subtotal" name="subtotal" readonly style="font-size: 1.3rem; background-color: #f8f9fa;">
                                    </div>
                                </div>
                            </div>

                            <div id="manual-wholesale-info" class="mt-3 p-3 bg-warning bg-opacity-10 rounded border-start border-warning border-3" style="display: none;">
                                <h6 class="text-warning mb-2"><i class="bi bi-cart-plus"></i> Harga Grosir Aktif!</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small><strong>Harga per unit:</strong> <span id="manual-unit-price" class="text-success fw-bold"></span></small><br>
                                        <small><strong>Total hemat:</strong> <span id="manual-total-savings" class="text-success fw-bold"></span></small>
                                    </div>
                                    <div class="col-md-6">
                                        <small><strong>Diskon:</strong> <span id="manual-discount-percent" class="text-success fw-bold"></span></small><br>
                                        <small class="text-muted">Pembelian <span id="manual-wholesale-min-qty"></span>+ unit</small>
                                    </div>
                                </div>
                            </div>

                            <div id="manual-tebus-murah-info" class="mt-3 p-3 bg-danger bg-opacity-10 rounded border-start border-danger border-3" style="display: none;">
                                <h6 class="text-danger mb-2"><i class="bi bi-percent"></i> Harga Tebus Murah Aktif!</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small><strong>Harga per unit:</strong> <span id="manual-tebus-unit-price" class="text-danger fw-bold"></span></small><br>
                                        <small><strong>Total hemat:</strong> <span id="manual-tebus-total-savings" class="text-danger fw-bold"></span></small>
                                    </div>
                                    <div class="col-md-6">
                                        <small><strong>Diskon:</strong> <span id="manual-tebus-discount-percent" class="text-danger fw-bold"></span></small><br>
                                        <small class="text-muted">Total transaksi mencapai syarat</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg py-3" id="manual-submit-btn" disabled>
                                    <i class="bi bi-plus-circle"></i> TAMBAH KE PESANAN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0" id="orders-card" style="background: white;">
                    <div class="card-header bg-warning text-dark py-3">
                        <h4 class="mb-0 fw-bold">
                            <i class="bi bi-cart3 fs-3"></i> PESANAN SAAT INI
                            <span class="badge bg-dark ms-2 fs-6" id="order-count"><?php echo e($orders->count()); ?></span>
                        </h4>
                        <small>Daftar produk yang akan dibeli</small>
                    </div>
                    <div class="card-body p-0" id="orders-content">
                        <?php if($orders->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="fw-bold py-3 px-3">Produk</th>
                                            <th class="fw-bold py-3 text-center">Qty</th>
                                            <th class="fw-bold py-3 text-end px-3">Subtotal</th>
                                            <th class="fw-bold py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orders-tbody">
                                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr id="order-row-<?php echo e($order->id); ?>">
                                                <td class="py-3 px-3">
                                                    <div class="fw-semibold"><?php echo e($order->good ? $order->good->nama : 'Produk tidak ditemukan'); ?></div>
                                                    <small class="text-muted">@ Rp <?php echo e(number_format($order->price, 0, ',', '.')); ?></small>
                                                    <?php if($order->good && $order->good->is_grosir_active && $order->qty >= $order->good->min_qty_grosir): ?>
                                                        <br><small class="text-success fw-bold"><i class="bi bi-cart-plus"></i> Harga Grosir</small>
                                                    <?php endif; ?>
                                                    <?php if($order->good && $order->good->is_tebus_murah_active && $orders->sum('subtotal') >= $order->good->min_total_tebus_murah): ?>
                                                        <br><small class="text-danger fw-bold"><i class="bi bi-percent"></i> Harga Tebus Murah</small>
                                                    <?php endif; ?>
                                                    <?php if(!$order->good): ?>
                                                        <br><small class="text-warning fw-bold"><i class="bi bi-exclamation-triangle"></i> Produk telah dihapus</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="py-3 text-center">
                                                    <div class="qty-controls">
                                                        <button type="button" class="qty-btn btn btn-danger btn-sm" onclick="updateQty(<?php echo e($order->id); ?>, <?php echo e($order->qty - 1); ?>)" <?php echo e($order->qty <= 1 ? 'disabled' : ''); ?>>
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                        <span class="qty-display badge bg-secondary fs-6" id="qty-<?php echo e($order->id); ?>"><?php echo e($order->qty); ?></span>
                                                        <button type="button" class="qty-btn btn btn-success btn-sm" onclick="updateQty(<?php echo e($order->id); ?>, <?php echo e($order->qty + 1); ?>)">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="py-3 text-end px-3 fw-bold text-success" id="subtotal-<?php echo e($order->id); ?>">
                                                    Rp <?php echo e(number_format($order->subtotal, 0, ',', '.')); ?>

                                                </td>
                                                <td class="py-3 text-center">
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteOrderItem(<?php echo e($order->id); ?>, '<?php echo e($order->good ? addslashes($order->good->nama) : 'Produk tidak ditemukan'); ?>')" title="Hapus item">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-4 bg-light border-top">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 fw-bold">TOTAL BELANJA:</h5>
                                    <h4 class="mb-0 fw-bold text-success" id="total-amount">
                                        Rp <?php echo e(number_format($orders->sum('subtotal'), 0, ',', '.')); ?>

                                    </h4>
                                </div>
                                <?php
                                    $totalTransaction = $orders->sum('subtotal');
                                    $tebusMusahProducts = $goods->filter(function ($good) use ($totalTransaction) {
                                        return $good->is_tebus_murah_active && $totalTransaction >= $good->min_total_tebus_murah;
                                    });
                                ?>
                                <?php if($tebusMusahProducts->count() > 0): ?>
                                    <div class="alert alert-danger border-0 shadow-sm mb-3">
                                        <h6 class="fw-bold text-danger mb-2"><i class="bi bi-percent"></i> TEBUS MURAH AKTIF!</h6>
                                        <small class="text-muted"><?php echo e($tebusMusahProducts->count()); ?> produk memenuhi syarat tebus murah.</small>
                                    </div>
                                <?php endif; ?>
                                <form method="post" action="/dashboard/cashiers/checkout">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="no_nota" value="<?php echo e($no_nota); ?>">
                                    <input type="hidden" name="total_harga" value="<?php echo e($orders->sum('subtotal')); ?>" id="checkout-total">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-success btn-lg py-3 fw-bold">
                                            <i class="bi bi-credit-card"></i> LANJUT KE PEMBAYARAN
                                        </button>
                                    </div>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5" id="empty-orders">
                                <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                                <h5 class="mt-3 text-muted">Belum Ada Pesanan</h5>
                                <p class="text-muted">Scan barcode atau pilih produk untuk memulai</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php
                    $totalTransaction = $orders->sum('subtotal');
                    $tebusMusahProducts = $goods->filter(function ($good) use ($totalTransaction) {
                        return $good->is_tebus_murah_active &&
                               $totalTransaction >= $good->min_total_tebus_murah &&
                               $good->stok > 0;
                    });
                ?>
                <?php if($tebusMusahProducts->count() > 0): ?>
                    <div class="card shadow-sm border-0 mt-4" id="tebus-murah-card" style="background: white;">
                        <div class="card-header text-white py-3" style="background: linear-gradient(45deg, #dc3545, #e74c3c);">
                            <h4 class="mb-0 fw-bold">
                                <i class="bi bi-percent fs-3"></i> BARANG TEBUS MURAH
                                <span class="badge bg-light text-dark ms-2 fs-6"><?php echo e($tebusMusahProducts->count()); ?></span>
                            </h4>
                            <small class="opacity-90">Produk yang bisa ditebus murah dengan total belanja saat ini</small>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="fw-bold py-3 px-3">Produk</th>
                                            <th class="fw-bold py-3 text-center">Harga</th>
                                            <th class="fw-bold py-3 text-center">Hemat</th>
                                            <th class="fw-bold py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $tebusMusahProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $savings = $product->harga - $product->harga_tebus_murah;
                                                $savingsPercent = round(($savings / $product->harga) * 100, 1);
                                            ?>
                                            <tr class="tebus-murah-item">
                                                <td class="py-3 px-3">
                                                    <div class="fw-semibold"><?php echo e($product->nama); ?></div>
                                                    <small class="text-muted">Stok: <?php echo e($product->stok); ?> pcs</small>
                                                    <br><span class="tebus-murah-badge">TEBUS MURAH</span>
                                                </td>
                                                <td class="py-3 text-center">
                                                    <div class="fw-bold text-danger fs-5">
                                                        Rp <?php echo e(number_format($product->harga_tebus_murah, 0, ',', '.')); ?>

                                                    </div>
                                                    <small class="text-muted text-decoration-line-through">
                                                        Rp <?php echo e(number_format($product->harga, 0, ',', '.')); ?>

                                                    </small>
                                                </td>
                                                <td class="py-3 text-center">
                                                    <div class="savings-badge">
                                                        <?php echo e($savingsPercent); ?>%
                                                    </div>
                                                    <small class="text-success fw-bold d-block mt-1">
                                                        Rp <?php echo e(number_format($savings, 0, ',', '.')); ?>

                                                    </small>
                                                </td>
                                                <td class="py-3 text-center">
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm fw-bold"
                                                            onclick="addTebusMusahToCart(<?php echo e($product->id); ?>, '<?php echo e(addslashes($product->nama)); ?>', <?php echo e($product->harga_tebus_murah); ?>, <?php echo e($product->stok); ?>)"
                                                            title="Tambah ke pesanan"
                                                            id="tebus-btn-<?php echo e($product->id); ?>">
                                                        <i class="bi bi-plus-circle"></i> TAMBAH
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 bg-light border-top">
                                <div class="text-center">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i>
                                        Produk di atas dapat dibeli dengan harga tebus murah karena total belanja sudah mencapai syarat minimum.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let currentProduct = null;
        let currentTransactionTotal = <?php echo e($orders->sum('subtotal')); ?>;
        let manualSelectedProduct = null;
        let html5QrcodeScanner;
        let isScanning = false;
        let isMobileDevice = false;


        function detectMobileDevice() {
            const hasTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
            const hasCamera = navigator.mediaDevices && navigator.mediaDevices.getUserMedia;
            const userAgent = navigator.userAgent.toLowerCase();
            const mobileKeywords = ['mobile', 'android', 'iphone', 'ipad', 'tablet', 'blackberry', 'windows phone'];
            const isMobileUserAgent = mobileKeywords.some(keyword => userAgent.includes(keyword));
            isMobileDevice = (hasTouch && hasCamera) || isMobileUserAgent;
            
            if (isMobileDevice) {
                const mobileElements = document.querySelectorAll('.mobile-only');
                mobileElements.forEach(element => {
                    element.classList.add('show-mobile');
                });
                const cameraScanSection = document.getElementById('camera-scan-section');
                if (cameraScanSection) {
                    cameraScanSection.style.display = 'block';
                }
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            // [PERUBAHAN] Scan dari kamera HP juga akan memanggil processBarcode
            processBarcode(decodedText);
            stopScanner();
        }

        function onScanError(errorMessage) {}

        function startScanner() {
            if (!isMobileDevice) {
                showAlert('warning', '⚠️ Fitur kamera hanya tersedia di perangkat mobile.');
                return;
            }
            const isSecureContext = window.location.protocol === 'https:' || ['localhost', '127.0.0.1'].includes(window.location.hostname);
            if (!isSecureContext) {
                showAlert('danger', '❌ Akses kamera butuh HTTPS atau localhost.');
                return;
            }
            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5Qrcode("qr-reader");
            }
            html5QrcodeScanner.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, onScanSuccess, onScanError);
            isScanning = true;
            document.getElementById('start-scan-btn').style.display = 'none';
            document.getElementById('stop-scan-btn').style.display = 'block';
            showAlert('info', 'Kamera aktif, siap memindai barcode.');
        }

        function stopScanner() {
            if (html5QrcodeScanner && isScanning) {
                html5QrcodeScanner.stop().then(() => {
                    isScanning = false;
                    document.getElementById('start-scan-btn').style.display = 'block';
                    document.getElementById('stop-scan-btn').style.display = 'none';
                }).catch(err => console.error("Gagal stop scanner.", err));
            }
        }

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        function processBarcode(barcode) {
            barcode = barcode.trim();
            if (!barcode) return;

            fetch(`/dashboard/cashier/search-goods?query=${encodeURIComponent(barcode)}`)
            .then(response => response.json())
            .then(products => {
                let productData = products.find(p => p.barcode && p.barcode.toLowerCase() === barcode.toLowerCase()) || (products.length > 0 ? products[0] : null);

                if (productData) {
                    currentProduct = productData;
                    document.getElementById('barcode-result').style.display = 'block';
                    document.getElementById('detected-barcode').textContent = productData.barcode || 'N/A';
                    document.getElementById('product-name').textContent = productData.nama;
                    document.getElementById('product-price').textContent = formatRupiah(productData.harga);
                    document.getElementById('product-stock').textContent = productData.stok + ' pcs';
                    document.getElementById('scan-qty').value = 1;
                    
                    setTimeout(() => {
                        updateBarcodePrice();
                    }, 100);

                    showAlert('success', `✅ Produk ditemukan: ${productData.nama}`);
                } else {
                    document.getElementById('barcode-result').style.display = 'none';
                    currentProduct = null;
                    showAlert('danger', `❌ Produk dengan barcode "${barcode}" tidak ditemukan!`);
                }
            }).catch(error => showAlert('danger', '❌ Terjadi kesalahan saat mencari produk.'));
        }

        function updateBarcodePrice() {
            if (!currentProduct) return;
            const qtyElement = document.getElementById('scan-qty');
            const totalPriceElement = document.getElementById('total-price');
            const priceTypeElement = document.getElementById('price-type');
            if (!qtyElement || !totalPriceElement || !priceTypeElement) return;

            const qty = parseInt(qtyElement.value) || 1;
            let unitPrice = parseFloat(currentProduct.harga) || 0;
            let priceType = 'Harga Normal';

            const isTebusMurahEligible = currentProduct.is_tebus_murah_active &&
                                        currentTransactionTotal >= currentProduct.min_total_tebus_murah &&
                                        currentProduct.harga_tebus_murah > 0;
            const isWholesaleEligible = currentProduct.is_grosir_active &&
                                        qty >= currentProduct.min_qty_grosir &&
                                        currentProduct.harga_grosir > 0;

            if (isTebusMurahEligible) {
                unitPrice = parseFloat(currentProduct.harga_tebus_murah) || unitPrice;
                priceType = 'Harga Tebus Murah';
            } else if (isWholesaleEligible) {
                unitPrice = parseFloat(currentProduct.harga_grosir) || unitPrice;
                priceType = 'Harga Grosir';
            }
            const totalPrice = unitPrice * qty;
            totalPriceElement.textContent = formatRupiah(totalPrice);
            priceTypeElement.textContent = priceType;
            priceTypeElement.className = isTebusMurahEligible ? 'small text-danger fw-bold' :
                                          isWholesaleEligible ? 'small text-warning fw-bold' :
                                          'small text-muted';
        }

        function calculateSubtotal() {
            const qtyInput = document.getElementById('qty');
            const subtotalInput = document.getElementById('subtotal');
            const manualSubmitBtn = document.getElementById('manual-submit-btn');
            const wholesaleInfo = document.getElementById('manual-wholesale-info');
            const tebusMusahInfo = document.getElementById('manual-tebus-murah-info');
            wholesaleInfo.style.display = 'none';
            tebusMusahInfo.style.display = 'none';
            subtotalInput.value = '';
            manualSubmitBtn.disabled = true;
            if (!manualSelectedProduct || !qtyInput.value) return;

            const product = manualSelectedProduct;
            const retailPrice = product.harga || 0;
            const qty = parseInt(qtyInput.value) || 0;
            const stock = product.stok || 0;
            
            if (qty > stock) {
                showAlert('warning', `Barang yang dibeli melebihi stok produk yang tersedia, Mohon Dicek Kembali. (Stok saat ini: ${stock})`);
                qtyInput.value = stock;
                calculateSubtotal(); 
                return;
            }

            let unitPrice = retailPrice;
            const isTebusMurahEligible = product.is_tebus_murah_active && currentTransactionTotal >= product.min_total_tebus_murah && product.harga_tebus_murah > 0;
            const isWholesaleEligible = product.is_grosir_active && qty >= product.min_qty_grosir && product.harga_grosir > 0;

            if (isTebusMurahEligible) {
                unitPrice = product.harga_tebus_murah;
            } else if (isWholesaleEligible) {
                unitPrice = product.harga_grosir;
            }
            if (isWholesaleEligible) {
                const savings = (retailPrice - product.harga_grosir) * qty;
                document.getElementById('manual-unit-price').textContent = formatRupiah(product.harga_grosir);
                document.getElementById('manual-total-savings').textContent = formatRupiah(savings);
                document.getElementById('manual-discount-percent').textContent = ((retailPrice - product.harga_grosir) / retailPrice * 100).toFixed(1) + '%';
                document.getElementById('manual-wholesale-min-qty').textContent = product.min_qty_grosir;
                wholesaleInfo.style.display = 'block';
            }
            if (isTebusMurahEligible) {
                const savings = (retailPrice - product.harga_tebus_murah) * qty;
                document.getElementById('manual-tebus-unit-price').textContent = formatRupiah(product.harga_tebus_murah);
                document.getElementById('manual-tebus-total-savings').textContent = formatRupiah(savings);
                document.getElementById('manual-tebus-discount-percent').textContent = ((retailPrice - product.harga_tebus_murah) / retailPrice * 100).toFixed(1) + '%';
                tebusMusahInfo.style.display = 'block';
            }
            subtotalInput.value = unitPrice * qty;
            manualSubmitBtn.disabled = false;
        }

        function showAlert(type, message) {
            const alertContainer = document.getElementById('dynamic-alerts');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
            alertDiv.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}"></i> ${message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
            alertContainer.appendChild(alertDiv);
            setTimeout(() => { alertDiv.remove(); }, 5000);
        }

        function updateQty(orderId, newQty) {
            if (newQty < 1) return;
            const orderRow = document.getElementById(`order-row-${orderId}`);
            const minusBtn = orderRow.querySelector('.qty-controls .btn-danger');
            const plusBtn = orderRow.querySelector('.qty-controls .btn-success');
            if (minusBtn) minusBtn.disabled = true;
            if (plusBtn) plusBtn.disabled = true;

            fetch(`/dashboard/cashier/update-qty/${orderId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ qty: newQty })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert('danger', data.message);
                    if (minusBtn) minusBtn.disabled = newQty <= 1;
                    if (plusBtn) plusBtn.disabled = false;
                }
            })
            .catch(error => {
                showAlert('danger', 'Terjadi kesalahan jaringan');
                if (minusBtn) minusBtn.disabled = newQty <= 1;
                if (plusBtn) plusBtn.disabled = false;
            });
        }

        function addTebusMusahToCart(productId, productName, tebusMusahPrice, stock) {
            if (stock <= 0) {
                showAlert('warning', '⚠️ Stok produk habis!');
                return;
            }
            const button = document.getElementById(`tebus-btn-${productId}`);
            if (!button) {
                showAlert('danger', '❌ Button tidak ditemukan!');
                return;
            }
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-hourglass-split"></i> Menambah...';
            button.disabled = true;
            const formData = new FormData();
            formData.append('no_nota', '<?php echo e($no_nota); ?>');
            formData.append('good_id', productId);
            formData.append('qty', 1);
            formData.append('subtotal', tebusMusahPrice);
            formData.append('is_tebus_murah', 'true');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/dashboard/cashier/storeorder', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(responseText => {
                if (responseText.includes('success') || responseText.includes('berhasil')) {
                    showAlert('success', `✅ "${productName}" berhasil ditambahkan dengan harga tebus murah!`);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    throw new Error('Response tidak mengindikasikan sukses');
                }
            })
            .catch(error => {
                showAlert('danger', '❌ Terjadi kesalahan: ' + error.message);
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        // --- Event Listeners ---
        document.getElementById('manual-form').addEventListener('submit', function (e) {
            e.preventDefault();
            if (!manualSelectedProduct) {
                showAlert('warning', '⚠️ Silakan pilih produk dari pencarian.');
                return;
            }
            const submitBtn = document.getElementById('manual-submit-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menambahkan...';
            submitBtn.disabled = true;

            const formData = new FormData(this);
            formData.set('good_id', manualSelectedProduct.id);
            formData.set('subtotal', document.getElementById('subtotal').value);

            fetch('/dashboard/cashier/storeorder', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            }).then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.text();
            }).then(() => {
                showAlert('success', '✅ Pesanan berhasil ditambahkan!');
                setTimeout(() => location.reload(), 1000);
            }).catch(() => showAlert('danger', '❌ Terjadi kesalahan.')).finally(() => submitBtn.innerHTML = originalText);
        });

        document.getElementById('add-scanned-item').addEventListener('click', function () {
            const qty = document.getElementById('scan-qty').value;
            if (!currentProduct) {
                showAlert('warning', '⚠️ Cari produk via barcode dulu!');
                return;
            }
            if (!qty || qty < 1) {
                showAlert('warning', '⚠️ Jumlah minimal 1!');
                return;
            }
            const submitBtn = this;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menambahkan...';
            submitBtn.disabled = true;
            const formData = new FormData();
            formData.append('barcode', currentProduct.barcode);
            formData.append('qty', parseInt(qty));
            formData.append('no_nota', '<?php echo e($no_nota); ?>');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/dashboard/cashier/store-barcode', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (!response.ok) return response.json().then(err => { throw err; });
                return response.json();
            }).then(data => {
                if(data.success) {
                    showAlert('success', '✅ Produk ditambahkan ke pesanan!');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert('danger', `❌ ${data.message || 'Gagal menambahkan produk.'}`);
                }
            }).catch(error => showAlert('danger', '❌ ' + (error.message || 'Error jaringan.'))).finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        const productSearchInput = document.getElementById('product-search-input');
        const searchResultsDiv = document.getElementById('search-results');
        let searchTimeout;
        productSearchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            if (query.length < 2) { searchResultsDiv.style.display = 'none'; return; }
            searchTimeout = setTimeout(() => {
                fetch(`/dashboard/cashier/search-goods?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(products => {
                    searchResultsDiv.innerHTML = '';
                    if (products.length > 0) {
                        products.forEach(product => {
                            const itemDiv = document.createElement('div');
                            itemDiv.className = 'search-results-item';
                            itemDiv.innerHTML = `<div class="product-name">${product.nama}</div><div class="product-details">Harga: ${formatRupiah(product.harga)} | Stok: ${product.stok}</div>`;
                            itemDiv.addEventListener('click', () => {
                                manualSelectedProduct = product;
                                document.getElementById('manual-good-id').value = product.id;
                                productSearchInput.value = product.nama;
                                searchResultsDiv.style.display = 'none';
                                document.getElementById('qty').value = 1;
                                calculateSubtotal();
                                document.getElementById('qty').focus();
                            });
                            searchResultsDiv.appendChild(itemDiv);
                        });
                        searchResultsDiv.style.display = 'block';
                    } else {
                        searchResultsDiv.style.display = 'none';
                    }
                });
            }, 300);
        });

        document.addEventListener('click', e => { if (!productSearchInput.contains(e.target) && !searchResultsDiv.contains(e.target)) { searchResultsDiv.style.display = 'none'; } });

        document.addEventListener('DOMContentLoaded', () => {
            detectMobileDevice();

            if (isMobileDevice) {
                document.getElementById('start-scan-btn').addEventListener('click', startScanner);
                document.getElementById('stop-scan-btn').addEventListener('click', stopScanner);
                window.addEventListener('beforeunload', () => stopScanner());
            }

            // --- [PERUBAHAN] LISTENER UNTUK SCANNER BARCODE GLOBAL ---
            let barcodeBuffer = '';
            let lastInputTime = Date.now();
            document.addEventListener('keydown', function(e) {
                const currentTime = Date.now();
                if (currentTime - lastInputTime > 50) {
                    barcodeBuffer = '';
                }
                if (e.key === 'Enter') {
                    if (barcodeBuffer.length > 3) {
                        e.preventDefault();
                        const activeElement = document.activeElement.tagName;
                        if (activeElement !== 'INPUT' && activeElement !== 'TEXTAREA') {
                            processBarcode(barcodeBuffer);
                        }
                    }
                    barcodeBuffer = '';
                } else {
                    if (e.key.length === 1) {
                        barcodeBuffer += e.key;
                    }
                }
                lastInputTime = currentTime;
            });
        });

        function deleteOrderItem(orderId, productName) {
            if (confirm(`Yakin ingin menghapus "${productName}" dari pesanan?`)) {
                const button = document.querySelector(`button[onclick="deleteOrderItem(${orderId}, '${productName.replace(/'/g, "\\'")}')"]`);
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="bi bi-hourglass-split"></i>';
                button.disabled = true;

                fetch(`/dashboard/cashier/deleteorder/${orderId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert('danger', data.message);
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    showAlert('danger', 'Terjadi kesalahan jaringan');
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
            }
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Kuliah\Magang Pelindo\project\UMKM-Web\resources\views/dashboard/cashiers/order/create.blade.php ENDPATH**/ ?>