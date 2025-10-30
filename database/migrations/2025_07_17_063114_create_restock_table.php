<?php

namespace App\Http\Controllers;

use App\Models\Good;
use App\Models\Restock;
use App\Models\Category;
use Illuminate\Http\Request;

class RestockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Good::with('category')
            ->filter(request(['search', 'mitra']));

        // Sorting stok
        if (request('sort_stok')) {
            $direction = request('sort_stok') === 'asc' ? 'asc' : 'desc';
            $query->orderBy('stok', $direction);
        }

        // Sorting mitra (kategori)
        if (request('sort_mitra')) {
            $direction = request('sort_mitra') === 'asc' ? 'asc' : 'desc';
            $query->join('categories', 'goods.category_id', '=', 'categories.id')
                ->orderBy('categories.nama', $direction)
                ->select('goods.*');
        }

        // Filter berdasarkan status stok
        if (request('filter_status')) {
            switch (request('filter_status')) {
                case 'aman':
                    $query->where('stok', '>', 20);
                    break;
                case 'sedang':
                    $query->whereBetween('stok', [6, 20]);
                    break;
                case 'rendah':
                    $query->where('stok', '<=', 5);
                    break;
            }
        }

        // Riwayat restock
        $restockHistory = Restock::with(['good.category', 'user'])
            ->orderBy('tgl_restock', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'history_page');

        return view('dashboard.restock.index', [
            'active' => 'restock',
            'goods' => $query->paginate(10)->withQueryString(),
            'categories' => Category::all(),
            'restockHistory' => $restockHistory,
        ]);
    }

    /**
     * Show the form for creating new product with initial stock (Restock Barang Baru)
     */
    public function restockBaru()
    {
        $categories = Category::orderBy('nama')->get();
        
        return view('dashboard.restock.baru', [
            'active' => 'restock',
            'categories' => $categories,
        ]);
    }

    /**
     * Store new product with initial stock (Restock Barang Baru)
     */
    public function storeRestock(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255|unique:goods,nama',
            'category_id' => 'required|exists:categories,id',
            'jumlah_barang' => 'required|integer|min:1',
            'tgl_masuk' => 'required|date',
            'tgl_expired' => 'nullable|date|after:tgl_masuk',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'nama.required' => 'Nama barang wajib diisi',
            'nama.unique' => 'Nama barang sudah terdaftar',
            'category_id.required' => 'Kategori/Mitra wajib dipilih',
            'jumlah_barang.required' => 'Jumlah barang wajib diisi',
            'jumlah_barang.min' => 'Jumlah barang minimal 1',
            'tgl_masuk.required' => 'Tanggal masuk wajib diisi',
            'tgl_expired.after' => 'Tanggal expired harus setelah tanggal masuk',
            'harga_beli.required' => 'Harga beli wajib diisi',
            'harga_jual.required' => 'Harga jual wajib diisi',
            'harga_jual.gte' => 'Harga jual harus lebih besar atau sama dengan harga beli',
        ]);

        \DB::beginTransaction();

        try {
            // Create new product
            $good = Good::create([
                'nama' => $validatedData['nama'],
                'category_id' => $validatedData['category_id'],
                'stok' => $validatedData['jumlah_barang'],
                'harga_beli' => $validatedData['harga_beli'],
                'harga_jual' => $validatedData['harga_jual'],
                'tgl_masuk' => $validatedData['tgl_masuk'],
                'tgl_expired' => $validatedData['tgl_expired'],
            ]);

            // Record restock history
            Restock::create([
                'good_id' => $good->id,
                'user_id' => auth()->id(),
                'qty_restock' => $validatedData['jumlah_barang'],
                'keterangan' => $validatedData['keterangan'] ?? 'Stok awal barang baru',
                'tgl_restock' => $validatedData['tgl_masuk'],
            ]);

            \DB::commit();

            return redirect()->route('restock.index')
                ->with('success', 'Barang baru "' . $good->nama . '" berhasil ditambahkan dengan stok awal ' . $validatedData['jumlah_barang'] . ' unit');

        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Restock existing product (Restock Barang Eksisting)
     */
    public function restockEksisting(Request $request)
    {
        $validatedData = $request->validate([
            'good_id' => 'required|exists:goods,id',
            'jumlah_restock' => 'required|integer|min:1',
            'tgl_masuk' => 'required|date',
            'tgl_expired' => 'nullable|date|after:tgl_masuk',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'good_id.required' => 'Barang wajib dipilih',
            'jumlah_restock.required' => 'Jumlah restock wajib diisi',
            'jumlah_restock.min' => 'Jumlah restock minimal 1',
            'tgl_masuk.required' => 'Tanggal masuk wajib diisi',
            'tgl_expired.after' => 'Tanggal expired harus setelah tanggal masuk',
            'harga_beli.required' => 'Harga beli wajib diisi',
        ]);

        \DB::beginTransaction();

        try {
            $good = Good::findOrFail($validatedData['good_id']);
            
            $stokSebelum = $good->stok;
            $stokBaru = $stokSebelum + $validatedData['jumlah_restock'];

            // Update stock
            $good->stok = $stokBaru;
            
            // Update selling price if provided
            if ($request->filled('harga_jual')) {
                $good->harga_jual = $validatedData['harga_jual'];
            }
            
            // Update purchase price (using weighted average)
            $totalNilaiLama = $stokSebelum * $good->harga_beli;
            $totalNilaiBaru = $validatedData['jumlah_restock'] * $validatedData['harga_beli'];
            $totalStok = $stokBaru;
            
            if ($totalStok > 0) {
                $good->harga_beli = ($totalNilaiLama + $totalNilaiBaru) / $totalStok;
            }
            
            // Update dates if this is a newer batch
            if ($validatedData['tgl_masuk'] > $good->tgl_masuk) {
                $good->tgl_masuk = $validatedData['tgl_masuk'];
            }
            
            if ($request->filled('tgl_expired')) {
                if (!$good->tgl_expired || $validatedData['tgl_expired'] < $good->tgl_expired) {
                    $good->tgl_expired = $validatedData['tgl_expired'];
                }
            }
            
            $good->save();

            // Record restock history
            Restock::create([
                'good_id' => $good->id,
                'user_id' => auth()->id(),
                'qty_restock' => $validatedData['jumlah_restock'],
                'keterangan' => $validatedData['keterangan'] ?? 'Restock barang eksisting',
                'tgl_restock' => $validatedData['tgl_masuk'],
            ]);

            \DB::commit();

            return redirect()->route('restock.index')
                ->with('success', 'Stok barang "' . $good->nama . '" berhasil ditambahkan sebanyak ' . $validatedData['jumlah_restock'] . ' unit. Total stok sekarang: ' . $stokBaru . ' unit');

        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update stock for existing product (method existing untuk backward compatibility)
     */
    public function updateStock(Request $request)
    {
        return $this->restockEksisting($request);
    }

    /**
     * Show the form for editing the specified Good (for restock).
     */
    public function edit(Good $good)
    {
        return view('dashboard.restock.edit', [
            'active' => 'restock',
            'good' => $good,
        ]);
    }

    /**
     * Show the form for editing a specific restock record.
     */
    public function editRestock(Restock $restock)
    {
        return view('dashboard.restock.edit-restock', [
            'active' => 'restock',
            'restock' => $restock,
        ]);
    }

    /**
     * Update (add stock) for a specific Good.
     */
    public function update(Request $request, Good $good)
    {
        $validatedData = $request->validate([
            'stok_tambahan' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $stokSebelum = $good->stok;
        $stokBaru = $stokSebelum + $validatedData['stok_tambahan'];

        // Update stok barang
        $good->update(['stok' => $stokBaru]);

        // Catat riwayat restock
        Restock::create([
            'good_id' => $good->id,
            'user_id' => auth()->user()->id,
            'qty_restock' => $validatedData['stok_tambahan'],
            'keterangan' => $validatedData['keterangan'] ?? null,
            'tgl_restock' => now()->format('Y-m-d'),
        ]);

        return redirect('/dashboard/restock')->with('success',
            "Berhasil menambah stok {$good->nama} sebanyak {$validatedData['stok_tambahan']} unit. Stok sekarang: {$stokBaru} unit.");
    }

    /**
     * Update a specific restock record (edit data restock).
     */
    public function updateRestock(Request $request, Restock $restock)
    {
        $validatedData = $request->validate([
            'qty_restock' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
            'tgl_restock' => 'required|date',
        ]);

        $oldQty = $restock->qty_restock;
        $newQty = $validatedData['qty_restock'];
        $qtyDifference = $newQty - $oldQty;

        $good = $restock->good;
        $newStock = $good->stok + $qtyDifference;

        if ($newStock < 0) {
            return back()->withErrors([
                'qty_restock' => 'Jumlah restock tidak dapat dikurangi karena akan membuat stok menjadi negatif.'
            ])->withInput();
        }

        // Update stok barang dan riwayat restock
        $good->update(['stok' => $newStock]);
        $restock->update($validatedData);

        return redirect('/dashboard/restock')->with('success',
            "Berhasil mengubah data restock {$good->nama}. Stok sekarang: {$newStock} unit.");
    }

    /**
     * Delete a single restock record.
     */
    public function destroy(Restock $restock)
    {
        $good = $restock->good;
        $qtyToRestore = $restock->qty_restock;
        $newStock = $good->stok - $qtyToRestore;

        if ($newStock < 0) {
            return redirect('/dashboard/restock')->with('error',
                'Tidak dapat menghapus restock ini karena akan membuat stok menjadi negatif.');
        }

        $good->update(['stok' => $newStock]);
        $restock->delete();

        return redirect('/dashboard/restock')->with('success',
            "Berhasil menghapus data restock {$good->nama}. Stok dikembalikan menjadi: {$newStock} unit.");
    }

    /**
     * Delete multiple restock records at once.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:restocks,id',
        ]);

        $selectedIds = $request->input('selected_ids');

        if (empty($selectedIds)) {
            return redirect('/dashboard/restock')->with('error', 'Tidak ada data restock yang dipilih untuk dihapus.');
        }

        $restocks = Restock::whereIn('id', $selectedIds)->with('good')->get();
        $deletedCount = 0;
        $errors = [];

        foreach ($restocks as $restock) {
            $good = $restock->good;
            if (!$good) continue;

            $newStock = $good->stok - $restock->qty_restock;

            if ($newStock < 0) {
                $errors[] = "Tidak dapat menghapus restock untuk {$good->nama} (akan membuat stok negatif)";
                continue;
            }

            $good->update(['stok' => $newStock]);
            $restock->delete();
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            $message = "{$deletedCount} data restock berhasil dihapus dan stok telah disesuaikan.";
            if (!empty($errors)) {
                $message .= ' Beberapa data tidak dapat dihapus: ' . implode(', ', $errors);
            }
            return redirect('/dashboard/restock')->with('success', $message);
        }

        return redirect('/dashboard/restock')->with('error', 'Gagal menghapus data restock yang dipilih.');
    }
}