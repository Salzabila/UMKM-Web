<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Good;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Good::with('category');

        // Search
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('barcode', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter stok menipis
        if ($request->has('low_stock') && $request->low_stock == true) {
            $query->where('stok', '<=', 10);
        }

        $products = $query->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show($id)
    {
        $product = Good::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama' => 'required|string',
            'barcode' => 'nullable|string|unique:goods',
            'type' => 'required|in:makanan,non_makanan,lainnya,handycraft,fashion',
            'harga_awal' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'tgl_masuk' => 'required|date',
            'expired_date' => 'nullable|date',
            'is_grosir_active' => 'boolean',
            'min_qty_grosir' => 'nullable|integer',
            'harga_grosir' => 'nullable|numeric',
            'is_tebus_murah_active' => 'boolean',
            'min_tebus_murah' => 'nullable|numeric',
            'harga_tebus_murah' => 'nullable|numeric',
        ]);

        $product = Good::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Good::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'nama' => 'sometimes|string',
            'barcode' => 'sometimes|string|unique:goods,barcode,' . $id,
            'type' => 'sometimes|in:makanan,non_makanan,lainnya,handycraft,fashion',
            'harga_awal' => 'sometimes|numeric',
            'harga_jual' => 'sometimes|numeric',
            'stok' => 'sometimes|integer',
            'tgl_masuk' => 'sometimes|date',
            'expired_date' => 'nullable|date',
        ]);

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diupdate',
            'data' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Good::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    }
}