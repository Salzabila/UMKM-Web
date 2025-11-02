<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Good;
use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Log;

class CashierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.cashiers.index', [
            'active' => 'cashier',
            'transactions' => Transaction::latest()->paginate(10), 
        ]);
    }

    /**
     * Quick create transaction and redirect to order creation
     *
     * @return \Illuminate\Http\Response
     */
    public function quickTransaction()
    {
        // Generate nomor nota 
        $tanggal = date('Ymd'); 
        $waktu = date('His');   
        $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT); // 001-999
        $no_nota_baru = $tanggal . '-' . $waktu . '-' . $random; 

        // Generate nomor nota unik
        while (Transaction::where('no_nota', $no_nota_baru)->exists()) {
            $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $no_nota_baru = $tanggal . '-' . $waktu . '-' . $random;
        }

        // Create transaction with current user and default payment method
        $transactionData = [
            'no_nota' => $no_nota_baru,
            'tgl_transaksi' => date('Y-m-d'),
            'user_id' => auth()->user()->id, 
            'metode_pembayaran' => 'CASH', 
            'status' => 'gagal',
            'total_harga' => 0,
            'bayar' => 0,
            'kembalian' => 0,
        ];

        Transaction::create($transactionData);

        return redirect('/dashboard/cashier/createorder?no_nota=' . $no_nota_baru);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createtransaction()
    {
        return view('dashboard.cashiers.transaction.create', [
            'active' => 'cashier',
            'users' => User::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createorder(Request $request)
    {
        // Handle both GET and POST requests
        $no_nota = $request->input('no_nota') ?? $request->get('no_nota');

        if (!$no_nota) {
            return redirect('/dashboard/cashier/createtransaction')->with('error', 'Nomor nota tidak ditemukan. Silakan buat transaksi baru.');
        }

        // Get or create transaction
        $transaction = Transaction::where('no_nota', $no_nota)->first();

        if (!$transaction) {
            return redirect('/dashboard/cashier/createtransaction')->with('error', 'Transaksi tidak ditemukan. Silakan buat transaksi baru.');
        }

        return view('dashboard.cashiers.order.create', [
            'active' => 'cashier',
            'goods' => Good::where('stok', '>', 0)->get(), // Still pass all goods for initial data, though search will filter
            'no_nota' => $no_nota,
            'transaction' => $transaction,
            'orders' => Order::where('no_nota', $no_nota)->with('good')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storetransaction(Request $request)
    {
        // Generate nomor nota 
        $tanggal = date('Ymd'); 
        $waktu = date('His');   
        $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT); 
        $no_nota_baru = $tanggal . '-' . $waktu . '-' . $random; 

        // Generate nomor nota unik
        while (Transaction::where('no_nota', $no_nota_baru)->exists()) {
            $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $no_nota_baru = $tanggal . '-' . $waktu . '-' . $random;
        }

        $validatedData = $request->validate([
            'tgl_transaksi' => 'required',
            'user_id' => 'required',
            'metode_pembayaran' => 'required',
        ]);

        // Tambahkan nomor nota yang sudah digenerate
        $validatedData['no_nota'] = $no_nota_baru;

        // Add default status
        $validatedData['status'] = 'gagal';
        $validatedData['total_harga'] = 0;
        $validatedData['bayar'] = 0;
        $validatedData['kembalian'] = 0;

        $transaction = Transaction::create($validatedData);

        return redirect('/dashboard/cashier/createorder?no_nota=' . $no_nota_baru);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeorder(Request $request)
    {
        try {
            // Log semua data request untuk debugging
            Log::info('Store order request received:', $request->all());

            $request->validate([
                'no_nota' => 'required',
                'good_id' => 'required|exists:goods,id',
                'qty' => 'required|integer|min:1',
                'subtotal' => 'required|numeric|min:0'
            ]);

            $barang = Good::findOrFail($request->good_id);
            Log::info('Product found:', ['id' => $barang->id, 'name' => $barang->nama, 'stock' => $barang->stok]);

            // Check if product already exists in cart
            $existingOrder = Order::where('no_nota', $request->no_nota)
                                  ->where('good_id', $request->good_id)
                                  ->first();

            if ($existingOrder) {
                Log::info('Existing order found, updating quantity');
                
                // Update existing order quantity
                $newQty = $existingOrder->qty + $request->qty;
                
                // Check stock (available stock + already in cart)
                $availableStock = $barang->stok + $existingOrder->qty;
                if ($availableStock < $newQty) {
                    Log::warning('Insufficient stock', ['available' => $availableStock, 'requested' => $newQty]);
                    return redirect()->back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $availableStock);
                }
                
                // Calculate stock difference to deduct
                $qtyDifference = $request->qty; // Only the new quantity being added

                // Get current transaction total to determine tebus murah eligibility
                $currentOrders = Order::where('no_nota', $request->no_nota)->get();
                $currentTotal = $currentOrders->sum('subtotal');
                Log::info('Current transaction total:', ['total' => $currentTotal]);

                // Determine price based on quantity and transaction total
                $unitPrice = $barang->harga;

                $isFromTebusMusahCart = $request->has('is_tebus_murah') && $request->is_tebus_murah == 'true';
                Log::info('Is from tebus murah cart:', ['is_tebus_murah' => $isFromTebusMusahCart]);
                
                if ($isFromTebusMusahCart) {
                    // Validasi syarat tebus murah
                    if ($barang->is_tebus_murah_active && 
                        $currentTotal >= $barang->min_total_tebus_murah && 
                        $barang->harga_tebus_murah > 0) {
                        $unitPrice = $barang->harga_tebus_murah;
                        Log::info('Using tebus murah price:', ['price' => $unitPrice]);
                    } else {
                        Log::warning('Product does not meet tebus murah requirements');
                        return redirect()->back()->with('error', 'Produk tidak memenuhi syarat tebus murah!');
                    }
                } else {
                    // Check tebus murah first (higher priority)
                    if ($barang->is_tebus_murah_active && 
                        $currentTotal >= $barang->min_total_tebus_murah && 
                        $barang->harga_tebus_murah > 0) {
                        $unitPrice = $barang->harga_tebus_murah;
                        Log::info('Auto applying tebus murah price:', ['price' => $unitPrice]);
                    }
                    // Then check wholesale
                    elseif ($barang->is_grosir_active && 
                            $newQty >= $barang->min_qty_grosir && 
                            $barang->harga_grosir > 0) {
                        $unitPrice = $barang->harga_grosir;
                        Log::info('Using wholesale price:', ['price' => $unitPrice]);
                    }
                }

                $newSubtotal = $unitPrice * $newQty;

                // Update existing order
                $existingOrder->update([
                    'qty' => $newQty,
                    'subtotal' => $newSubtotal,
                    'price' => $unitPrice,
                ]);
                
                // Reduce stock by the additional quantity
                $barang->decrement('stok', $qtyDifference);
                Log::info('Stock reduced:', ['product_id' => $barang->id, 'qty_reduced' => $qtyDifference, 'remaining_stock' => $barang->fresh()->stok]);

                Log::info('Order updated successfully:', ['order_id' => $existingOrder->id, 'new_qty' => $newQty, 'new_subtotal' => $newSubtotal]);

                $successMessage = $isFromTebusMusahCart ? 
                    'Produk berhasil ditambahkan dengan harga tebus murah! Total: ' . $newQty :
                    'Jumlah produk berhasil ditambahkan! Total: ' . $newQty;

                return redirect('/dashboard/cashier/createorder?no_nota=' . $request->no_nota)
                                    ->with('success', $successMessage);
            } else {
                Log::info('Creating new order');
                
                // Check stock for new order
                if ($barang->stok < $request->qty) {
                    Log::warning('Insufficient stock for new order', ['available' => $barang->stok, 'requested' => $request->qty]);
                    return redirect()->back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $barang->stok);
                }

                // Get current transaction total to determine tebus murah eligibility
                $currentOrders = Order::where('no_nota', $request->no_nota)->get();
                $currentTotal = $currentOrders->sum('subtotal');
                Log::info('Current transaction total for new order:', ['total' => $currentTotal]);

                // Determine price based on quantity and transaction total
                $qty = $request->qty;
                $unitPrice = $barang->harga;

                $isFromTebusMusahCart = $request->has('is_tebus_murah') && $request->is_tebus_murah == 'true';
                Log::info('Is from tebus murah cart (new order):', ['is_tebus_murah' => $isFromTebusMusahCart]);
                
                if ($isFromTebusMusahCart) {
                    // Validasi syarat tebus murah
                    if ($barang->is_tebus_murah_active && 
                        $currentTotal >= $barang->min_total_tebus_murah && 
                        $barang->harga_tebus_murah > 0) {
                        $unitPrice = $barang->harga_tebus_murah;
                        Log::info('Using tebus murah price for new order:', ['price' => $unitPrice]);
                    } else {
                        Log::warning('Product does not meet tebus murah requirements for new order');
                        return redirect()->back()->with('error', 'Produk tidak memenuhi syarat tebus murah!');
                    }
                } else {
                    // Check tebus murah first (higher priority)
                    if ($barang->is_tebus_murah_active && 
                        $currentTotal >= $barang->min_total_tebus_murah && 
                        $barang->harga_tebus_murah > 0) {
                        $unitPrice = $barang->harga_tebus_murah;
                        Log::info('Auto applying tebus murah price for new order:', ['price' => $unitPrice]);
                    }
                    // Then check wholesale
                    elseif ($barang->is_grosir_active && 
                            $qty >= $barang->min_qty_grosir && 
                            $barang->harga_grosir > 0) {
                        $unitPrice = $barang->harga_grosir;
                        Log::info('Using wholesale price for new order:', ['price' => $unitPrice]);
                    }
                }

                $subtotal = $unitPrice * $qty;

                // Create new order
                $newOrder = Order::create([
                    'no_nota' => $request->no_nota,
                    'good_id' => $request->good_id,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                    'price' => $unitPrice,
                ]);
                
                // Reduce stock
                $barang->decrement('stok', $qty);
                Log::info('Stock reduced for new order:', ['product_id' => $barang->id, 'qty_reduced' => $qty, 'remaining_stock' => $barang->fresh()->stok]);

                Log::info('New order created successfully:', ['order_id' => $newOrder->id, 'qty' => $qty, 'subtotal' => $subtotal]);

                $successMessage = $isFromTebusMusahCart ? 
                    'Produk berhasil ditambahkan dengan harga tebus murah!' :
                    'Pesanan berhasil ditambahkan!';

                return redirect('/dashboard/cashier/createorder?no_nota=' . $request->no_nota)
                                    ->with('success', $successMessage);
            }

        } catch (\Exception $e) {
            Log::error('Error in storeorder:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store order from barcode scan
     */
    public function storeOrderFromBarcode(Request $request)
    {
        try {
            Log::info('Barcode scan request received', $request->all());

            $barcode = $request->input('barcode');
            $qty = $request->input('qty', 1);
            $no_nota = $request->input('no_nota');

            // Validate input
            if (!$barcode || !$no_nota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data barcode atau nomor nota tidak lengkap!'
                ]);
            }

            // Clean barcode
            $barcode = trim($barcode);

            // Try to find product by barcode with multiple strategies
            $barang = Good::where('barcode', $barcode)->first();

            // If not found by exact match, try case insensitive
            if (!$barang) {
                $barang = Good::whereRaw('LOWER(barcode) = ?', [strtolower($barcode)])->first();
            }

            // If not found, try partial match
            if (!$barang) {
                $barang = Good::where('barcode', 'LIKE', '%' . $barcode . '%')->first();
            }

            // If still not found, try by name
            if (!$barang) {
                $barang = Good::where('nama', 'LIKE', '%' . $barcode . '%')->first();
            }

            if (!$barang) {
                Log::warning('Barcode not found: ' . $barcode);
                return response()->json([
                    'success' => false,
                    'message' => 'Barang dengan barcode "' . $barcode . '" tidak ditemukan!'
                ]);
            }

            // Check if product already exists in cart
            $existingOrder = Order::where('no_nota', $no_nota)
                                  ->where('good_id', $barang->id)
                                  ->first();

            if ($existingOrder) {
                // Update existing order quantity
                $newQty = $existingOrder->qty + $qty;
                
                // Check stock (available stock + already in cart)
                $availableStock = $barang->stok + $existingOrder->qty;
                if ($availableStock < $newQty) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi! Stok tersedia: ' . $availableStock
                    ]);
                }
                
                // Calculate stock difference to deduct
                $qtyDifference = $qty; // Only the new quantity being added

                // Get current transaction total to determine tebus murah eligibility
                $currentOrders = Order::where('no_nota', $no_nota)->get();
                $currentTotal = $currentOrders->sum('subtotal');

                // Determine price based on quantity and transaction total
                $unitPrice = $barang->harga; 

                // Check tebus murah first (higher priority)
                if ($barang->is_tebus_murah_active && 
                    $currentTotal >= $barang->min_total_tebus_murah && 
                    $barang->harga_tebus_murah > 0) {
                    $unitPrice = $barang->harga_tebus_murah;
                }
                // Then check wholesale
                elseif ($barang->is_grosir_active && 
                        $newQty >= $barang->min_qty_grosir && 
                        $barang->harga_grosir > 0) {
                    $unitPrice = $barang->harga_grosir;
                }

                $newSubtotal = $unitPrice * $newQty;

                // Update existing order
                $existingOrder->update([
                    'qty' => $newQty,
                    'subtotal' => $newSubtotal,
                    'price' => $unitPrice,
                ]);
                
                // Reduce stock by the additional quantity
                $barang->decrement('stok', $qtyDifference);
                Log::info('Stock reduced from barcode scan:', ['product_id' => $barang->id, 'qty_reduced' => $qtyDifference, 'remaining_stock' => $barang->fresh()->stok]);

                Log::info('Order updated successfully', ['order_id' => $existingOrder->id, 'barcode' => $barcode, 'new_qty' => $newQty]);

                return response()->json([
                    'success' => true,
                    'message' => 'Jumlah "' . $barang->nama . '" berhasil ditambahkan! Total: ' . $newQty,
                    'data' => [
                        'nama' => $barang->nama,
                        'harga' => number_format($unitPrice),
                        'qty' => $newQty,
                        'subtotal' => number_format($newSubtotal)
                    ]
                ]);
            } else {
                if ($barang->stok < $qty) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok barang tidak mencukupi! Stok tersedia: ' . $barang->stok
                    ]);
                }

                // Get current transaction total to determine tebus murah eligibility
                $currentOrders = Order::where('no_nota', $no_nota)->get();
                $currentTotal = $currentOrders->sum('subtotal');

                // Determine price based on quantity and transaction total
                $unitPrice = $barang->harga; 

                // Check tebus murah first (higher priority)
                if ($barang->is_tebus_murah_active && 
                    $currentTotal >= $barang->min_total_tebus_murah && 
                    $barang->harga_tebus_murah > 0) {
                    $unitPrice = $barang->harga_tebus_murah;
                }
                // Then check wholesale
                elseif ($barang->is_grosir_active && 
                        $qty >= $barang->min_qty_grosir && 
                        $barang->harga_grosir > 0) {
                    $unitPrice = $barang->harga_grosir;
                }

                $subtotal = $unitPrice * $qty;

                // Create order
                $order = Order::create([
                    'no_nota' => $no_nota,
                    'good_id' => $barang->id,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                    'price' => $unitPrice,
                ]);
                
                // Reduce stock
                $barang->decrement('stok', $qty);
                Log::info('Stock reduced for new order from barcode:', ['product_id' => $barang->id, 'qty_reduced' => $qty, 'remaining_stock' => $barang->fresh()->stok]);

                Log::info('Order created successfully', ['order_id' => $order->id, 'barcode' => $barcode]);

                return response()->json([
                    'success' => true,
                    'message' => 'Barang "' . $barang->nama . '" berhasil ditambahkan!',
                    'data' => [
                        'nama' => $barang->nama,
                        'harga' => number_format($unitPrice),
                        'qty' => $qty,
                        'subtotal' => number_format($subtotal)
                    ]
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error in storeOrderFromBarcode: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update order quantity
     */
    public function updateOrderQty(Request $request, Order $order)
    {
        try {
            $request->validate([
                'qty' => 'required|integer|min:1'
            ]);

            $newQty = $request->qty;
            $oldQty = $order->qty;
            $barang = Good::findOrFail($order->good_id);

            // Calculate quantity difference
            $qtyDifference = $newQty - $oldQty;
            
            // Check stock (current stock + old quantity in cart)
            $availableStock = $barang->stok + $oldQty;
            if ($availableStock < $newQty) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi! Stok tersedia: ' . $availableStock
                ]);
            }

            // Get current transaction total to determine tebus murah eligibility
            $currentOrders = Order::where('no_nota', $order->no_nota)->get();
            $currentTotal = $currentOrders->sum('subtotal');

            // Determine price based on quantity and transaction total
            $unitPrice = $barang->harga; // Default to retail price

            // Check tebus murah first (higher priority)
            if ($barang->is_tebus_murah_active && 
                $currentTotal >= $barang->min_total_tebus_murah && 
                $barang->harga_tebus_murah > 0) {
                $unitPrice = $barang->harga_tebus_murah;
            }
            // Then check wholesale
            elseif ($barang->is_grosir_active && 
                    $newQty >= $barang->min_qty_grosir && 
                    $barang->harga_grosir > 0) {
                $unitPrice = $barang->harga_grosir;
            }

            $newSubtotal = $unitPrice * $newQty;

            // Update order
            $order->update([
                'qty' => $newQty,
                'subtotal' => $newSubtotal,
                'price' => $unitPrice,
            ]);
            
            // Update stock based on quantity difference
            if ($qtyDifference > 0) {
                // Quantity increased, reduce stock
                $barang->decrement('stok', $qtyDifference);
                Log::info('Stock reduced after qty increase:', ['product_id' => $barang->id, 'qty_reduced' => $qtyDifference, 'new_stock' => $barang->fresh()->stok]);
            } elseif ($qtyDifference < 0) {
                // Quantity decreased, restore stock
                $barang->increment('stok', abs($qtyDifference));
                Log::info('Stock restored after qty decrease:', ['product_id' => $barang->id, 'qty_restored' => abs($qtyDifference), 'new_stock' => $barang->fresh()->stok]);
            }

            // Calculate new total
            $allOrders = Order::where('no_nota', $order->no_nota)->get();
            $newTotal = $allOrders->sum('subtotal');

            return response()->json([
                'success' => true,
                'message' => 'Jumlah berhasil diperbarui!',
                'data' => [
                    'qty' => $newQty,
                    'subtotal' => number_format($newSubtotal, 0, ',', '.'),
                    'price' => number_format($unitPrice, 0, ',', '.'),
                    'total' => number_format($newTotal, 0, ',', '.')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Search goods by name or barcode.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchGoods(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        $goods = Good::where('stok', '>', 0)
                           ->where(function($q) use ($query) {
                               $q->where('nama', 'LIKE', '%' . $query . '%')
                                 ->orWhere('barcode', 'LIKE', '%' . $query . '%');
                           })
                           ->limit(10)
                           ->get();

        return response()->json($goods);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function finishing(Request $request)
    {
        // Update transaction - ambil nama_pembeli dari request (dari form checkout)
        $rules = [
            'status' => $request->status,
            'total_harga' => $request->total_harga,
            'bayar' => $request->bayar,
            'kembalian' => $request->kembalian,
            'metode_pembayaran' => $request->nama_pembeli,
        ];

        $transaction = Transaction::find($request->id);
        $transaction->update($rules);

        // Log untuk debug
        Log::info('Finishing transaction', [
            'transaction_id' => $request->id,
            'status' => $request->status,
            'no_nota' => $transaction->no_nota
        ]);

        // Kurangi stok jika status lunas
        if (strtolower(trim($request->status)) === 'lunas') {
            $orders = Order::where('no_nota', $transaction->no_nota)->get();
            
            Log::info('Processing stock reduction', [
                'no_nota' => $transaction->no_nota,
                'orders_count' => $orders->count()
            ]);

            foreach ($orders as $order) {
                $good = Good::find($order->good_id);
                if ($good) {
                    $oldStock = $good->stok;
                    $newStock = $oldStock - $order->qty;
                    
                    // Update stok menggunakan raw query
                    DB::table('goods')
                       ->where('id', $good->id)
                       ->update(['stok' => $newStock]);
                    
                    Log::info('Stock updated', [
                        'good_id' => $good->id,
                        'good_name' => $good->nama,
                        'old_stock' => $oldStock,
                        'qty_reduced' => $order->qty,
                        'new_stock' => $newStock
                    ]);
                }
            }
        }

        return redirect('/dashboard/cashier')->with('success', 'Transaksi Sukses!');
    }

    /**
     * Generate and download nota PDF
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function nota(Request $request)
    {
        try {
            $request->validate([
                'no_nota' => 'required|string'
            ]);

            $no_nota = $request->no_nota;
            
            Log::info('Generating nota for: ' . $no_nota);
            
            // Get transaction data
            $transaction = Transaction::where('no_nota', $no_nota)->get();
            
            if ($transaction->isEmpty()) {
                Log::error('Transaction not found: ' . $no_nota);
                return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
            }
            
            // Get orders data with related goods
            $orders = Order::where('no_nota', $no_nota)->with('good')->get();
            
            // Handle case where no orders exist
            if ($orders->isEmpty()) {
                Log::warning('No orders found for transaction: ' . $no_nota . '. Creating dummy order for failed transaction.');
                
                // Create a dummy order to indicate failed transaction
                $orders = collect([
                    (object) [
                        'good' => (object) [
                            'nama' => 'Transaksi Gagal - Tidak ada item'
                        ],
                        'qty' => 0,
                        'price' => 0,
                        'subtotal' => 0
                    ]
                ]);
            }
            
            Log::info('Orders found: ' . $orders->count());
            
            // Generate PDF
            $pdf = PDF::loadview('nota_pdf', [
                'transaction' => $transaction,
                'orders' => $orders
            ]);
            
            // Set paper size for thermal printer (80mm width)
            $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait'); // 80mm x 297mm in points
            
            Log::info('PDF generated successfully for: ' . $no_nota);
            
            return $pdf->download('nota-' . $no_nota . '.pdf');
            
        } catch (\Exception $e) {
            Log::error('Error generating nota: ' . $e->getMessage(), [
                'no_nota' => $request->no_nota ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat nota: ' . $e->getMessage());
        }
    }

    /**
     * Display nota for printing (HTML version)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function printNota(Request $request)
    {
        $request->validate([
            'no_nota' => 'required|string'
        ]);

        $no_nota = $request->no_nota;
        
        // Get transaction data
        $transaction = Transaction::where('no_nota', $no_nota)->get();
        
        if ($transaction->isEmpty()) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }
        
        // Get orders data
        $orders = Order::where('no_nota', $no_nota)->with('good')->get();
        
        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'Detail pesanan tidak ditemukan.');
        }
        
        // Return HTML view for printing
        return view('nota_print', [
            'transaction' => $transaction,
            'orders' => $orders
        ]);
    }

    /**
     * Delete order item from cart
     */
    public function deleteorder(Order $order)
    {
        try {
            $no_nota = $order->no_nota;
            $good_id = $order->good_id;
            $qty = $order->qty;
            
            // Delete the order
            $order->delete();
            
            // Return stock to the product
            $barang = Good::find($good_id);
            if ($barang) {
                $barang->increment('stok', $qty);
                Log::info('Stock restored after order deletion:', ['product_id' => $good_id, 'qty_restored' => $qty, 'new_stock' => $barang->fresh()->stok]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari pesanan dan stok dikembalikan!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
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

        return view('dashboard.cashiers.checkout', [
            'active' => 'cashier',
            'no_nota' => $request->no_nota,
            'total_harga' => $request->total_harga,
            'users' => User::all(),
            'transaction' => $transaction,
        ]);
    }
}
