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
            'stok_sebelum' => $stokSebelum,
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
