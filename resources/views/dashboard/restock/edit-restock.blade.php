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

@media (min-width: 768px) {
    .page-title h1 {
        font-size: 2.5rem;
    }
    .card-body {
        padding: 2.5rem;
    }
}
</style>

@extends('layouts.app')

@section('title', 'Restock Barang')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-boxes"></i> Restock Barang</h4>
                </div>
                <div class="card-body">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-4" id="restockTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="new-tab" data-bs-toggle="tab" data-bs-target="#new-product" type="button" role="tab">
                                <i class="fas fa-plus-circle"></i> Restock Barang Baru
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="existing-tab" data-bs-toggle="tab" data-bs-target="#existing-product" type="button" role="tab">
                                <i class="fas fa-box"></i> Restock Barang Eksisting
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit-restock" type="button" role="tab">
                                <i class="bi bi-pencil-square"></i> Edit Data Restock
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="restockTabContent">

                        {{-- ====================== --}}
                        {{-- TAB 1: RESTOCK BARANG BARU --}}
                        {{-- ====================== --}}
                        <div class="tab-pane fade show active" id="new-product" role="tabpanel">
                            @include('dashboard.restock._create_new')
                        </div>

                        {{-- ====================== --}}
                        {{-- TAB 2: RESTOCK BARANG EKSISTING --}}
                        {{-- ====================== --}}
                        <div class="tab-pane fade" id="existing-product" role="tabpanel">
                            @include('dashboard.restock._create_existing')
                        </div>

                        {{-- ====================== --}}
                        {{-- TAB 3: EDIT DATA RESTOCK --}}
                        {{-- ====================== --}}
                        <div class="tab-pane fade" id="edit-restock" role="tabpanel">
                            <div class="container-fluid py-4">
                                <div class="page-title">
                                    <h1>✏️ EDIT DATA RESTOCK</h1>
                                    <p>Edit data restock untuk barang: <strong>{{ $restock->good->nama }}</strong></p>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-8 col-lg-10 col-md-12">
                                        <div class="umkm-card">
                                            <div class="umkm-card-header">
                                                <h3 class="umkm-card-title">
                                                    <i class="bi bi-pencil-square"></i>
                                                    Form Edit Data Restock
                                                </h3>
                                            </div>

                                            <div class="card-body p-4">
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

                                                <!-- Info Barang -->
                                                <div class="info-section">
                                                    <h5 class="text-success mb-3">
                                                        <i class="bi bi-info-circle"></i>
                                                        Informasi Barang
                                                    </h5>

                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <div class="info-item">
                                                                <strong>Nama Barang:</strong><br>
                                                                <span class="text-primary">{{ $restock->good->nama }}</span>
                                                            </div>
                                                            <div class="info-item">
                                                                <strong>Supplier:</strong><br>
                                                                <span class="text-muted">{{ $restock->good->category ? $restock->good->category->nama : 'Tidak ada mitra' }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <div class="info-item">
                                                                <strong>Stok Saat Ini:</strong><br>
                                                                <span class="text-success fw-bold fs-4">{{ $restock->good->stok }}</span> unit
                                                            </div>
                                                            <div class="info-item">
                                                                <strong>Admin Restock:</strong><br>
                                                                <span class="text-info">{{ $restock->user->nama ?? 'User Terhapus' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <form method="post" action="/dashboard/restock/{{ $restock->id }}/update">
                                                    @method('put')
                                                    @csrf

                                                    <div class="mb-4">
                                                        <label for="qty_restock" class="form-label">
                                                            <i class="bi bi-plus-circle text-success"></i>
                                                            Jumlah Stok Restock <span class="required">*</span>
                                                        </label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control @error('qty_restock') is-invalid @enderror"
                                                                id="qty_restock" name="qty_restock"
                                                                value="{{ old('qty_restock', $restock->qty_restock) }}"
                                                                required min="1" placeholder="Masukkan jumlah stok restock...">
                                                            <span class="input-group-text">unit</span>
                                                        </div>
                                                        @error('qty_restock')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                        <small class="text-muted mt-2 d-block">
                                                            <i class="bi bi-info-circle"></i>
                                                            Minimal 1 unit untuk restock
                                                        </small>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="tgl_restock" class="form-label">
                                                            <i class="bi bi-calendar text-success"></i>
                                                            Tanggal Restock <span class="required">*</span>
                                                        </label>
                                                        <input type="date" class="form-control @error('tgl_restock') is-invalid @enderror"
                                                            id="tgl_restock" name="tgl_restock"
                                                            value="{{ old('tgl_restock', $restock->tgl_restock) }}" required>
                                                        @error('tgl_restock')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="keterangan" class="form-label">
                                                            <i class="bi bi-chat-text text-success"></i>
                                                            Keterangan (Opsional)
                                                        </label>
                                                        <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                                            id="keterangan" name="keterangan" rows="3"
                                                            placeholder="Catatan tambahan untuk restock ini...">{{ old('keterangan', $restock->keterangan) }}</textarea>
                                                        @error('keterangan')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Action Buttons -->
                                                    <div class="d-grid d-sm-flex justify-content-sm-between pt-3 mt-4 border-top gap-2">
                                                        <a href="/dashboard/restock" class="btn btn-secondary-umkm">
                                                            <i class="bi bi-arrow-left"></i>
                                                            Kembali
                                                        </a>
                                                        <button type="submit" class="btn btn-umkm">
                                                            <i class="bi bi-save"></i>
                                                            Update Data Restock
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> {{-- end tab edit --}}
                    </div> {{-- end tab-content --}}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const barangSelect = document.getElementById('barang_id');
    const infoBarang = document.getElementById('info-barang');
    const displayNama = document.getElementById('display-nama');
    const displayStok = document.getElementById('display-stok');
    const displayHarga = document.getElementById('display-harga');

    if (barangSelect) {
        barangSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                const nama = selectedOption.getAttribute('data-nama');
                const stok = selectedOption.getAttribute('data-stok');
                const harga = selectedOption.getAttribute('data-harga');
                
                displayNama.textContent = nama;
                displayStok.textContent = stok + ' unit';
                displayHarga.textContent = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
                
                infoBarang.style.display = 'block';
            } else {
                infoBarang.style.display = 'none';
            }
        });
    }
});
</script>
@endpush
@endsection
