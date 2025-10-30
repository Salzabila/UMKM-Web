@extends('dashboard.layouts.main')

@section('container')
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
        color: White;
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
        padding: 1.5rem;
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
        border-color: (135deg, #28a745, #20c997);
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        background: white;
        transform: translateY(-1px);
    }

    .form-control:read-only {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
        cursor: not-allowed;
    }

    .input-group-text {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: White;
        border: none;
        border-radius: 15px 0 0 15px;
        font-weight: 600;
    }

    .input-group .form-control {
        border-radius: 0 15px 15px 0;
        border-left: none;
    }

    .btn-warning-umkm, .btn-secondary-umkm, .btn-outline-umkm, .btn-info-restock {
        border-radius: 15px;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-warning-umkm {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        color: #000;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-warning-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        color: #000;
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

    .btn-outline-umkm {
        background: transparent;
        border: 2px solid #28a745;
        color: #28a745;
        font-size: 0.9rem;
        padding: 10px 20px;
    }

    .btn-outline-umkm:hover {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        transform: translateY(-1px);
    }

    .btn-info-restock {
        background: linear-gradient(135deg, #17a2b8, #138496);
        border: none;
        color: white;
        font-size: 0.9rem;
        padding: 10px 20px;
    }

    .btn-info-restock:hover {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        transform: translateY(-1px);
    }

    .barcode-section {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
        border-radius: 15px;
        padding: 20px;
        border: 2px solid rgba(40, 167, 69, 0.2);
        margin-bottom: 20px;
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

    .expired-section, .wholesale-fields, .tebus-murah-fields {
        display: none;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 10px;
        padding: 15px;
        margin-top: 15px;
    }
    .expired-section { background: rgba(40, 167, 69, 0.1); border: 2px solid rgba(40, 167, 69, 0.3); }

    .expired-section.show, .wholesale-fields.show, .tebus-murah-fields.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    .wholesale-section, .tebus-murah-section {
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .wholesale-section { background: linear-gradient(135deg, rgba(255, 87, 34, 0.1), rgba(255, 152, 0, 0.1)); border: 2px solid rgba(255, 87, 34, 0.2); }
    .tebus-murah-section { background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(200, 35, 51, 0.1)); border: 2px solid rgba(220, 53, 69, 0.2); }

    .form-check-input:checked { background-color: #ff5722; border-color: #ff5722; }
    .form-check-input:focus { border-color: #ff5722; box-shadow: 0 0 0 0.25rem rgba(255, 87, 34, 0.25); }

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

    .bi-pencil-square,
    .bi-building,
    .text-warning,
    .bi-calendar-event,
    .bi-box,
    .bi-tags,
    .expired-section,
    .bi-collection,
    .bi-calendar-x {
        Color : #28a745 !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1>✏️ EDIT BARANG</h1>
        <p>Perbarui informasi barang di inventori GERAI UMKM MART</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 col-md-12">
            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-pencil-square"></i>
                        Form Edit Barang
                    </h3>
                </div>

                <div class="umkm-card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="post" action="/dashboard/goods/{{ $good->id }}" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <input type="hidden" name="id" value="{{ $good->id }}">

                        <div class="form-section">
                            <div class="section-title">
                                <i class="bi bi-info-circle"></i>
                                Informasi Dasar
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="category_id" class="form-label">
                                        <i class="bi bi-building text-warning"></i>
                                        Supplier <span class="required">*</span>
                                    </label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" id="category_id" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $good->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="tgl_masuk" class="form-label">
                                        <i class="bi bi-calendar-event text-warning"></i>
                                        Tanggal Masuk <span class="required">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('tgl_masuk') is-invalid @enderror" id="tgl_masuk" name="tgl_masuk" value="{{ old('tgl_masuk', $good->tgl_masuk->format('Y-m-d')) }}" required>
                                    @error('tgl_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">
                                    <i class="bi bi-box text-warning"></i>
                                    Nama Barang <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $good->nama) }}" required autofocus placeholder="Masukkan nama barang...">
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title">
                                <i class="bi bi-tags"></i>
                                Jenis & Masa Berlaku
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">
                                    <i class="bi bi-collection text-warning"></i>
                                    Jenis Barang <span class="required">*</span>
                                </label>
                                <select class="form-select @error('type') is-invalid @enderror" name="type" id="type" required onchange="toggleExpiredField(); calculateSellingPrice();">
                                    <option value="">-- Pilih Jenis Barang --</option>
                                    <option value="makanan" {{ old('type', $good->type) == 'makanan' ? 'selected' : '' }}>Makanan & Minuman</option>
                                    <option value="non_makanan" {{ old('type', $good->type) == 'non_makanan' ? 'selected' : '' }}>Non Makanan & Minuman</option>
                                    <option value="lainnya" {{ old('type', $good->type) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    <option value="handycraft" {{ old('type', $good->type) == 'handycraft' ? 'selected' : '' }}>Handycraft</option>
                                    <option value="fashion" {{ old('type', $good->type) == 'fashion' ? 'selected' : '' }}>Fashion</option>
                                </select>
                                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div id="expired-section" class="expired-section">
                                <label for="expired_date" class="form-label">
                                    <i class="bi bi-calendar-x text-warning"></i>
                                    Tanggal Expired <span class="required">*</span>
                                </label>
                                <input type="date" class="form-control @error('expired_date') is-invalid @enderror" id="expired_date" name="expired_date" value="{{ old('expired_date', $good->expired_date ? $good->expired_date->format('Y-m-d') : '') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                @error('expired_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                                    <label for="harga_asli" class="form-label">
                                        <i class="bi bi-cash text-warning"></i>
                                        Harga Asli <span class="required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('harga_asli') is-invalid @enderror" id="harga_asli" name="harga_asli" value="{{ old('harga_asli', $good->harga_asli) }}" required min="0" onchange="calculateSellingPrice();" oninput="calculateSellingPrice();">
                                    </div>
                                    @error('harga_asli')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted mt-2 d-block">Harga asli sebelum markup</small>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="stok" class="form-label">
                                        <i class="bi bi-boxes text-warning"></i>
                                        Stok Saat Ini
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="stok" name="stok" value="{{ $good->stok }}" readonly>
                                        <span class="input-group-text">unit</span>
                                    </div>
                                    <div class="stock-readonly-info">
                                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-2">
                                            <small class="text-info text-center text-sm-start"><i class="bi bi-info-circle"></i> Stok tidak dapat diubah di sini</small>
                                            <a href="/dashboard/restock/{{ $good->id }}/edit" class="btn-info-restock w-100 w-sm-auto"><i class="bi bi-plus-circle"></i> Restock</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="markup_percentage" class="form-label">
                                    <i class="bi bi-percent text-warning"></i>
                                    Persentase Markup (Opsional)
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('markup_percentage') is-invalid @enderror" id="markup_percentage" name="markup_percentage" value="{{ old('markup_percentage', $good->markup_percentage) }}" min="0" max="100" step="0.01" placeholder="Contoh: 5 (untuk 5%)" oninput="calculateSellingPrice();">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('markup_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-2 d-block">Biarkan kosong untuk markup otomatis (Makanan: 2%, Non-Makanan: 5%)</small>
                            </div>
                            <div id="price-info" class="price-info">
                                <h6 class="text-info mb-2"><i class="bi bi-calculator"></i> Informasi Harga Jual</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Harga Asli:</strong> <span id="display-harga-asli" class="text-muted">Rp {{ number_format($good->harga_asli ?? 0, 0, ',', '.') }}</span></p>
                                        <p class="mb-1"><strong>Markup:</strong> <span id="markup-percent" class="text-info">
                                            @if($good->markup_percentage !== null)
                                                {{ number_format($good->markup_percentage, 0) }}% (Manual)
                                            @else
                                                {{ $good->type === 'makanan' ? '2%' : '5%' }} (Otomatis)
                                            @endif
                                        </span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Harga Jual:</strong> <span id="display-harga-jual" class="text-success fw-bold">Rp {{ number_format($good->harga ?? 0, 0, ',', '.') }}</span></p>
                                        <p class="mb-0"><strong>Selisih:</strong> <span id="display-profit" class="text-success">Rp {{ number_format(($good->harga ?? 0) - ($good->harga_asli ?? 0), 0, ',', '.') }}</span></p> {{-- [UBAH] Keuntungan menjadi Selisih --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wholesale-section">
                            <div class="section-title" style="color: #ff5722;"><i class="bi bi-tags-fill"></i> Pengaturan Grosir</div>
                            <div class="wholesale-toggle">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_grosir_active" name="is_grosir_active" value="1" {{ old('is_grosir_active', $good->is_grosir_active) ? 'checked' : '' }} onchange="toggleWholesaleFields()">
                                    <label class="form-check-label fw-bold" for="is_grosir_active"><i class="bi bi-shop"></i> Aktifkan Harga Grosir</label>
                                </div>
                                <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle"></i> Aktifkan untuk memberikan harga khusus pembelian dalam jumlah besar</small>
                            </div>
                            <div id="wholesale-fields" class="wholesale-fields {{ old('is_grosir_active', $good->is_grosir_active) ? 'show' : '' }}">
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="min_qty_grosir" class="form-label"><i class="bi bi-box-seam" style="color: #ff5722;"></i> Minimal Pembelian Grosir <span class="required">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control @error('min_qty_grosir') is-invalid @enderror" id="min_qty_grosir" name="min_qty_grosir" value="{{ old('min_qty_grosir', $good->min_qty_grosir) }}" min="2" placeholder="Contoh: 10">
                                            <span class="input-group-text">unit</span>
                                        </div>
                                        @error('min_qty_grosir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <small class="text-muted">Minimal 2 unit untuk harga grosir</small>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="harga_grosir" class="form-label"><i class="bi bi-cash-coin" style="color: #ff5722;"></i> Harga Grosir <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control @error('harga_grosir') is-invalid @enderror" id="harga_grosir" name="harga_grosir" value="{{ old('harga_grosir', $good->harga_grosir) }}" min="0" placeholder="Harga lebih murah dari harga eceran">
                                        </div>
                                        @error('harga_grosir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <small class="text-muted">Harga harus lebih kecil dari harga eceran</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tebus-murah-section">
                            <div class="section-title" style="color: #dc3545;"><i class="bi bi-tag-fill"></i> Pengaturan Tebus Murah</div>
                            <div class="tebus-murah-toggle">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_tebus_murah_active" name="is_tebus_murah_active" value="1" {{ old('is_tebus_murah_active', $good->is_tebus_murah_active) ? 'checked' : '' }} onchange="toggleTebusMusahFields()" style="background-color: #dc3545; border-color: #dc3545;">
                                    <label class="form-check-label fw-bold" for="is_tebus_murah_active"><i class="bi bi-percent"></i> Aktifkan Harga Tebus Murah</label>
                                </div>
                                <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle"></i> Aktifkan untuk memberikan harga khusus ketika total pembelian mencapai nilai tertentu</small>
                            </div>
                            <div id="tebus-murah-fields" class="tebus-murah-fields {{ old('is_tebus_murah_active', $good->is_tebus_murah_active) ? 'show' : '' }}">
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="min_total_tebus_murah" class="form-label"><i class="bi bi-cash-stack" style="color: #dc3545;"></i> Minimal Pembelian <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white;">Rp</span>
                                            <input type="number" class="form-control @error('min_total_tebus_murah') is-invalid @enderror" id="min_total_tebus_murah" name="min_total_tebus_murah" value="{{ old('min_total_tebus_murah', $good->min_total_tebus_murah) }}" min="0" placeholder="Contoh: 100000">
                                        </div>
                                        @error('min_total_tebus_murah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <small class="text-muted">Total nilai transaksi minimal</small>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="harga_tebus_murah" class="form-label"><i class="bi bi-tag" style="color: #dc3545;"></i> Harga Tebus Murah <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white;">Rp</span>
                                            <input type="number" class="form-control @error('harga_tebus_murah') is-invalid @enderror" id="harga_tebus_murah" name="harga_tebus_murah" value="{{ old('harga_tebus_murah', $good->harga_tebus_murah) }}" min="0" placeholder="Harga lebih murah dari harga normal">
                                        </div>
                                        @error('harga_tebus_murah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <small class="text-muted">Harga khusus untuk barang ini</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($good->barcode)
                        <div class="barcode-section">
                            <div class="section-title" style="color: #28a745;"><i class="bi bi-qr-code"></i> Informasi Barcode</div>
                            <div class="barcode-info">
                                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-3">
                                    <div>
                                        <h6 class="mb-1 text-success">{{ $good->barcode }}</h6>
                                        <small class="text-muted">Barcode saat ini</small>
                                    </div>
                                    <div class="d-grid d-sm-flex gap-2 w-100 w-sm-auto">
                                        <a href="/dashboard/goods/{{ $good->id }}/print-barcode" class="btn-outline-umkm"><i class="bi bi-printer"></i> Print / Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert-warning-umkm">
                            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-3">
                                <div class="flex-grow-1 text-center text-sm-start">
                                    <strong><i class="bi bi-exclamation-triangle-fill"></i> Perhatian!</strong>
                                    <p class="mb-0">Barang ini belum memiliki barcode.</p>
                                </div>
                                <a href="/dashboard/goods/{{ $good->id }}/generate-barcode" class="btn-warning-umkm w-100 w-sm-auto"><i class="bi bi-qr-code"></i> Generate Barcode</a>
                            </div>
                        </div>
                        @endif

                        <div class="d-grid d-sm-flex justify-content-sm-between gap-2 pt-3 mt-3">
                            <a href="/dashboard/goods" class="btn btn-secondary-umkm"><i class="bi bi-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-warning-umkm"><i class="bi bi-save"></i> Update Barang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
    const markupPercentageInput = document.getElementById('markup_percentage'); 
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

function toggleWholesaleFields() {
    const checkbox = document.getElementById('is_grosir_active');
    const wholesaleFields = document.getElementById('wholesale-fields');
    const minQtyInput = document.getElementById('min_qty_grosir');
    const hargaGrosirInput = document.getElementById('harga_grosir');
    if (checkbox.checked) {
        wholesaleFields.classList.add('show');
        minQtyInput.required = true;
        hargaGrosirInput.required = true;
    } else {
        wholesaleFields.classList.remove('show');
        minQtyInput.required = false;
        hargaGrosirInput.required = false;
    }
}

function toggleTebusMusahFields() {
    const checkbox = document.getElementById('is_tebus_murah_active');
    const tebusMusahFields = document.getElementById('tebus-murah-fields');
    const minTotalInput = document.getElementById('min_total_tebus_murah');
    const hargaTebusMusahInput = document.getElementById('harga_tebus_murah');
    if (checkbox.checked) {
        tebusMusahFields.classList.add('show');
        minTotalInput.required = true;
        hargaTebusMusahInput.required = true;
    } else {
        tebusMusahFields.classList.remove('show');
        minTotalInput.required = false;
        hargaTebusMusahInput.required = false;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleExpiredField();
    toggleWholesaleFields();
    toggleTebusMusahFields();
    calculateSellingPrice();
});
</script>
@endsection
