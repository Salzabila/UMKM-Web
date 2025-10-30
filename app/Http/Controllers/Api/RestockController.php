<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Mitra;
use Carbon\Carbon;

class RestockController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        return view('restock.index', compact('barang'));
    }

    // FORM Restock Awal (barang baru)
    public function restockBaru()
    {
        $mitra = Mitra::all();
        return view('restock.baru', compact('mitra'));
    }

    // FORM Restock Barang Eksisting (berdasarkan barcode)
    public function restockEksisting(Request $request)
    {
        $barcode = $request->barcode;
        $barang = Barang::where('barcode', $barcode)->first();

        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan!');
        }

        return view('restock.eksisting', compact('barang'));
    }

    // Simpan hasil restock (barang baru / eksisting)
    public function storeRestock(Request $request)
    {
        if ($request->tipe == 'baru') {
            Barang::create([
                'nama_barang' => $request->nama_barang,
                'jumlah' => $request->jumlah,
                'mitra_id' => $request->mitra_id,
                'tanggal_masuk' => Carbon::parse($request->tanggal_masuk),
                'tanggal_expired' => Carbon::parse($request->tanggal_expired),
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'barcode' => $request->barcode,
            ]);
        } else {
            $barang = Barang::findOrFail($request->barang_id);
            $barang->jumlah += $request->jumlah;
            $barang->save();
        }

        return redirect()->route('restock.index')->with('success', 'Restock berhasil!');
    }
}
