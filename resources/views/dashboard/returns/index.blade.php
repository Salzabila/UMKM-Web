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
.umkm-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
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
.umkm-card-header:hover::before { right: -30%; }
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
    overflow: hidden;
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
    justify-content: center;
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
.form-control:focus, .form-select:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    background: white;
}
.table-responsive { overflow-x: auto; }
.table-umkm {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
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
.table-umkm tbody tr:last-child td { border-bottom: none; }
.table-umkm tbody tr:hover { background-color: #f8f9fa; }
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
.search-section {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid rgba(255,255,255,0.2);
}
.input-group .form-control { border-radius: 15px 0 0 15px; }
.input-group .btn { border-radius: 0 15px 15px 0; }
.action-dropdown { position: static; }
.action-dropdown .dropdown-toggle::after { display: none; }
.action-dropdown .btn-action {
    background: transparent;
    border: none;
    color: #6c757d;
    padding: 0.25rem 0.5rem;
}
.action-dropdown .btn-action:hover,
.action-dropdown .btn-action:focus {
    background-color: #e9ecef;
    color: #212529;
}
.action-dropdown .dropdown-menu {
    border-radius: 15px;
    border: 1px solid rgba(0,0,0,0.1);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    padding: 0.5rem 0;
    z-index: 100;
}
.action-dropdown .dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    font-weight: 500;
    transition: background-color 0.2s ease, color 0.2s ease;
}
.action-dropdown .dropdown-item:hover { background-color: #f8f9fa; }
.action-dropdown .dropdown-item i {
    font-size: 1.1rem;
    width: 20px;
    text-align: center;
}
.action-dropdown .dropdown-item-form {
    padding: 0;
    margin: 0;
}
.action-dropdown .dropdown-item-form button {
    width: 100%;
    text-align: left;
    background: none;
    border: none;
}
.action-dropdown .dropdown-item.text-danger:hover {
    background-color: #fdf2f2;
    color: #c82333 !important;
}

.pagination-wrapper .pagination {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.pagination-wrapper .page-link {
    border: none;
    padding: 12px 16px;
    color: #28a745; 
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s ease;
}
.pagination-wrapper .page-link:hover {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    transform: translateY(-1px);
}
.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #28a745, #20c997);
    border-color: #28a745;
    color: white;
    font-weight: 700;
}
.pagination-wrapper .page-item .page-link[aria-label="Previous"],
.pagination-wrapper .page-item .page-link[aria-label="Next"] {
    color: #28a745;
    font-weight: bold;
    font-size: 1.1rem;
}
.pagination-wrapper .page-item.disabled .page-link {
    color: #a5a5a5;
    background: transparent;
}

/* Responsif di layar kecil */
@media (max-width: 576px) {
    .pagination-wrapper .page-link {
        padding: 8px 12px;
        font-size: 0.9rem;
    }
    .pagination-wrapper .page-item .page-link[aria-label="Previous"],
    .pagination-wrapper .page-item .page-link[aria-label="Next"] {
        font-size: 1rem;
    }
    .pagination-wrapper .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
}

@media (min-width: 768px) {
    .page-title h1 { font-size: 2.5rem; }
    .umkm-card-body { padding: 25px; }
}
</style>

<div class="container-fluid py-4">
    <div class="page-title">
        <h1>🔄 MANAJEMEN DATA RETURN</h1>
        <p>Kelola data pengembalian barang dari Mitra Binaan</p>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="umkm-card">
        <div class="umkm-card-header">
            <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center w-100 gap-2">
                <h3 class="umkm-card-title mb-2 mb-md-0">
                    <i class="bi bi-arrow-return-left"></i>
                    Data Return Barang
                </h3>
                <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
                    <form id="bulk-delete-form" action="{{ route('returns.bulkDelete') }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" id="bulk-delete-button" class="btn btn-danger btn-umkm-sm" style="display: none;">
                            <i class="bi bi-trash-fill"></i> Hapus Terpilih
                        </button>
                    </form>
                    <a href="{{ route('returns.create') }}" class="btn btn-umkm btn-umkm-sm">
                        <i class="bi bi-plus-circle"></i>
                        Tambah Return
                    </a>
                </div>
            </div>
        </div>

        <div class="umkm-card-body">
            <div class="search-section">
                <form action="{{ route('returns.index') }}" method="GET" id="search-form">
                    <div class="row align-items-end">
                        <div class="col-12 col-md-4 mb-3 mb-md-0">
                            <label class="form-label text-white fw-bold">
                                <i class="bi bi-search me-2"></i>Cari Barang atau Mitra
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Masukkan nama barang atau mitra..."
                                       name="search" value="{{ request('search') }}">
                                <button class="btn btn-umkm" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-3 mb-md-0">
                            <label class="form-label text-white fw-bold">
                                <i class="bi bi-building me-2"></i>Filter Mitra Saja
                            </label>
                            <select class="form-select" name="category_id" id="category-filter">
                                <option value="all">Semua Mitra</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="text-white text-md-end">
                                <small><i class="bi bi-info-circle me-1"></i>Total: {{ $returns->total() }} data return</small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-umkm">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align: center;">
                                <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                            </th>
                            <th style="width: 5%;">NO</th>
                            <th style="width: 10%;">
                                Tgl Return
                                <button type="button" class="btn btn-sm btn-light ms-2 sort-toggle"
                                    data-sort-param="tgl_return"
                                    data-sort-order="{{ request('sort_tgl_return', 'none') }}"
                                    title="Urutkan berdasarkan tanggal return">
                                    <i class="bi {{ request('sort_tgl_return') == 'asc' ? 'bi-sort-up' : (request('sort_tgl_return') == 'desc' ? 'bi-sort-down' : 'bi-arrow-down-up') }}"></i>
                                </button>
                            </th>
                            <th style="width: 15%;">Mitra Binaan</th>
                            <th style="width: 20%;">Nama Barang</th>
                            <th style="width: 7%;">Qty</th>
                            <th style="width: 15%;">Alasan</th>
                            <th style="width: 15%;">Keterangan</th>
                            <th style="width: 10%;">Administrator</th>
                            <th style="width: 5%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $key => $return)
                        <tr>
                            <td class="text-center">
                                <input class="form-check-input item-checkbox" type="checkbox" value="{{ $return->id }}">
                            </td>
                            <td><strong>{{ $returns->firstItem() + $key }}</strong></td>
                            <td>
                                <i class="bi bi-calendar-x text-danger me-1"></i>
                                {{ \Carbon\Carbon::parse($return->tgl_return ?? $return->tanggal_retur)->format('d/m/Y') }}
                            </td>
                            <td>
                                <i class="bi bi-building text-info me-1"></i>
                                {{ $return->good->category->nama ?? 'N/A' }}
                            </td>
                            <td>
                                <i class="bi bi-box text-primary me-2"></i>
                                {{ $return->good->nama ?? $return->good->nama_barang }}
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $return->qty_return }} unit</span>
                            </td>
                            <td>{{ $return->alasan }}</td>
                            <td>{{ $return->keterangan }}</td>
                            <td>
                                <i class="bi bi-person-check text-success me-1"></i>
                                {{ $return->user->nama }}
                            </td>
                            <td class="text-center">
                                <div class="dropdown action-dropdown">
                                    <button class="btn btn-action dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical fs-5"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('returns.edit', $return->id) }}">
                                                <i class="bi bi-pencil-square text-warning"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('returns.destroy', $return->id) }}" method="post" class="dropdown-item-form">
                                                @method('delete')
                                                @csrf
                                                <button type="button" class="dropdown-item text-danger" onclick="showDeleteModal(this.form, '{{ $return->good->nama ?? $return->good->nama_barang }}')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                    <h5>Belum ada data return</h5>
                                    <p>Silakan tambah data return baru untuk memulai</p>
                                    <a href="{{ route('returns.create') }}" class="btn-umkm">
                                        <i class="bi bi-plus-circle"></i>
                                        Tambah Data Return
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($returns->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <div class="pagination-wrapper">
                    {{ $returns->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border-bottom: none; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
            </div>
            <div class="modal-body fs-5 text-center py-4">
                Apakah Anda yakin ingin menghapus data return untuk barang <br><strong id="itemNameToDelete" class="text-danger"></strong>?
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton" style="border-radius: 10px;">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bulkDeleteConfirmationModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border-bottom: none; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title" id="bulkDeleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
            </div>
            <div class="modal-body fs-5 text-center py-4">
                Apakah Anda yakin ingin menghapus <strong id="bulkDeleteCount" class="text-danger"></strong> data return yang dipilih?
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmBulkDeleteButton" style="border-radius: 10px;">Ya, Hapus Semua</button>
            </div>
        </div>
    </div>
</div>

{{-- Enhanced JavaScript with date sorting functionality --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- SCRIPT UNTUK HAPUS SATUAN ---
        const deleteModalElement = document.getElementById('deleteConfirmationModal');
        const deleteModal = new bootstrap.Modal(deleteModalElement);
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        const itemNameToDeleteSpan = document.getElementById('itemNameToDelete');
        let currentDeleteForm = null;

        window.showDeleteModal = function(formElement, itemName) {
            currentDeleteForm = formElement;
            itemNameToDeleteSpan.textContent = itemName;
            deleteModal.show();
        }

        confirmDeleteButton.addEventListener('click', function() {
            if (currentDeleteForm) {
                deleteModal.hide();
                currentDeleteForm.submit();
            } else {
                console.error('No form found to submit for single delete!');
            }
        });

        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const bulkDeleteButton = document.getElementById('bulk-delete-button');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');
        const bulkDeleteModal = new bootstrap.Modal(document.getElementById('bulkDeleteConfirmationModal'));
        const bulkDeleteCountSpan = document.getElementById('bulkDeleteCount');
        const confirmBulkDeleteButton = document.getElementById('confirmBulkDeleteButton');

        function updateBulkDeleteButtonState() {
            const selectedCount = document.querySelectorAll('.item-checkbox:checked').length;
            if (selectedCount > 0) {
                bulkDeleteButton.style.display = 'inline-flex';
                bulkDeleteButton.querySelector('.bi').nextSibling.textContent = ` Hapus ${selectedCount} Terpilih`;
            } else {
                bulkDeleteButton.style.display = 'none';
            }
            if(itemCheckboxes.length > 0) {
                selectAllCheckbox.checked = selectedCount > 0 && selectedCount === itemCheckboxes.length;
            }
        }

        if(selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkDeleteButtonState();
            });
        }

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkDeleteButtonState);
        });

        if(bulkDeleteButton) {
            bulkDeleteButton.addEventListener('click', function() {
                const selectedCount = document.querySelectorAll('.item-checkbox:checked').length;
                if (selectedCount > 0) {
                    bulkDeleteCountSpan.textContent = selectedCount;
                    bulkDeleteModal.show();
                }
            });
        }

        if(confirmBulkDeleteButton) {
            confirmBulkDeleteButton.addEventListener('click', function() {
                bulkDeleteForm.querySelectorAll('input[name="selected_ids[]"]').forEach(input => input.remove());

                itemCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'selected_ids[]';
                        hiddenInput.value = checkbox.value;
                        bulkDeleteForm.appendChild(hiddenInput);
                    }
                });

                bulkDeleteForm.submit();
            });
        }

        updateBulkDeleteButtonState();

        const sortToggles = document.querySelectorAll('.sort-toggle');
        sortToggles.forEach(toggleButton => {
            toggleButton.addEventListener('click', function() {
                const sortParam = this.dataset.sortParam;
                let currentSortOrder = this.dataset.sortOrder;
                let newSortOrder;

                if (currentSortOrder === 'none') {
                    newSortOrder = 'asc';
                } else if (currentSortOrder === 'asc') {
                    newSortOrder = 'desc';
                } else {
                    newSortOrder = 'none';
                }

                const url = new URL(window.location.href);

                url.searchParams.delete('sort_tgl_return');

                if (newSortOrder !== 'none') {
                    url.searchParams.set(`sort_${sortParam}`, newSortOrder);
                }

                url.searchParams.delete('page');

                window.location.href = url.toString();
            });
        });

        const categoryFilterSelect = document.getElementById('category-filter');
        if (categoryFilterSelect) {
            categoryFilterSelect.addEventListener('change', function() {
                document.getElementById('search-form').submit();
            });
        }
    });
</script>
@endsection
