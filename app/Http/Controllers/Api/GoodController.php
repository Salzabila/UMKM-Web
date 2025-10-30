<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Good::with('category');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('barcode', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter stok menipis
        if ($request->has('low_stock') && $request->low_stock == 'true') {
            $query->where('stok', '<=', 10);
        }

        // Filter will expire
        if ($request->has('will_expire') && $request->will_expire == 'true') {
            $today = now();
            $query->whereNotNull('expired_date')
                  ->whereDate('expired_date', '<=', $today->copy()->addDays(7))
                  ->whereDate('expired_date', '>=', $today);
        }

        // Pagination
        $perPage = $request->input('per_page', 20);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
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
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'nama' => 'required|string|max:255',
            'barcode' => 'nullable|string|unique:goods',
            'type' => 'required|in:makanan,non_makanan,lainnya,handycraft,fashion',
            'harga_awal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'tgl_masuk' => 'required|date',
            'expired_date' => 'nullable|date',
            'is_grosir_active' => 'boolean',
            'min_qty_grosir' => 'nullable|integer',
            'harga_grosir' => 'nullable|numeric',
            'is_tebus_murah_active' => 'boolean',
            'min_tebus_murah' => 'nullable|numeric',
            'harga_tebus_murah' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Good::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product->load('category')
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

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:categories,id',
            'nama' => 'sometimes|string|max:255',
            'barcode' => 'sometimes|string|unique:goods,barcode,' . $id,
            'type' => 'sometimes|in:makanan,non_makanan,lainnya,handycraft,fashion',
            'harga_awal' => 'sometimes|numeric|min:0',
            'harga_jual' => 'sometimes|numeric|min:0',
            'stok' => 'sometimes|integer|min:0',
            'tgl_masuk' => 'sometimes|date',
            'expired_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $product->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diupdate',
            'data' => $product->load('category')
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

    public function scanBarcode($barcode)
    {
        $product = Good::with('category')
            ->where('barcode', $barcode)
            ->first();

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
}

