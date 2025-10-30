<?php $__env->startSection('container'); ?>
  <style>
      body {
          background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
          font-family: 'Poppins', sans-serif;
          min-height: 100vh;
      }

      .main-card {
          background: white;
          border-radius: 20px;
          box-shadow: 0 20px 40px rgba(0,0,0,0.1);
          overflow: hidden;
          margin-bottom: 1.5rem;
      }

      .card-header-custom {
          background: linear-gradient(135deg, #28a745, #20c997);
          color: white;
          padding: 25px 30px;
          border: none;
      }

      .card-header-custom h4, .card-header-custom h5 {
          margin: 0;
          font-weight: 600;
          font-size: 1.5rem;
          display: flex;
          align-items: center;
          gap: 10px;
      }

      .form-section {
          padding: 30px;
      }

      .form-group {
          margin-bottom: 25px;
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
          border: 2px solid #d4edda;
          border-radius: 12px;
          padding: 12px 16px;
          font-size: 14px;
          transition: all 0.3s ease;
          background-color: #f8fff9;
      }

      .form-control:focus {
          border-color: #28a745;
          box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
          outline: none;
          background-color: white;
      }

      .btn-primary {
          background: linear-gradient(135deg, #28a745, #20c997);
          border: none;
          padding: 12px 30px;
          border-radius: 25px;
          font-weight: 600;
          transition: all 0.3s ease;
          box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
      }

      .btn-primary:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
      }

      .btn-info {
          background: linear-gradient(135deg, #17a2b8, #138496);
          border: none;
          padding: 12px 30px;
          border-radius: 25px;
          font-weight: 600;
          transition: all 0.3s ease;
          box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
      }

      .btn-info:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
      }

      .btn-danger {
          background: linear-gradient(135deg, #dc3545, #c82333);
          border: none;
          padding: 12px 30px;
          border-radius: 25px;
          font-weight: 600;
          transition: all 0.3s ease;
          box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
      }

      .btn-danger:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
      }

      .info-card {
          background: linear-gradient(135deg, #f8fff9, #d4edda);
          border-radius: 15px;
          padding: 20px;
          margin-bottom: 25px;
          border-left: 4px solid #28a745;
      }

      .stats-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
          gap: 20px;
          margin-bottom: 30px;
      }

      .stat-card {
          background: white;
          border-radius: 15px;
          padding: 20px;
          box-shadow: 0 4px 15px rgba(0,0,0,0.1);
          border-left: 4px solid #28a745;
          transition: all 0.3s ease;
      }

      .stat-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      }

      .stat-card.stat-card-danger {
          border-left-color: #dc3545;
      }
      .stat-card.stat-card-danger .stat-number {
          color: #dc3545;
      }
      .stat-card.stat-card-danger .stat-icon {
          background: linear-gradient(135deg, #dc3545, #c82333);
      }

      .stat-number {
          font-size: 1.8rem;
          font-weight: bold;
          color: #28a745;
          margin-bottom: 5px;
      }

      .stat-label {
          color: #6c757d;
          font-size: 14px;
          font-weight: 500;
      }

      .stat-icon {
          width: 50px;
          height: 50px;
          border-radius: 50%;
          background: linear-gradient(135deg, #28a745, #20c997);
          display: flex;
          align-items: center;
          justify-content: center;
          color: white;
          font-size: 20px;
          margin-bottom: 15px;
      }


      @media (max-width: 768px) {
          .main-card {
              margin: 10px;
              border-radius: 15px;
          }

          .card-header-custom {
              padding: 20px;
          }

          .form-section {
              padding: 20px;
          }

          .stats-grid {
              grid-template-columns: 1fr;
          }
      }
  </style>

  <div class="container-fluid">
      <?php if(session()->has('success')): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none; box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);">
              <i class="bi bi-check-circle-fill me-2"></i>
              <?php echo e(session('success')); ?>

              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      <?php endif; ?>

      <div class="main-card">
          <div class="card-header-custom">
              <h4>
                  <i class="bi bi-graph-up"></i>
                  Rekapitulasi & Laporan UMKM MART
              </h4>
          </div>

          <div class="form-section">
              <div class="info-card">
                  <h6 class="mb-2">
                      <i class="bi bi-info-circle me-2"></i>
                      Dashboard Rekapitulasi
                  </h6>
                  <p class="mb-0 text-muted">
                      Berikut adalah ringkasan data dan statistik toko UMKM MART.
                      Anda dapat mencetak laporan berdasarkan kategori dan periode tertentu.
                  </p>
              </div>

              <div class="stats-grid">
                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="bi bi-check-circle"></i>
                      </div>
                      <div class="stat-number"><?php echo e(number_format($successfulTransactions ?? 0)); ?></div>
                      <div class="stat-label">Transaksi Berhasil</div>
                  </div>

                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="bi bi-currency-dollar"></i>
                      </div>
                      <div class="stat-number">Rp <?php echo e(number_format($totalRevenue ?? 0, 0, ',', '.')); ?></div>
                      <div class="stat-label">Total Pendapatan</div>
                  </div>

                  <div class="stat-card stat-card-danger">
                      <div class="stat-icon">
                          <i class="bi bi-wallet2"></i>
                      </div>
                      <div class="stat-number">Rp <?php echo e(number_format($totalExpenses ?? 0, 0, ',', '.')); ?></div>
                      <div class="stat-label">Total Pengeluaran</div>
                  </div>

                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="bi bi-box"></i>
                      </div>
                      <div class="stat-number"><?php echo e(number_format($totalProducts ?? 0)); ?></div>
                      <div class="stat-label">Total Produk</div>
                  </div>

                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="bi bi-people"></i>
                      </div>
                      <div class="stat-number"><?php echo e(number_format($totalUsers ?? 0)); ?></div>
                      <div class="stat-label">Total User</div>
                  </div>

                  <div class="stat-card">
                      <div class="stat-icon">
                          <i class="bi bi-arrow-return-left"></i>
                      </div>
                      <div class="stat-number"><?php echo e(number_format($totalReturns ?? 0)); ?></div>
                      <div class="stat-label">Total Return</div>
                  </div>
              </div>

              <div class="row mt-5">
                  <div class="col-xl-4 col-lg-6 mb-4">
                      <div class="main-card h-100">
                          <div class="card-header-custom">
                              <h5 class="mb-0">
                                  <i class="bi bi-file-earmark-pdf me-2"></i>
                                  Cetak Laporan Transaksi
                              </h5>
                          </div>

                          <div class="form-section">
                              <form method="post" action="/dashboard/rekapitulasi/cetak-transaksi" onsubmit="handleFormSubmit(this)">
                                  <?php echo csrf_field(); ?>
                                  <div class="form-group">
                                      <label class="form-label">
                                          <i class="bi bi-calendar-event text-primary"></i>
                                          Tanggal Mulai
                                      </label>
                                      <input type="date" class="form-control" name="tgl_awal"
                                          value="<?php echo e(date('Y-m-01')); ?>" required>
                                  </div>
                                  <div class="form-group">
                                      <label class="form-label">
                                          <i class="bi bi-calendar-check text-primary"></i>
                                          Tanggal Akhir
                                      </label>
                                      <input type="date" class="form-control" name="tgl_akhir"
                                          value="<?php echo e(date('Y-m-d')); ?>" required>
                                  </div>
                                  <div class="form-group">
                                      <label class="form-label">
                                          <i class="bi bi-shop text-primary"></i>
                                          Mitra Binaan
                                      </label>
                                      <select class="form-control" name="category_id">
                                          <option value="">Semua Mitra Binaan</option>
                                          <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                              <option value="<?php echo e($category->id); ?>"><?php echo e($category->nama); ?></option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      </select>
                                  </div>
                                  <button type="submit" class="btn btn-primary w-100">
                                      <i class="bi bi-download me-2"></i>Cetak Laporan Transaksi
                                  </button>
                              </form>
                          </div>
                      </div>
                  </div>

                  <div class="col-xl-4 col-lg-6 mb-4">
                      <div class="main-card h-100">
                          <div class="card-header-custom" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                              <h5 class="mb-0">
                                  <i class="bi bi-box-seam me-2"></i>
                                  Cetak Data Barang
                              </h5>
                          </div>
                          <div class="form-section">
                              <form method="post" action="/dashboard/rekapitulasi/cetak-barang" onsubmit="handleFormSubmit(this)">
                                  <?php echo csrf_field(); ?>
                                  <div class="form-group">
                                      <label class="form-label">
                                          <i class="bi bi-calendar-event text-info"></i>
                                          Tanggal Mulai
                                      </label>
                                      <input type="date" class="form-control" name="tgl_awal"
                                          value="<?php echo e(date('Y-m-01')); ?>">
                                  </div>
                                  <div class="form-group">
                                      <label class="form-label">
                                          <i class="bi bi-calendar-check text-info"></i>
                                          Tanggal Akhir
                                      </label>
                                      <input type="date" class="form-control" name="tgl_akhir"
                                          value="<?php echo e(date('Y-m-d')); ?>">
                                  </div>
                                  <div class="form-group">
                                      <label class="form-label">
                                          <i class="bi bi-shop text-info"></i>
                                          Mitra Binaan
                                      </label>
                                      <select class="form-control" name="category_id">
                                          <option value="">Semua Mitra Binaan</option>
                                          <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                              <option value="<?php echo e($category->id); ?>"><?php echo e($category->nama); ?></option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      </select>
                                  </div>
                                  <button type="submit" class="btn btn-info w-100">
                                      <i class="bi bi-download me-2"></i>Cetak Data Barang
                                  </button>
                              </form>
                          </div>
                      </div>
                  </div>

                  <div class="col-xl-4 col-lg-6 mb-4">
                      <div class="main-card h-100">
                          <div class="card-header-custom" style="background: linear-gradient(135deg, #6f42c1, #8a2be2);">
                              <h5 class="mb-0">
                                  <i class="bi bi-arrow-left-right me-2"></i>
                                  Cetak Restock & Return
                              </h5>
                          </div>
                          <div class="form-section">
                              <form method="post" action="/dashboard/rekapitulasi/cetak-restock-return" onsubmit="handleFormSubmit(this)">
                                  <?php echo csrf_field(); ?>
                                  <div class="form-group">
                                      <label class="form-label">
                                          <i class="bi bi-calendar-event" style="color: #6f42c1;"></i>
                                          Tanggal Mulai
                                      </label>
                                      <input type="date" class="form-control" name="tgl_awal"
                                          value="<?php echo e(date('Y-m-01')); ?>" required>
                                  </div>
                                  <div class="form-group">
                                      <label class="form-label">
                                          <i class="bi bi-calendar-check" style="color: #6f42c1;"></i>
                                          Tanggal Akhir
                                      </label>
                                      <input type="date" class="form-control" name="tgl_akhir"
                                          value="<?php echo e(date('Y-m-d')); ?>" required>
                                  </div>
                                  <div class="form-group">
                                      <label class="form-label">
                                          <i class="bi bi-shop" style="color: #6f42c1;"></i>
                                          Mitra Binaan
                                      </label>
                                      <select class="form-control" name="category_id">
                                          <option value="">Semua Mitra Binaan</option>
                                          <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                              <option value="<?php echo e($category->id); ?>"><?php echo e($category->nama); ?></option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      </select>
                                  </div>
                                  <button type="submit" class="btn btn-primary w-100" style="background: linear-gradient(135deg, #6f42c1, #8a2be2);">
                                      <i class="bi bi-download me-2"></i>Cetak Restock & Return
                                  </button>
                              </form>
                          </div>
                      </div>
                  </div>

                  <div class="col-xl-6 col-lg-6 mb-4">
                      <div class="main-card h-100">
                          <div class="card-header-custom" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                              <h5 class="mb-0">
                                  <i class="bi bi-wallet2 me-2"></i>
                                  Cetak Laporan Biaya Operasional
                              </h5>
                          </div>
                          <div class="form-section">
                              <form method="post" action="<?php echo e(route('reports.cetakBiayaOperasional')); ?>" onsubmit="handleFormSubmit(this)">
                                  <?php echo csrf_field(); ?>
                                  <div class="form-group">
                                      <label class="form-label">
                                          <i class="bi bi-calendar-event text-danger"></i>
                                          Tanggal Mulai
                                      </label>
                                      <input type="date" class="form-control" name="tgl_awal"
                                          value="<?php echo e(date('Y-m-01')); ?>" required>
                                  </div>
                                  <div class="form-group">
                                      <label class="form-label">
                                          <i class="bi bi-calendar-check text-danger"></i>
                                          Tanggal Akhir
                                      </label>
                                      <input type="date" class="form-control" name="tgl_akhir"
                                          value="<?php echo e(date('Y-m-d')); ?>" required>
                                  </div>
                                  <button type="submit" class="btn btn-danger w-100 mt-4">
                                      <i class="bi bi-download me-2"></i>Cetak Laporan Biaya Operasional
                                  </button>
                              </form>
                          </div>
                      </div>
                  </div>

                  <div class="col-xl-6 col-lg-6 mb-4">
                      <div class="main-card h-100">
                          <div class="card-header-custom" style="background: linear-gradient(135deg, #ffc107, #ff9800);">
                              <h5 class="mb-0">
                                  <i class="bi bi-cash-coin me-2"></i>
                                  Cetak Laporan Keuangan
                              </h5>
                          </div>
                          <div class="form-section">
                              <form method="post" action="<?php echo e(route('reports.cetakLaporanKeuangan')); ?>" onsubmit="handleFormSubmit(this)">
                                  <?php echo csrf_field(); ?>
                                  <div class="form-group">
                                      <label class="form-label">
                                          <i class="bi bi-calendar-event text-warning"></i>
                                          Tanggal Mulai
                                      </label>
                                      <input type="date" class="form-control" name="tgl_awal"
                                          value="<?php echo e(date('Y-m-01')); ?>" required>
                                  </div>
                                  <div class="form-group">
                                      <label class="form-label">
                                          <i class="bi bi-calendar-check text-warning"></i>
                                          Tanggal Akhir
                                      </label>
                                      <input type="date" class="form-control" name="tgl_akhir"
                                          value="<?php echo e(date('Y-m-d')); ?>" required>
                                  </div>
                                  <button type="submit" class="btn btn-primary w-100 mt-4" style="background: linear-gradient(135deg, #ffc107, #ff9800);">
                                      <i class="bi bi-download me-2"></i>Cetak Laporan Keuangan
                                  </button>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <script>
      function handleFormSubmit(form) {
          const button = form.querySelector('button[type="submit"]');
          if (button) {
              const originalButtonHTML = button.innerHTML;

              button.disabled = true;
              button.innerHTML = `
                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  Loading...
              `;

              setTimeout(function() {
                  button.disabled = false;
                  button.innerHTML = originalButtonHTML;
              }, 3000);
          }
      }
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LENOVO\Documents\SEMESTER 5\Magang\Gerai-umkm-mart-finalisasi-main\resources\views/dashboard/reports/index.blade.php ENDPATH**/ ?>