<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiayaOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BiayaOperasionalController extends Controller
{
    public function index(Request $request)
    {
        $query = BiayaOperasional::orderBy('created_at', 'desc');

        // Filter by date range
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Pagination
        $perPage = $request->input('per_page', 20);
        $operasional = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $operasional->items(),
            'current_page' => $operasional->currentPage(),
            'last_page' => $operasional->lastPage(),
            'per_page' => $operasional->perPage(),
            'total' => $operasional->total(),
        ]);
    }

    public function show($id)
    {
        $operasional = BiayaOperasional::find($id);

        if (!$operasional) {
            return response()->json([
                'success' => false,
                'message' => 'Data biaya operasional tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $operasional
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uraian' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $operasional = BiayaOperasional::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Biaya operasional berhasil ditambahkan',
            'data' => $operasional
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $operasional = BiayaOperasional::find($id);

        if (!$operasional) {
            return response()->json([
                'success' => false,
                'message' => 'Data biaya operasional tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'uraian' => 'sometimes|string|max:255',
            'nominal' => 'sometimes|numeric|min:0',
            'qty' => 'sometimes|integer|min:1',
            'tanggal' => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $operasional->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Biaya operasional berhasil diupdate',
            'data' => $operasional
        ]);
    }

    public function destroy($id)
    {
        $operasional = BiayaOperasional::find($id);

        if (!$operasional) {
            return response()->json([
                'success' => false,
                'message' => 'Data biaya operasional tidak ditemukan'
            ], 404);
        }

        $operasional->delete();

        return response()->json([
            'success' => true,
            'message' => 'Biaya operasional berhasil dihapus'
        ]);
    }

    public function summary(Request $request)
    {
        $query = BiayaOperasional::query();

        // Filter by month/year
        if ($request->has('month') && $request->has('year')) {
            $query->whereMonth('tanggal', $request->month)
                  ->whereYear('tanggal', $request->year);
        }

        $total = $query->sum('nominal');
        $count = $query->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'count' => $count,
            ]
        ]);
    }
}
