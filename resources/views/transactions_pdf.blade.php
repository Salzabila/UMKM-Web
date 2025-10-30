<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi UMKM MART</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 {
            color: #333;
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .header h2 {
            color: #666;
            margin: 5px 0;
            font-size: 18px;
            font-weight: normal;
        }
        .header p {
            color: #888;
            margin: 5px 0;
            font-size: 14px;
        }
        .summary-section {
            background: white;
            border: 2px solid #333;
            border-radius: 10px;
            padding: 25px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .summary-title {
            color: #333;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .summary-grid {
            display: table;
            width: 100%;
        }
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 15px;
            vertical-align: top;
        }
        .summary-number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        .summary-label {
            font-size: 12px;
            color: #666;
            font-weight: normal;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        th {
            background: #e9ecef;
            color: #333;
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            border-bottom: 2px solid #333;
            font-size: 11px;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
            font-size: 10px;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .status-lunas {
            background-color: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }
        .status-belum {
            background-color: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }
        .currency {
            text-align: right;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .payment-summary {
            margin-top: 30px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .payment-summary h3 {
            text-align: center;
            color: #333;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .payment-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .payment-summary th, .payment-summary td {
            padding: 8px;
            border: 1px solid #eee;
            text-align: left;
            font-size: 11px;
        }
        .payment-summary th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .payment-summary .total-row {
            font-weight: bold;
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>GERAI UMKM MART</h1>
        <h2>DATA TRANSAKSI</h2>
        <p>Transaksi Lengkap Toko</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Nota</th>
                <th>Tanggal</th>
                <th>Metode Pembayaran</th>
                <th>Jumlah Qty Barang</th>
                <th>Nama Barang</th>
                <th>Barcode Barang</th>
                <th>Mitra Binaan</th> 
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $transaction)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $transaction->no_nota }}</td>
                <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                <td>{{ $transaction->metode_pembayaran }}</td>
                <td>{{ $transaction->total_qty_barang }}</td>
                <td>{{ $transaction->nama_barang_list }}</td>
                <td>{{ $transaction->barcode_list }}</td>
                <td>{{ $transaction->category_list }}</td>
                <td class="currency">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; color: #666; font-style: italic; padding: 20px;">
                    Tidak ada data transaksi pada periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="payment-summary">
        <h3>Ringkasan Total Berdasarkan Metode Pembayaran</h3>
        <table>
            <thead>
                <tr>
                    <th>Metode Pembayaran</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentMethodTotals as $method => $total)
                <tr>
                    <td>{{ $method }}</td>
                    <td class="currency">Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Keseluruhan</td>
                    <td class="currency">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis pada {{ $generated_at }}</p>
        <p><strong>GERAI UMKM MART - Sistem Manajemen Transaksi</strong></p>
    </div>
</body>
</html>
