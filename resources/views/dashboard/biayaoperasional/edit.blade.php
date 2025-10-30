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

    .form-label i,
    .form-label svg {
        color: #28a745;
        fill: #28a745;
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
        color: #000;
        border: none;
        border-radius: 15px 0 0 15px;
        font-weight: 600;
    }

    .input-group .form-control {
        border-radius: 0 15px 15px 0;
        border-left: none;
    }

    .btn-warning-umkm, .btn-secondary-umkm {
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

    .page-title {
        text-align: center;
        margin-bottom: 30px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .page-title h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 10px;
        color: white; 
    }

    .page-title p {
        font-size: 1.1rem;
        opacity: 0.9;
        color: white; 
    }

    .required {
        color: #dc3545;
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
        <h1><i class="bi bi-pencil-square"></i> EDIT BIAYA OPERASIONAL</h1>
        <p>Perbarui rincian pengeluaran operasional</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 col-md-12">
            <div class="umkm-card">
                <div class="umkm-card-header">
                    <h3 class="umkm-card-title">
                        <i class="bi bi-pencil-square"></i>
                        Form Edit Biaya
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

                    <form method="post" action="{{ route('biayaoperasional.update', $biayaOperasional->id) }}" enctype="multipart/form-data">
                        @method('put')
                        @csrf

                        <div class="mb-3">
                            <label for="uraian" class="form-label">
                                <i class="bi bi-chat-left-text text-warning"></i>
                                Uraian/Keterangan <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control @error('uraian') is-invalid @enderror" id="uraian"
                                name="uraian" required autofocus value="{{ old('uraian', $biayaOperasional->uraian) }}" placeholder="Contoh: Pembelian ATK">
                            @error('uraian')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="nominal" class="form-label">
                                    <i class="bi bi-cash-coin text-warning"></i>
                                    Nominal <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="nominal"
                                        name="nominal" required value="{{ old('nominal', $biayaOperasional->nominal) }}" min="0" placeholder="Contoh: 50000">
                                </div>
                                @error('nominal')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="qty" class="form-label">
                                    <i class="bi bi-box text-warning"></i>
                                    Kuantitas (Qty) <span class="required">*</span>
                                </label>
                                <input type="number" class="form-control @error('qty') is-invalid @enderror" id="qty"
                                    name="qty" required value="{{ old('qty', $biayaOperasional->qty) }}" min="1" placeholder="Contoh: 1">
                                @error('qty')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">
                                <i class="bi bi-calendar-event text-warning"></i>
                                Tanggal <span class="required">*</span>
                            </label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal"
                                name="tanggal" required value="{{ old('tanggal', \Carbon\Carbon::parse($biayaOperasional->tanggal)->format('Y-m-d')) }}">
                            @error('tanggal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="bukti_resi" class="form-label">
                                <i class="bi bi-receipt text-warning"></i>
                                Ganti Bukti/Resi (Opsional)
                            </label>

                            @if($biayaOperasional->bukti_resi)
                                <div class="mb-2">
                                    <p class="form-label mb-1" style="font-size: 0.9rem;">Bukti Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $biayaOperasional->bukti_resi) }}" class="img-fluid" style="max-width: 300px; border-radius: 15px; border: 2px solid #e9ecef;">
                                </div>
                            @endif

                            <img class="img-preview img-fluid mb-3 col-sm-5" style="display: none; border-radius: 15px;">
                            <input class="form-control @error('bukti_resi') is-invalid @enderror" type="file" id="bukti_resi" name="bukti_resi" onchange="previewImage()">
                            <small class="text-muted mt-2 d-block">Kosongkan jika tidak ingin mengubah bukti.</small>
                            @error('bukti_resi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid d-sm-flex justify-content-sm-between pt-3 border-top gap-2">
                            <a href="{{ route('biayaoperasional.index') }}" class="btn btn-secondary-umkm">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-warning-umkm">
                                <i class="bi bi-save"></i>
                                Update Biaya
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Helper function to create a FileList from a single File
    function createFileListItem(file) {
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        return dataTransfer.files;
    }

    // Function to compress image
    function compressImage(file, quality, maxWidth) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = event => {
                const img = new Image();
                img.src = event.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    // Calculate new dimensions while maintaining aspect ratio
                    if (width > maxWidth) {
                        height = Math.round((height * maxWidth) / width);
                        width = maxWidth;
                    }

                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(blob => {
                        if (blob) {
                            const compressedFile = new File([blob], file.name, {
                                type: blob.type,
                                lastModified: Date.now()
                            });
                            resolve(compressedFile);
                        } else {
                            reject(new Error('Canvas to Blob conversion failed.'));
                        }
                    }, file.type, quality);
                };
                img.onerror = error => reject(error);
            };
            reader.onerror = error => reject(error);
        });
    }

    // Modified previewImage function to include compression
    function previewImage() {
        const imageInput = document.querySelector('#bukti_resi');
        const imgPreview = document.querySelector('.img-preview');

        if (imageInput.files && imageInput.files[0]) {
            const originalFile = imageInput.files[0];

            // Display preview immediately
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                imgPreview.style.display = 'block';
            };
            reader.readAsDataURL(originalFile);

            // Compress the image if it's larger than 2MB (2048 KB)
            // Note: The backend validation is still 2MB. This client-side compression
            // aims to reduce size to fit, but if the compressed file is still >2MB,
            // the server will reject it.
            if (originalFile.size > 2 * 1024 * 1024) {
                console.log('File is larger than 2MB, attempting compression...');
                // Compress with 80% quality and max width of 1920px
                compressImage(originalFile, 0.8, 1920)
                    .then(compressedFile => {
                        console.log('Compressed file size:', (compressedFile.size / 1024 / 1024).toFixed(2), 'MB');
                        // Replace the original file with the compressed one in the input
                        imageInput.files = createFileListItem(compressedFile);
                    })
                    .catch(error => {
                        console.error('Image compression failed:', error);
                        alert('Gagal mengompres gambar. Silakan coba lagi atau gunakan gambar yang lebih kecil.');
                        // Optionally clear the input if compression fails
                        imageInput.value = '';
                        imgPreview.style.display = 'none';
                        imgPreview.src = '';
                    });
            } else {
                console.log('File size is within limit, no compression needed.');
                // Ensure the original file is kept if no compression is needed
                imageInput.files = createFileListItem(originalFile);
            }
        } else {
            imgPreview.style.display = 'none';
            imgPreview.src = '';
        }
    }
</script>
@endsection
