<?php

namespace App\Http\Controllers;

use App\Models\ReturnBarang;
use App\Models\Good;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        $query = ReturnBarang::query()->with(['good.category', 'user']);

        // Apply filters
        $query->filter(request(['search', 'category_id']));

        $sortApplied = false;

        // Sorting by tgl_return (return date)
        $sortTglReturn = request('sort_tgl_return');
        if ($sortTglReturn === 'asc') {
            $query->orderBy('tgl_return', 'asc');
            $sortApplied = true;
        } elseif ($sortTglReturn === 'desc') {
            $query->orderBy('tgl_return', 'desc');
            $sortApplied = true;
        }

        // Default sorting if no specific sort is applied
        if (!$sortApplied) {
            $query->latest(); // Order by created_at DESC
        }

        return view('dashboard.returns.index', [
            'active' => 'returns',
            'returns' => $query->paginate(10)->withQueryString(),
            'categories' => Category::all(), // Added categories for mitra filter
        ]);
    }

    public function create()
    {
        return view('dashboard.returns.create', [
            'active' => 'data',
            'goods' => Good::where('stok', '>', 0)->get(),
        ]);
    }

    public function searchGoods(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $goods = Good::where('stok', '>', 0)
            ->where(function($q) use ($query) {
                $q->where('nama', 'LIKE', '%' . $query . '%')
                  ->orWhere('barcode', 'LIKE', '%' . $query . '%');
            })
            ->select('id', 'nama', 'barcode', 'stok')
            ->limit(10)
            ->get();

        return response()->json($goods);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'good_id'     => 'required|exists:goods,id',
            'tgl_return'  => 'required|date',
            'qty_return'  => 'required|integer|min:1',
            'alasan'      => 'required|in:Rusak,Cacat,Kadaluarsa,Salah Kirim,Lainnya',
            'keterangan'  => 'nullable|string',
        ]);

        // Automatically set the user_id to the authenticated user
        $validatedData['user_id'] = auth()->user()->id;

        $good = Good::find($validatedData['good_id']);

        if ($validatedData['qty_return'] > $good->stok) {
            return back()->withErrors([
                'qty_return' => 'Jumlah return tidak boleh melebihi stok tersedia (' . $good->stok . ')'
            ])->withInput();
        }

        ReturnBarang::create($validatedData);

        $good->decrement('stok', $validatedData['qty_return']);

        return redirect('/dashboard/returns')->with('success', 'Return barang berhasil ditambahkan.');
    }

    public function edit(ReturnBarang $return)
    {
        return view('dashboard.returns.edit', [
            'active' => 'data',
            'return' => $return,
            'goods'  => Good::all(),
            'users'  => User::all(),
        ]);
    }

    public function update(Request $request, ReturnBarang $return)
    {
        $validatedData = $request->validate([
            'good_id'     => 'required|exists:goods,id',
            'user_id'     => 'required|exists:users,id',
            'tgl_return'  => 'required|date',
            'qty_return'  => 'required|integer|min:1',
            'alasan'      => 'required|in:Rusak,Cacat,Kadaluarsa,Salah Kirim,Lainnya',
            'keterangan'  => 'nullable|string',
        ]);

        $newGood = Good::find($validatedData['good_id']);
        $oldGood = Good::find($return->good_id);

        // Balikin stok lama
        $oldGood->increment('stok', $return->qty_return);

        // Validasi stok baru
        if ($validatedData['qty_return'] > $newGood->stok) {
            // Balikin ke stok awal karena gagal
            $oldGood->decrement('stok', $return->qty_return);

            return back()->withErrors([
                'qty_return' => 'Jumlah return tidak boleh melebihi stok tersedia (' . $newGood->stok . ')'
            ])->withInput();
        }

        $return->update($validatedData);

        $newGood->decrement('stok', $validatedData['qty_return']);

        return redirect('/dashboard/returns')->with('success', 'Data return berhasil diperbarui.');
    }

    public function destroy(ReturnBarang $return)
    {
        $good = Good::find($return->good_id);
        if ($good) {
            $good->increment('stok', $return->qty_return);
        }

        $return->delete();

        return redirect('/dashboard/returns')->with('success', 'Data return berhasil dihapus dan stok dikembalikan.');
    }

    /**
     * Menghapus beberapa data return sekaligus.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:returns,id',
        ]);

        $selectedIds = $request->input('selected_ids');

        if (empty($selectedIds)) {
            return redirect('/dashboard/returns')->with('error', 'Tidak ada data return yang dipilih untuk dihapus.');
        }

        $returns = ReturnBarang::whereIn('id', $selectedIds)->get();

        // Kembalikan stok untuk setiap barang yang di-return
        foreach ($returns as $return) {
            $good = Good::find($return->good_id);
            if ($good) {
                $good->increment('stok', $return->qty_return);
            }
        }

        // Hapus data return
        $deletedCount = ReturnBarang::destroy($selectedIds);

        if ($deletedCount > 0) {
            return redirect('/dashboard/returns')->with('success', $deletedCount . ' data return berhasil dihapus dan stok telah dikembalikan.');
        }

        return redirect('/dashboard/returns')->with('error', 'Gagal menghapus data return yang dipilih.');
    }
}
