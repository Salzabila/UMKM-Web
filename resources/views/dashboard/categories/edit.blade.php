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

    .form-control {
        border-radius: 15px;
        border: 2px solid #e9ecef;
        padding: 12px 20px;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.9);
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: #28a745, #20c997;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        background: white;
        transform: translateY(-1px);
    }

    .btn-warning-umkm, .btn-secondary-umkm {
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

    .btn-warning-umkm {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        color: #000;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-warning-umkm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 193, 7, 0.3);
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

    .required {
        color: #dc3545;
    }

    .bi-building,
    .text-warning,
    .bi-telephone,
    .text-success,
    .bi-geo-alt   {
    color: #28a745 !important;
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
        <h1>✏️ EDIT MITRA BINAAN</h1>
        <p>Perbarui informasi mitra binaan GERAI UMKM MART</p>
    </div>

    <div class="row justify-content-center">
        {{-- [RESPONSIVE] Mengubah col-lg-6 fleksibel --}}
        <div class="col-xl-6 col-lg-8 col-md-10">
            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-pencil-square"></i>
                        Form Edit Mitra Binaan
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

                    <form method="post" action="/dashboard/categories/{{ $category->id }}">
                        @method('put')
                        @csrf

                        <div class="mb-4">
                            <label for="nama" class="form-label">
                                <i class="bi bi-building text-warning"></i>
                                Nama Mitra Binaan <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                   name="nama" required placeholder="Masukkan Nama Mitra Binaan..."
                                   value="{{ old('nama', $category->nama) }}">
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nomor_penanggung_jawab" class="form-label">
                                <i class="bi bi-telephone text-success"></i>
                                Nomor Penanggung Jawab
                            </label>
                            <input type="text" class="form-control @error('nomor_penanggung_jawab') is-invalid @enderror"
                                   name="nomor_penanggung_jawab" placeholder="Masukkan Nomor Telepon/HP..."
                                   value="{{ old('nomor_penanggung_jawab', $category->nomor_penanggung_jawab) }}">
                            @error('nomor_penanggung_jawab')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label">
                                <i class="bi bi-geo-alt text-warning"></i>
                                Alamat Mitra
                            </label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror"
                                      name="alamat" rows="3" placeholder="Masukkan Alamat Lengkap Mitra...">{{ old('alamat', $category->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- [RESPONSIVE] Membuat tombol full-width di mobile dan stack --}}
                        <div class="d-grid d-sm-flex justify-content-sm-between pt-3 gap-2">
                            <a href="/dashboard/categories" class="btn btn-secondary-umkm">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-warning-umkm">
                                <i class="bi bi-save"></i>
                                Update Mitra Binaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
