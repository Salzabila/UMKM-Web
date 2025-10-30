@extends('dashboard.layouts.main')

@section('container')
    <style>
        body {
        background: linear-gradient(180deg, #e0e0e0, #dcdcdc);
        backdrop-filter: blur(6px);
        border-radius: 20px;
        border: 1px solid #ccc;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        }

        .return-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .return-card-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 20px;
            border-radius: 20px 20px 0 0;
            font-weight: 700;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .return-card-body {
            padding: 1.5rem; /* [RESPONSIVE]  */
        }

        .form-section {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border: 2px solid #d6e0f5;
            padding: 12px 16px;
            background: #f0f4ff;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #28a745, #20c997;
            box-shadow: 0 0 0 0.2rem rgba(75, 108, 183, 0.3);
            background-color: white;
        }

        .btn-primary-return, .btn-secondary-return {
            border-radius: 15px;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center; /* [RESPONSIVE]*/
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary-return {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
        }

        .btn-primary-return:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(75, 108, 183, 0.3);
        }

        .btn-secondary-return {
            background: #6c757d;
            border: none;
        }

        .btn-secondary-return:hover {
            transform: translateY(-2px);
            background: #5a6268;
        }

        .page-title {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
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
            color: red;
        }

        @media (min-width: 768px) {
            .page-title h1 {
                font-size: 2.3rem;
            }
            .return-card-body {
                padding: 30px;
            }
        }
        .bi-calendar3,
        .text-primary,
        .bi-123,
        .bi-exclamation-triangle,
        .bi-person-gear {
        Color: #28a745 !important;
    }
    </style>

    <div class="container-fluid py-4">
        <div class="page-title">
            <h1><i class="bi bi-pencil-square"></i> EDIT RETURN BARANG</h1>
            <p>Perbarui data return barang di bawah ini.</p>
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10 col-md-12">
                <div class="return-card">
                    <div class="return-card-header">
                        <i class="bi bi-pencil-square"></i> Edit Data Return Barang
                    </div>

                    <div class="return-card-body">
                        <form method="post" action="/dashboard/returns/{{ $return->id }}">
                            @method('put')
                            @csrf

                            <div class="form-section">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-calendar3 text-primary"></i> Tanggal Return <span
                                            class="required">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('tgl_return') is-invalid @enderror"
                                        name="tgl_return" value="{{ old('tgl_return', $return->tgl_return) }}" required>
                                    @error('tgl_return')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-box text-primary"></i> Barang <span class="required">*</span>
                                    </label>
                                    <select class="form-select @error('good_id') is-invalid @enderror" name="good_id"
                                        required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach ($goods as $good)
                                            <option value="{{ $good->id }}" {{ $return->good_id == $good->id ? 'selected' : '' }}>
                                                {{ $good->nama }} (Stok: {{ $good->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('good_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-123 text-primary"></i> Jumlah Return <span class="required">*</span>
                                    </label>
                                    <input type="number" class="form-control @error('qty_return') is-invalid @enderror"
                                        name="qty_return" value="{{ old('qty_return', $return->qty_return) }}" min="1"
                                        required>
                                    @error('qty_return')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-exclamation-triangle text-primary"></i> Alasan Return <span
                                            class="required">*</span>
                                    </label>
                                    <select class="form-select @error('alasan') is-invalid @enderror" name="alasan"
                                        required>
                                        @foreach(['Rusak', 'Cacat', 'Kadaluarsa', 'Salah Kirim', 'Lainnya'] as $alasan)
                                            <option value="{{ $alasan }}" {{ $return->alasan == $alasan ? 'selected' : '' }}>
                                                {{ $alasan }}</option>
                                        @endforeach
                                    </select>
                                    @error('alasan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-person-gear text-primary"></i> Administrator <span
                                            class="required">*</span>
                                    </label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" name="user_id"
                                        required>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ $return->user_id == $user->id ? 'selected' : '' }}>
                                                {{ $user->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-chat-left-text text-primary"></i> Keterangan (Opsional)
                                    </label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                        name="keterangan" rows="3">{{ old('keterangan', $return->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid d-sm-flex justify-content-sm-between pt-3 gap-2">
                                <a href="/dashboard/returns" class="btn btn-secondary-return">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary-return">
                                    <i class="bi bi-save"></i> Update Return
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
