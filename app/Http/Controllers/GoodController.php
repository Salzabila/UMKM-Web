<?php

namespace App\Http\Controllers;

use App\Models\Good;
use App\Models\Category;
use App\Http\Requests\StoreGoodRequest;
use App\Http\Requests\UpdateGoodRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Carbon\Carbon; 

class GoodController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      $query = Good::query()->with('category'); 

      // Filter by search term (nama or barcode)
      if (request('search')) {
          $query->where(function($q) {
              $q->where('nama', 'like', '%' . request('search') . '%')
                ->orWhere('barcode', 'like', '%' . request('search') . '%');
          });
      }

      // Filter by category_id (Mitra Binaan)
      if (request('category_id') && request('category_id') !== 'all') {
          $query->where('category_id', request('category_id'));
      }

      // --- Sorting Logic ---
      $sortApplied = false;

      // Sorting by expired_date
      $sortExpired = request('sort_expired');
      if ($sortExpired === 'asc') {
          $query->whereNotNull('expired_date')->orderBy('expired_date', 'asc');
          $sortApplied = true;
      } elseif ($sortExpired === 'desc') {
          $query->whereNotNull('expired_date')->orderBy('expired_date', 'desc');
          $sortApplied = true;
      }

      // Sorting by tgl_masuk
      $sortTglMasuk = request('sort_tgl_masuk');
      if ($sortTglMasuk === 'asc') {
          $query->orderBy('tgl_masuk', 'asc');
          $sortApplied = true;
      } elseif ($sortTglMasuk === 'desc') {
          $query->orderBy('tgl_masuk', 'desc');
          $sortApplied = true;
      }

      // Sorting by stok
      $sortStok = request('sort_stok');
      if ($sortStok === 'asc') {
          $query->orderBy('stok', 'asc');
          $sortApplied = true;
      } elseif ($sortStok === 'desc') {
          $query->orderBy('stok', 'desc');
          $sortApplied = true;
      }

      // Sorting by harga
      $sortHarga = request('sort_harga');
      if ($sortHarga === 'asc') {
          $query->orderBy('harga', 'asc');
          $sortApplied = true;
      } elseif ($sortHarga === 'desc') {
          $query->orderBy('harga', 'desc');
          $sortApplied = true;
      }

      // Default sorting if no specific sort is applied
      if (!$sortApplied) {
          $query->latest(); 
      }

      return view('dashboard.goods.index', [
          'active' => 'goods',
          'goods' => $query->paginate(7)->withQueryString(),
          'categories' => Category::all(), 
      ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
      return view('dashboard.goods.create', [
          'active' => 'data',
          'categories' => Category::all(),
      ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreGoodRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreGoodRequest $request)
  {
      $validatedData = $request->validate([
          'category_id' => 'required|exists:categories,id',
          'tgl_masuk' => 'required|date',
          'nama' => [
              'required',
              'string',
              'max:255',
              function ($attribute, $value, $fail) {
                  $exists = Good::whereRaw('LOWER(nama) = ?', [strtolower($value)])->exists();
                  if ($exists) {
                      $fail('Nama barang sudah ada (tidak boleh sama meskipun berbeda huruf besar/kecil).');
                  }
              },
          ],
          'type' => 'required|in:makanan,non_makanan,lainnya,handycraft,fashion',
          'expired_date' => 'nullable|date|after:today',
          'stok' => 'required|integer|min:0',
          'harga_asli' => 'required|numeric|min:0',
          'markup_percentage' => 'nullable|numeric|min:0|max:100', 
          'is_grosir_active' => 'boolean',
          'min_qty_grosir' => 'nullable|integer|min:2',
          'harga_grosir' => 'nullable|numeric|min:0',
          'is_tebus_murah_active' => 'boolean',
          'min_total_tebus_murah' => 'nullable|numeric|min:0',
          'harga_tebus_murah' => 'nullable|numeric|min:0',
          'use_existing_barcode' => 'boolean',
          'existing_barcode' => 'nullable|string|max:255',
      ]);

      if (in_array($validatedData['type'], ['makanan', 'non_makanan']) && empty($validatedData['expired_date'])) {
          return back()->withErrors(['expired_date' => 'Tanggal expired wajib diisi untuk jenis ' . $validatedData['type'] . '.'])->withInput();
      }

      if (in_array($validatedData['type'], ['lainnya', 'handycraft', 'fashion'])) {
          $validatedData['expired_date'] = null;
      }

      // Perhitungan harga jual berdasarkan markup_percentage atau default
      if ($request->has('markup_percentage') && $request->markup_percentage !== null) {
          $markup = $validatedData['markup_percentage'] / 100;
      } else {
          $markup = $validatedData['type'] === 'makanan' ? 0.02 : 0.05;
      }
      $validatedData['harga'] = $validatedData['harga_asli'] + ($validatedData['harga_asli'] * $markup);

      if ($request->has('is_grosir_active') && $request->is_grosir_active) {
          if (empty($validatedData['min_qty_grosir']) || empty($validatedData['harga_grosir'])) {
              return back()->withErrors(['min_qty_grosir' => 'Minimal Qty & Harga grosir wajib diisi.'])->withInput();
          }

          // Harga grosir tidak boleh lebih rendah dari harga asli (modal)
          if ($validatedData['harga_grosir'] < $validatedData['harga_asli']) {
              return back()->withErrors(['harga_grosir' => 'Harga grosir tidak boleh lebih rendah dari harga asli (modal).'])->withInput();
          }

          if ($validatedData['harga_grosir'] >= $validatedData['harga']) {
              return back()->withErrors(['harga_grosir' => 'Harga grosir harus lebih kecil dari harga eceran.'])->withInput();
          }

          $validatedData['is_grosir_active'] = true;
      } else {
          $validatedData['is_grosir_active'] = false;
          $validatedData['min_qty_grosir'] = null;
          $validatedData['harga_grosir'] = null;
      }

      if ($request->has('is_tebus_murah_active') && $request->is_tebus_murah_active) {
          if (empty($validatedData['min_total_tebus_murah']) || empty($validatedData['harga_tebus_murah'])) {
              return back()->withErrors(['min_total_tebus_murah' => 'Minimal Total & Harga tebus murah wajib diisi.'])->withInput();
          }

          // Harga tebus murah tidak boleh lebih rendah dari harga asli (modal)
          if ($validatedData['harga_tebus_murah'] < $validatedData['harga_asli']) {
              return back()->withErrors(['harga_tebus_murah' => 'Harga tebus murah tidak boleh lebih rendah dari harga asli (modal).'])->withInput();
          }

          if ($validatedData['harga_tebus_murah'] >= $validatedData['harga']) {
              return back()->withErrors(['harga_tebus_murah' => 'Harga tebus murah harus lebih kecil dari harga normal.'])->withInput();
          }

          $validatedData['is_tebus_murah_active'] = true;
      } else {
          $validatedData['is_tebus_murah_active'] = false;
          $validatedData['min_total_tebus_murah'] = null;
          $validatedData['harga_tebus_murah'] = null;
      }

      $validatedData['use_existing_barcode'] = $request->has('use_existing_barcode') && $request->use_existing_barcode ? true : false;
      
      if ($validatedData['use_existing_barcode'] && !empty($validatedData['existing_barcode'])) {
          // Check if barcode already exists
          $existingGood = Good::where('barcode', $validatedData['existing_barcode'])->first();
          if ($existingGood) {
              return back()->withErrors(['existing_barcode' => "BARCODE {$validatedData['existing_barcode']} TERDAFTAR SEBAGAI {$existingGood->nama}. APAKAH ANDA MAU MENGGANTI ATAU MEREPLACE BARCODE INI?"])->withInput();
          }
          $validatedData['barcode'] = $validatedData['existing_barcode'];
      } else {
          // Generate automatic barcode
          $validatedData['barcode'] = Good::generateBarcodeStatic($validatedData['type'], $validatedData['nama']);
          $validatedData['existing_barcode'] = null;
      }

      Good::create($validatedData);
      return redirect('/dashboard/goods')->with('success', 'Barang berhasil ditambahkan.');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Good  $good
   * @return \Illuminate\Http\Response
   */
  public function show(Good $good)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Good  $good
   * @return \Illuminate\Http\Response
   */
  public function edit(Good $good)
  {
      return view('dashboard.goods.edit', [
          'active' => 'data',
          'categories' => Category::all(),
          'good' => $good,
      ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateGoodRequest  $request
   * @param  \App\Models\Good  $good
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateGoodRequest $request, Good $good)
  {
      $rules = [
          'category_id' => 'required|exists:categories,id',
          'tgl_masuk' => 'required|date',
          'nama' => [
              'required',
              'string',
              'max:255',
              function ($attribute, $value, $fail) use ($good) {
                  $exists = Good::whereRaw('LOWER(nama) = ?', [strtolower($value)])
                      ->where('id', '!=', $good->id)
                      ->exists();
                  if ($exists) {
                      $fail('Nama barang sudah ada (tidak boleh sama meskipun berbeda huruf besar/kecil).');
                  }
              },
          ],
          'type' => 'required|in:makanan,non_makanan,lainnya,handycraft,fashion',
          'expired_date' => 'nullable|date|after:today',
          'harga_asli' => 'required|numeric|min:0',
          'markup_percentage' => 'nullable|numeric|min:0|max:100', 
          'is_grosir_active' => 'boolean',
          'min_qty_grosir' => 'nullable|integer|min:2',
          'harga_grosir' => 'nullable|numeric|min:0',
          'is_tebus_murah_active' => 'boolean',
          'min_total_tebus_murah' => 'nullable|numeric|min:0',
          'harga_tebus_murah' => 'nullable|numeric|min:0',
          'use_existing_barcode' => 'boolean',
          'existing_barcode' => 'nullable|string|max:255',
      ];

      $validatedData = $request->validate($rules);

      if (in_array($validatedData['type'], ['makanan', 'non_makanan']) && empty($validatedData['expired_date'])) {
          return back()->withErrors(['expired_date' => 'Tanggal expired wajib diisi untuk jenis ' . $validatedData['type'] . '.'])->withInput();
      }

      if (in_array($validatedData['type'], ['lainnya', 'handycraft', 'fashion'])) {
          $validatedData['expired_date'] = null;
      }

      // Perhitungan harga jual berdasarkan markup_percentage atau default
      if ($request->has('markup_percentage') && $request->markup_percentage !== null) {
          $markup = $validatedData['markup_percentage'] / 100;
      } else {
          $markup = $validatedData['type'] === 'makanan' ? 0.02 : 0.05;
      }
      $validatedData['harga'] = $validatedData['harga_asli'] + ($validatedData['harga_asli'] * $markup);

      if ($request->has('is_grosir_active') && $request->is_grosir_active) {
          if (empty($validatedData['min_qty_grosir']) || empty($validatedData['harga_grosir'])) {
              return back()->withErrors(['min_qty_grosir' => 'Minimal pembelian grosir wajib diisi jika grosir diaktifkan.'])->withInput();
          }

          // Harga grosir tidak boleh lebih rendah dari harga asli (modal)
          if ($validatedData['harga_grosir'] < $validatedData['harga_asli']) {
              return back()->withErrors(['harga_grosir' => 'Harga grosir tidak boleh lebih rendah dari harga asli (modal).'])->withInput();
          }

          if ($validatedData['harga_grosir'] >= $validatedData['harga']) {
              return back()->withErrors(['harga_grosir' => 'Harga grosir harus lebih kecil dari harga eceran.'])->withInput();
          }

          $validatedData['is_grosir_active'] = true;
      } else {
          $validatedData['is_grosir_active'] = false;
          $validatedData['min_qty_grosir'] = null;
          $validatedData['harga_grosir'] = null;
      }

      if ($request->has('is_tebus_murah_active') && $request->is_tebus_murah_active) {
          if (empty($validatedData['min_total_tebus_murah']) || empty($validatedData['harga_tebus_murah'])) {
              return back()->withErrors(['min_total_tebus_murah' => 'Minimal pembelian tebus murah wajib diisi jika tebus murah diaktifkan.'])->withInput();
          }

          // Harga tebus murah tidak boleh lebih rendah dari harga asli (modal)
          if ($validatedData['harga_tebus_murah'] < $validatedData['harga_asli']) {
              return back()->withErrors(['harga_tebus_murah' => 'Harga tebus murah tidak boleh lebih rendah dari harga asli (modal).'])->withInput();
          }

          if ($validatedData['harga_tebus_murah'] >= $validatedData['harga']) {
              return back()->withErrors(['harga_tebus_murah' => 'Harga tebus murah harus lebih kecil dari harga normal.'])->withInput();
          }

          $validatedData['is_tebus_murah_active'] = true;
      } else {
          $validatedData['is_tebus_murah_active'] = false;
          $validatedData['min_total_tebus_murah'] = null;
          $validatedData['harga_tebus_murah'] = null;
      }

      $validatedData['use_existing_barcode'] = $request->has('use_existing_barcode') && $request->use_existing_barcode ? true : false;
      
      if ($validatedData['use_existing_barcode'] && !empty($validatedData['existing_barcode'])) {
          // Check if barcode already exists (excluding current good)
          $existingGood = Good::where('barcode', $validatedData['existing_barcode'])
              ->where('id', '!=', $good->id)
              ->first();
          if ($existingGood) {
              return back()->withErrors(['existing_barcode' => "BARCODE {$validatedData['existing_barcode']} TERDAFTAR SEBAGAI {$existingGood->nama}. APAKAH ANDA MAU MENGGANTI ATAU MEREPLACE BARCODE INI?"])->withInput();
          }
          $validatedData['barcode'] = $validatedData['existing_barcode'];
      } else {
          if (!$good->barcode) {
              $validatedData['barcode'] = Good::generateBarcodeStatic($validatedData['type'], $validatedData['nama']);
          } else {
              $validatedData['barcode'] = $good->barcode;
          }
          $validatedData['existing_barcode'] = null;
      }

      $good->update($validatedData);
      return redirect('/dashboard/goods')->with('success', 'Barang berhasil diubah.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Good  $good
   * @return \Illuminate\Http\Response
   */
  public function destroy(Good $good)
  {
      Good::destroy($good->id);
      return redirect('/dashboard/goods')->with('success', 'Barang telah dihapus.');
  }

  /**
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function bulkDelete(Request $request)
  {
      $request->validate([
          'selected_ids' => 'required|array',
          'selected_ids.*' => 'exists:goods,id',
      ]);

      $selectedIds = $request->input('selected_ids');

      if (empty($selectedIds)) {
          return redirect('/dashboard/goods')->with('error', 'Tidak ada barang yang dipilih untuk dihapus.');
      }

      $deletedCount = Good::whereIn('id', $selectedIds)->delete();

      if ($deletedCount > 0) {
          return redirect('/dashboard/goods')->with('success', $deletedCount . ' barang berhasil dihapus!');
      }

      return redirect('/dashboard/goods')->with('error', 'Gagal menghapus barang yang dipilih.');
  }

  /**
   * Generate new barcode for existing product
   */
  public function generateBarcode(Good $good)
  {
      $good->update(['barcode' => Good::generateBarcodeStatic($good->type, $good->nama)]);
      return redirect()->back()->with('success', 'Barcode baru telah dibuat.');
  }

  /**
   * Download barcode as PDF
   */
  public function downloadBarcode(Good $good)
  {
      if (!$good->barcode) {
          return redirect()->back()->with('error', 'Barang ini belum memiliki barcode.');
      }

      $pdf = PDF::loadView('barcode_pdf', ['good' => $good]);
      return $pdf->download('barcode-' . $good->barcode . '.pdf');
  }

  /**
   * Print barcode view
   */
  public function printBarcode(Good $good)
  {
      if (!$good->barcode) {
          return redirect()->back()->with('error', 'Barang ini belum memiliki barcode.');
      }

      return view('dashboard.goods.print-barcode', [
          'good' => $good,
          'active' => 'data'
      ]);
  }

  public function exportpdf()
  {
      $goods = Good::with('category')->get();
      $pdf = PDF::loadview('good_pdf', ['goods' => $goods]);
      $pdf->setPaper('A4', 'landscape');
      return $pdf->download('laporan-barang.pdf');
  }

  /**
   * Show the form for selecting multiple goods to print barcodes.
   *
   * @return \Illuminate\Http\Response
   */
  public function showPrintMultipleBarcodesForm()
  {
      return view('dashboard.goods.cetakbarcode', [
          'active' => 'data',
          'goods' => Good::latest()->get(),
      ]);
  }

  /**
   * Generate PDF with multiple selected barcodes.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function generateMultipleBarcodesPdf(Request $request)
  {
      $request->validate([
          'selected_goods' => 'required|array|min:1|max:52',
          'selected_goods.*' => 'exists:goods,id',
      ], [
          'selected_goods.required' => 'Pilih setidaknya satu barang untuk dicetak.',
          'selected_goods.min' => 'Pilih setidaknya satu barang untuk dicetak.',
          'selected_goods.max' => 'Anda hanya dapat memilih maksimal 52 barang untuk dicetak dalam satu lembar.',
          'selected_goods.*.exists' => 'Salah satu barang yang dipilih tidak valid.',
      ]);

      $selectedGoodIds = $request->input('selected_goods');
      $goodsToPrint = Good::whereIn('id', $selectedGoodIds)->get();

      if ($goodsToPrint->isEmpty()) {
          return redirect()->back()->with('error', 'Tidak ada barang yang dipilih atau barang tidak ditemukan.');
      }

      $generator = new BarcodeGeneratorSVG();
      $barcodesData = [];

      foreach ($goodsToPrint as $good) {
          if ($good->barcode) {
              $barcodeSvg = $generator->getBarcode($good->barcode, $generator::TYPE_CODE_128);
              $barcodesData[] = [
                  'nama' => $good->nama,
                  'harga' => $good->harga,
                  'barcode_value' => $good->barcode,
                  'barcode_svg' => $barcodeSvg,
              ];
          }
      }

      if (empty($barcodesData)) {
          return redirect()->back()->with('error', 'Tidak ada barcode yang valid untuk barang yang dipilih.');
      }

      $pdf = PDF::loadView('multiple_barcode_pdf', [
          'barcodesData' => $barcodesData
      ]);

      $pdf->setPaper('A4', 'portrait');
      return $pdf->download('multiple-barcodes.pdf');
  }
}
