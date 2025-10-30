<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReturnBarang;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnBarang::with('good', 'user')->orderBy('created_at', 'desc');

        // Filter by date range
        if ($request->has('start_date')) {
            $query->whereDate('tgl_return', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('tgl_return', '<=', $request->end_date);
        }

        // Pagination
        $perPage = $request->input('per_page', 20);
        $returns = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $returns->items(),
            'current_page' => $returns->currentPage(),
            'last_page' => $returns->lastPage(),
            'per_page' => $returns->perPage(),
            'total' => $returns->total(),
        ]);
    }

    public function show($id)
    {
        $return = ReturnBarang::with('good', 'user')->find($id);

        if (!$return) {
            return response()->json([
                'success' => false,
                'message' => 'Data return tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $return
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'good_id' => 'required|exists:goods,id',
            'user_id' => 'required|exists:users,id',
            'tgl_return' => 'required|date',
            'qty_return' => 'required|integer|min:1',
            'alasan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            // Check if product has enough stock to return
            $product = Good::find($request->good_id);
            
            if ($product->stok < $request->qty_return) {
                throw new \Exception("Stok {$product->nama} tidak mencukupi untuk dikembalikan");
            }

            // Create return record
            $return = ReturnBarang::create([
                'good_id' => $request->good_id,
                'user_id' => $request->user_id,
                'tgl_return' => $request->tgl_return,
                'qty_return' => $request->qty_return,
                'alasan' => $request->alasan,
            ]);

            // Decrease product stock
            $product->decrement('stok', $request->qty_return);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Return berhasil dicatat',
                'data' => $return->load('good', 'user')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Return gagal: ' . $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id)
    {
        $return = ReturnBarang::find($id);

        if (!$return) {
            return response()->json([
                'success' => false,
                'message' => 'Data return tidak ditemukan'
            ], 404);
        }

        DB::beginTransaction();
        
        try {
            // Rollback stock (add back)
            $product = Good::find($return->good_id);
            $product->increment('stok', $return->qty_return);

            // Delete return
            $return->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Return berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus return: ' . $e->getMessage()
            ], 400);
        }
    }
}

