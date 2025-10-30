<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Sesuaikan dengan model yang ada
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    // Method-method lain yang sudah ada...

    /**
     * Scan barcode dan cari produk
     */
    public function scanBarcode(Request $request): JsonResponse
    {
        try {
            // Validasi input
            $request->validate([
                'barcode' => 'required|string|max:100'
            ]);

            $barcode = $request->input('barcode');
            
            // Cari produk berdasarkan barcode
            $product = Product::where('barcode', $barcode)->first();
            
            if ($product) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk ditemukan',
                    'data' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'barcode' => $product->barcode,
                        'price' => $product->price,
                        'stock' => $product->stock,
                        // Tambahkan field lain sesuai kebutuhan
                    ]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Produk dengan barcode ' . $barcode . ' tidak ditemukan',
                'data' => null
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Update produk dengan barcode
     */
    public function updateWithBarcode(Request $request, $id)
    {
        try {
            $request->validate([
                'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $id,
                // Tambahkan validasi field lain sesuai kebutuhan
            ]);

            $product = Product::findOrFail($id);
            
            // Update barcode jika ada
            if ($request->has('barcode')) {
                $product->barcode = $request->barcode;
            }
            
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diupdate',
                'data' => $product
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update produk: ' . $e->getMessage()
            ], 500);
        }
    }
}