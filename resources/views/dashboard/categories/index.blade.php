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

   .umkm-card-header:hover::before {
       right: -30%;
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

   .form-control:focus, .form-select:focus {
       border-color: #28a745;
       box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
       background: white;
   }

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



   .search-section {
       background: rgba(255,255,255,0.1);
       backdrop-filter: blur(10px);
       border-radius: 15px;
       padding: 20px;
       margin-bottom: 20px;
       border: 1px solid rgba(255,255,255,0.2);
   }

   .input-group .form-control {
       border-radius: 15px 0 0 15px;
   }

   .input-group .btn {
       border-radius: 0 15px 15px 0;
   }

   .action-dropdown {
       position: static;
   }

   .action-dropdown .dropdown-toggle::after {
       display: none;
   }

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

   .action-dropdown .dropdown-item:hover {
       background-color: #f8f9fa;
   }

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

    /* Pagination */
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

   .mitra-info {
       font-size: 0.85rem;
       color: #6c757d;
       margin-top: 5px;
   }

   .mitra-info i {
       width: 16px;
       text-align: center;
       margin-right: 5px;
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
       <h1>🏢 MANAJEMEN DATA MITRA BINAAN</h1>
       <p>Kelola data mitra binaan GERAI UMKM MART</p>
   </div>

   @if (session()->has('success'))
       <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
           <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
           <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
       </div>
   @endif

   @if (session()->has('error'))
       <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none;">
           <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
           <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
       </div>
   @endif

   <div class="umkm-card">
       <div class="umkm-card-header">
           <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center w-100 gap-2">
               <h3 class="umkm-card-title mb-2 mb-md-0">
                   <i class="bi bi-building"></i>
                   Data Mitra Binaan
               </h3>
               <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
                   {{-- Debugging form bulk delete agar tidak bersarang --}}
                   <form id="bulk-delete-form" action="{{ route('categories.bulkDelete') }}" method="POST" style="display: inline;">
                       @csrf
                       @method('DELETE')
                       <button type="button" id="bulk-delete-button" class="btn btn-danger btn-umkm-sm" style="display: none;">
                           <i class="bi bi-trash-fill"></i> Hapus Terpilih
                       </button>
                   </form>
                   <a href="/dashboard/categories/create" class="btn-umkm btn-umkm-sm">
                       <i class="bi bi-plus-circle"></i>
                       Tambah Mitra Binaan
                   </a>
               </div>
           </div>
       </div>

       <div class="umkm-card-body">
           <!-- Search Section -->
           <div class="search-section">
               <form action="/dashboard/categories" method="GET">
                   <div class="row align-items-center">
                       <div class="col-12 col-md-8 mb-3 mb-md-0">
                           <label class="form-label text-white fw-bold">
                               <i class="bi bi-search me-2"></i>Cari Mitra Binaan
                           </label>
                           <div class="input-group">
                               <input type="text" class="form-control" placeholder="Masukkan nama mitra binaan..."
                                      name="search" value="{{ request('search') }}">
                               <button class="btn btn-umkm" type="submit">
                                   <i class="bi bi-search"></i>
                               </button>
                           </div>
                       </div>
                       <div class="col-12 col-md-4">
                           <div class="text-white text-md-end">
                               <small><i class="bi bi-info-circle me-1"></i>Total: {{ $categories->total() }} mitra binaan</small>
                           </div>
                       </div>
                   </div>
               </form>
           </div>

           {{-- Debugging Hapus form bulk-delete-form yang membungkus tabel --}}
           <div class="table-responsive">
               <table class="table table-umkm">
                   <thead>
                       <tr>
                           <th style="width: 5%; text-align: center;">
                               <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                           </th>
                           <th style="width: 8%;">No</th>
                           <th style="width: 47%;">Nama Mitra Binaan</th>
                           <th style="width: 20%;">Nomor PJ</th>
                           <th style="width: 20%; text-align: center;">Aksi</th>
                       </tr>
                   </thead>
                   <tbody>
                       @forelse ($categories as $key => $category)
                       <tr>
                           <td class="text-center">
                               <input class="form-check-input item-checkbox" type="checkbox" value="{{ $category->id }}">
                           </td>
                           <td><strong>{{ $categories->firstItem() + $key }}</strong></td>
                           <td>
                               <div class="d-flex align-items-start flex-column">
                                   <div class="d-flex align-items-center mb-1">
                                       <i class="bi bi-building text-success me-2"></i>
                                       <strong>{{ $category->nama }}</strong>
                                   </div>
                                   @if($category->alamat)
                                   <div class="mitra-info">
                                       <i class="bi bi-geo-alt text-muted"></i>
                                       {{ Str::limit($category->alamat, 50) }}
                                   </div>
                                   @endif
                               </div>
                           </td>
                           <td>
                               @if($category->nomor_penanggung_jawab)
                                   <div class="d-flex align-items-center">
                                       <i class="bi bi-telephone text-primary me-2"></i>
                                       <span class="fw-medium">{{ $category->nomor_penanggung_jawab }}</span>
                                   </div>
                               @else
                                   <span class="text-muted fst-italic">-</span>
                               @endif
                           </td>
                           <td class="text-center">
                               <div class="dropdown action-dropdown">
                                   <button class="btn btn-action dropdown-toggle" type="button" id="dropdownMenuButton-{{$category->id}}" data-bs-toggle="dropdown" aria-expanded="false">
                                       <i class="bi bi-three-dots-vertical fs-5"></i>
                                   </button>
                                   <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton-{{$category->id}}">
                                       <li>
                                           <a class="dropdown-item" href="/dashboard/categories/{{ $category->id }}/edit">
                                               <i class="bi bi-pencil-square text-warning"></i> Edit
                                           </a>
                                       </li>
                                       <li>
                                           {{-- Form delete satuan, agar tidak bersarang --}}
                                           <form action="/dashboard/categories/{{ $category->id }}" method="post" class="dropdown-item-form">
                                               @method('delete')
                                               @csrf
                                               <button type="button" class="dropdown-item text-danger" onclick="showDeleteModal(this.form, '{{ $category->nama }}')">
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
                           <td colspan="5" class="text-center py-5">
                               <div class="text-muted">
                                   <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                   <h5>Belum ada data mitra binaan</h5>
                                   <p>Silakan tambah mitra binaan baru untuk memulai</p>
                                   <a href="/dashboard/categories/create" class="btn-umkm">
                                       <i class="bi bi-plus-circle"></i>
                                       Tambah Mitra Binaan Pertama
                                   </a>
                               </div>
                           </td>
                       </tr>
                       @endforelse
                   </tbody>
               </table>
           </div>

           <!-- Pagination -->
           @if($categories->hasPages())
           <div class="d-flex justify-content-center mt-4">
               <div class="pagination-wrapper">
                   {{ $categories->links() }}
               </div>
           </div>
           @endif
       </div>
   </div>
</div>

<!-- Modal Konfirmasi Hapus SATUAN -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content" style="border-radius: 15px; border: none;">
    <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border-bottom: none; border-radius: 15px 15px 0 0;">
      <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Penghapusan</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
    </div>
    <div class="modal-body fs-5 text-center py-4">
      Apakah Anda yakin ingin menghapus mitra <br><strong id="categoryNameToDelete" class="text-danger"></strong>?
    </div>
    <div class="modal-footer" style="border-top: none;">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
      <button type="button" class="btn btn-danger" id="confirmDeleteButton" style="border-radius: 10px;">Ya, Hapus</button>
    </div>
  </div>
</div>
</div>

<!-- Modal Konfirmasi Hapus BANYAK -->
<div class="modal fade" id="bulkDeleteConfirmationModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content" style="border-radius: 15px; border: none;">
    <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border-bottom: none; border-radius: 15px 15px 0 0;">
      <h5 class="modal-title" id="bulkDeleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus Massal</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) grayscale(100%) brightness(200%);"></button>
    </div>
    <div class="modal-body fs-5 text-center py-4">
      Apakah Anda yakin ingin menghapus <strong id="bulkDeleteCount" class="text-danger"></strong> mitra yang dipilih?
    </div>
    <div class="modal-footer" style="border-top: none;">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
      <button type="button" class="btn btn-danger" id="confirmBulkDeleteButton" style="border-radius: 10px;">Ya, Hapus Semua</button>
    </div>
  </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- SCRIPT UNTUK HAPUS SATUAN ---
        const deleteModalElement = document.getElementById('deleteConfirmationModal');
        const deleteModal = new bootstrap.Modal(deleteModalElement);
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        const categoryNameToDeleteSpan = document.getElementById('categoryNameToDelete');
        let currentDeleteForm = null;

        window.showDeleteModal = function(formElement, categoryName) {
            currentDeleteForm = formElement;
            categoryNameToDeleteSpan.textContent = categoryName;
            deleteModal.show();
        }

        confirmDeleteButton.addEventListener('click', function() {
            console.log('Attempting to submit form:', currentDeleteForm);
            if (currentDeleteForm) {
                deleteModal.hide();
                currentDeleteForm.submit();
            } else {
                console.error('No form found to submit for single delete!');
            }
        });

        // --- SCRIPT UNTUK BULK DELETE  ---
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
            selectAllCheckbox.checked = selectedCount > 0 && selectedCount === itemCheckboxes.length;
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
    });
</script>
@endsection
