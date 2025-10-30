@extends('dashboard.layouts.main')

@section('container')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

    .form-control:read-only {
        background-color: #e9ecef;
        opacity: 1;
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
        color: white;
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
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        color: white;
    }

    .btn-secondary-umkm {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        border: none;
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

    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 20px);
        padding: 12px 20px;
        border-radius: 15px;
        border: 2px solid #e9ecef;
        background: rgba(255,255,255,0.9);
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
        padding-left: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(2.25rem + 18px);
        right: 15px;
    }
    .select2-container--open .select2-dropdown--below {
        border-radius: 15px;
        border: 2px solid #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        overflow: hidden;
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
        <h1>➕ TAMBAH PESANAN</h1>
        <p>Tambah item baru ke dalam nota nomor <strong>{{ $no_nota }}</strong></p>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 col-md-12">
            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-cart-plus"></i>
                        Formulir Tambah Pesanan
                    </h3>
                </div>

                <div class="umkm-card-body">
                    <form method="post" action="/dashboard/orders/store">
                        @csrf
                        <div class="mb-3">
                            <label for="no_nota" class="form-label">No. Nota</label>
                            <input type="text" class="form-control" name="no_nota" required value="{{ $no_nota }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="goods" class="form-label">Pilih Barang</label>
                            <select class="form-select" id="goods" name="good_id" onchange="Subtotal()">
                                <option value="" disabled selected>-- Cari dan Pilih Barang --</option>
                                @foreach ($goods as $good)
                                    <option 
                                        price="{{ $good->harga }}"
                                        harga_grosir="{{ $good->harga_grosir }}"
                                        min_qty_grosir="{{ $good->min_qty_grosir }}"
                                        is_grosir_active="{{ $good->is_grosir_active }}"
                                        harga_tebus_murah="{{ $good->harga_tebus_murah }}"
                                        min_total_tebus_murah="{{ $good->min_total_tebus_murah }}"
                                        is_tebus_murah_active="{{ $good->is_tebus_murah_active }}"
                                        value="{{ $good->id }}">
                                        {{ $good->nama }} (Stok: {{ $good->stok }} | Normal: Rp {{ number_format($good->harga, 0, ',', '.') }}
                                        @if($good->is_grosir_active && $good->harga_grosir > 0)
                                            | Grosir: Rp {{ number_format($good->harga_grosir, 0, ',', '.') }} untuk Qty≥{{ $good->min_qty_grosir }}
                                        @endif
                                        @if($good->is_tebus_murah_active && $good->harga_tebus_murah > 0)
                                            | Tebus Murah: Rp {{ number_format($good->harga_tebus_murah, 0, ',', '.') }} jika total≥Rp{{ number_format($good->min_total_tebus_murah, 0, ',', '.') }}
                                        @endif
                                        )
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="qty" class="form-label">Jumlah Pesan</label>
                                <input type="number" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                       class="form-control" name="qty" id="qty" required placeholder="Masukkan Jumlah..." oninput="Subtotal()">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="subtotal" class="form-label">Subtotal</label>
                                <input type="text" class="form-control" id="subtotal_display" readonly placeholder="Rp 0">
                                <input type="hidden" id="subtotal" name="subtotal" required>
                                <small id="price_info" class="text-muted"></small>
                            </div>
                        </div>

                        <div class="d-grid d-sm-flex justify-content-sm-between pt-4 gap-2">
                            <a href="/dashboard/orders?no_nota={{ $no_nota }}" class="btn btn-secondary-umkm">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button class="btn btn-umkm" type="submit">
                                <i class="bi bi-plus-lg"></i> Tambahkan Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#goods').select2({
            placeholder: "-- Cari dan Pilih Barang --",
            allowClear: true
        });
    });

    function Subtotal() {
        var databarang = document.querySelector("#goods");
        var selectedOption = databarang.options[databarang.selectedIndex];

        if (!selectedOption || !selectedOption.hasAttribute('price')) {
            document.getElementById("subtotal_display").value = "Rp 0";
            document.getElementById("subtotal").value = "";
            document.getElementById("price_info").innerHTML = "";
            return;
        }

        var hargaNormal = parseFloat(selectedOption.getAttribute('price'));
        var hargaGrosir = parseFloat(selectedOption.getAttribute('harga_grosir')) || 0;
        var minQtyGrosir = parseInt(selectedOption.getAttribute('min_qty_grosir')) || 0;
        var isGrosirActive = selectedOption.getAttribute('is_grosir_active') === '1';
        var hargaTebusMurah = parseFloat(selectedOption.getAttribute('harga_tebus_murah')) || 0;
        var minTotalTebusMurah = parseFloat(selectedOption.getAttribute('min_total_tebus_murah')) || 0;
        var isTebusMurahActive = selectedOption.getAttribute('is_tebus_murah_active') === '1';
        
        var qty = parseInt(document.querySelector("#qty").value) || 0;
        var currentTotal = {{ $currentTotal }}; // Total transaksi saat ini
        
        var unitPrice = hargaNormal;
        var priceType = 'Normal';
        var penghematan = 0;
        
        if (isTebusMurahActive && currentTotal >= minTotalTebusMurah && hargaTebusMurah > 0) {
            unitPrice = hargaTebusMurah;
            priceType = 'Tebus Murah';
            penghematan = (hargaNormal - unitPrice) * qty;
        }
        else if (isGrosirActive && qty >= minQtyGrosir && hargaGrosir > 0) {
            unitPrice = hargaGrosir;
            priceType = 'Grosir';
            penghematan = (hargaNormal - unitPrice) * qty;
        }
        
        var hasil = unitPrice * qty;
        
        document.getElementById("subtotal").value = hasil;
        document.getElementById("subtotal_display").value = 'Rp ' + new Intl.NumberFormat('id-ID').format(hasil);
        
        var priceInfo = '';
        if (priceType === 'Tebus Murah') {
            priceInfo = '<span class="badge bg-danger">TEBUS MURAH</span> Penghematan: Rp ' + new Intl.NumberFormat('id-ID').format(penghematan);
        } else if (priceType === 'Grosir') {
            priceInfo = '<span class="badge bg-warning text-dark">GROSIR</span> Penghematan: Rp ' + new Intl.NumberFormat('id-ID').format(penghematan);
        } else {
            priceInfo = '<span class="badge bg-secondary">HARGA NORMAL</span>';
        }
        
        document.getElementById("price_info").innerHTML = priceInfo;
    }
</script>

@endsection
