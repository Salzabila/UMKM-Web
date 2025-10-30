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
        justify-content: center; /* [RESPONSIVE] */
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

    .form-control:read-only {
        background-color: #e9ecef;
        opacity: 1;
    }

    .form-control:focus, .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        background: white;
    }

    .table-umkm {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        overflow: hidden;
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

    .table-umkm tbody tr:last-child td {
        border-bottom: none;
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

    .checkout-section {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-top: 25px;
    }

    .checkout-section h2 {
        color: #28a745;
    }

    @media (min-width: 768px) {
        .page-title h1 {
            font-size: 2.5rem;
        }
        .umkm-card-body {
            padding: 25px;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1>🛒 DETAIL PESANAN</h1>
        <p>Kelola item belanja untuk nota nomor <strong>{{ $no_nota }}</strong></p>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="umkm-card">
        <div class="umkm-card-header">
            <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center w-100 gap-2">
                <h3 class="umkm-card-title mb-2 mb-md-0">
                    <i class="bi bi-cart3"></i>
                    Keranjang Belanja
                </h3>
                <form method="post" action="/dashboard/orders/create" class="w-100 w-md-auto">
                    @csrf
                    <input type="hidden" name="no_nota" value="{{ $no_nota }}">
                    <button type="submit" class="btn btn-umkm btn-umkm-sm w-100" style="background: white; color: #28a745;">
                        <i class="bi bi-plus-circle"></i>
                        Tambah Belanja
                    </button>
                </form>
            </div>
        </div>

        <div class="umkm-card-body">
            <div class="table-responsive">
                <table class="table table-umkm" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td><strong>{{ $loop->iteration }}</strong></td>
                                <td>{{ $order->good ? $order->good->nama : 'Barang sudah tidak ada' }}</td>
                                <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                                <td>{{ $order->qty }}</td>
                                <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <form action="/dashboard/orders/{{ $order->id }}" method="post" class="d-inline">
                                        @method('delete')
                                        @csrf
                                        <input type="hidden" name="no_nota" value="{{ $order->no_nota }}">
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus item ini dari keranjang?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-cart-x display-4 d-block mb-3"></i>
                                        <h5>Keranjang belanja masih kosong</h5>
                                        <p>Silakan tambah barang untuk memulai transaksi.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->count() > 0)
            <div class="checkout-section">
                <form method="post" action="/dashboard/transactions/checkout">
                    @csrf
                    <input type="hidden" name="no_nota" value="{{ $no_nota }}">
                    <input type="hidden" name="nama_pembeli" value="CASH"> 

                    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-3">
                        <div class="text-center text-sm-start">
                            <span class="text-muted">Total Harga</span>
                            <h2 class="fw-bold mb-0" id="total_harga_display">Rp 0</h2>
                            <input type="hidden" name="total_harga" id="total_harga">
                        </div>
                        <div class="d-grid d-sm-block">
                            <button type="submit" class="btn btn-umkm btn-lg">
                                <i class="bi bi-check-circle"></i>
                                Checkout
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function calculateTotal() {
        var table = document.getElementById("table");
        var subtotalSum = 0;

        for(var i = 1; i < table.rows.length; i++) {
            var cellValue = table.rows[i].cells[4].innerText.replace(/[^0-9]/g, '');
            subtotalSum += parseInt(cellValue) || 0;
        }

        var finalTotal = subtotalSum;

        document.getElementById("total_harga").value = finalTotal;
        document.getElementById("total_harga_display").innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(finalTotal);
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateTotal();
    });
</script>
@endsection
