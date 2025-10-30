<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Good;
use App\Models\Transaction;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('dashboard.orders.index', [
            'active' => 'data',
            'no_nota' => $request->no_nota,
            'orders' => Order::where('no_nota', $request->no_nota)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $currentTotal = Order::where('no_nota', $request->no_nota)->sum('subtotal');
        
        return view('dashboard.orders.create', [
            'active' => 'data',
            'goods' => Good::all(),
            'no_nota' => $request->no_nota,
            'currentTotal' => $currentTotal, 
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        $barang = Good::where('id', $request['good_id'])->first();
        
        $qty = $request->qty;
        $currentTotal = Order::where('no_nota', $request->no_nota)->sum('subtotal');
        
        // Default ke harga normal
        $unitPrice = $barang->harga;
        $priceType = 'normal';
        
        // Cek tebus murah terlebih dahulu (prioritas lebih tinggi)
        if ($barang->is_tebus_murah_active && 
            $currentTotal >= $barang->min_total_tebus_murah && 
            $barang->harga_tebus_murah > 0) {
            $unitPrice = $barang->harga_tebus_murah;
            $priceType = 'tebus_murah';
        }
        // cek harga grosir
        elseif ($barang->is_grosir_active && 
                $qty >= $barang->min_qty_grosir && 
                $barang->harga_grosir > 0) {
            $unitPrice = $barang->harga_grosir;
            $priceType = 'grosir';
        }
        
        $subtotal = $unitPrice * $qty;
        
        Order::create([
            'no_nota' => $request->no_nota,
            'good_id' => $request->good_id,
            'qty' => $qty,
            'subtotal' => $subtotal,
            'price' => $unitPrice,
        ]);
        
        Good::find($request->good_id)->decrement('stok', $qty);
        
        $successMessage = 'Pesanan telah ditambahkan.';
        if ($priceType === 'tebus_murah') {
            $penghematan = ($barang->harga - $unitPrice) * $qty;
            $successMessage = 'Pesanan telah ditambahkan dengan harga TEBUS MURAH! Penghematan: Rp ' . number_format($penghematan, 0, ',', '.');
        } elseif ($priceType === 'grosir') {
            $penghematan = ($barang->harga - $unitPrice) * $qty;
            $successMessage = 'Pesanan telah ditambahkan dengan harga GROSIR! Penghematan: Rp ' . number_format($penghematan, 0, ',', '.');
        }
        
        return view('dashboard.orders.index', [
            'no_nota' => $request['no_nota'],
            'orders' => Order::where('no_nota', $request['no_nota'])->get(),
            'goods' => Good::all(),
            'active' => 'cashier',
        ])->with('success', $successMessage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function checkout(Request $request)
    {
        $transaction = Transaction::where('no_nota', $request->no_nota)->first();
        
        // Logika kembalian dibalik agar benar
        // Rumus : Uang Bayar - Total Harga
        $kembalian = $transaction['bayar'] - $request->total_harga;

        $rules = [
            'no_nota' => $request->no_nota,
            'total_harga' => $request->total_harga,
            'kembalian' => $kembalian,
        ];

        Transaction::where('no_nota', $rules['no_nota'])->update($rules);

        return redirect('/dashboard/transactions')->with('success', 'Sukses!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        
        // 1. Ambil informasi penting SEBELUM data order dihapus.
        $no_nota = $order->no_nota;
        
        // 2. Kembalikan stok barang yang dihapus dari keranjang.
        //    Gunakan 'increment' untuk menambah kembali stok.
        Good::find($order->good_id)->increment('stok', $order->qty);
        
        // 3. Hapus item order dari database.
        $order->delete();
        
        // 4. Redirect ke route 'orders.index' dengan metode GET
        //    dan membawa parameter no_nota di URL.
        return redirect()->route('orders.index', ['no_nota' => $no_nota])
                         ->with('success', 'Item telah dihapus dari keranjang!');
    }
}
