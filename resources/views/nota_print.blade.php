<!DOCTYPE html>
<html>
<head>
    <title>Cetak Nota - {{ $transaction->first()->no_nota }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @page {
            size: 80mm auto;
            margin: 3mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            font-weight: bold;
            margin: 0;
            padding: 0;
            color: #000;
            background: white;
        }
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .main-table td {
            padding: 2px 0;
            font-weight: bold;
        }
        
        .header-title {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            text-align: center;
        }
        
        .header-address {
            margin: 2px 0;
            font-weight: bold;
            text-align: center;
        }
        
        .divider {
            border-top: 2px solid #000;
            padding: 0;
            height: 2px;
        }
        
        .dashed-divider {
            border-top: 1px dashed #000;
            padding: 0;
            height: 1px;
            margin-top: 5px;
        }
        
        .items-header th {
            font-weight: bold;
            text-align: left;
            padding: 5px 2px;
            border-bottom: 1.5px solid #000;
        }
        
        .item-row td {
            padding: 4px 2px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
            font-weight: bold;
        }
        
        .total-row td {
            padding: 3px 0;
            font-size: 11px;
            font-weight: bold;
        }
        
        .text-center { 
            text-align: center; 
        }
        
        .text-right { 
            text-align: right; 
        }
        
        .font-bold { 
            font-weight: bold; 
        }

        .barcode-section {
            text-align: center;
            padding: 10px 0;
        }
        .barcode-container {
            display: inline-block;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
        }
        .barcode-text {
            font-size: 8px;
            margin-top: 3px;
            font-weight: bold;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #0056b3;
        }

        @media print {
            .print-button {
                display: none;
            }
            
            body {
                font-family: Arial, sans-serif;
                font-size: 10px;
                font-weight: bold;
                margin: 0;
                padding: 0;
            }
            
            * {
                font-weight: bold !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .header-title {
                font-weight: bold !important;
                font-size: 14px !important;
            }
            
            .header-address {
                font-weight: bold !important;
            }
            
            .items-header th {
                font-weight: bold !important;
            }
            
            .item-row td {
                font-weight: bold !important;
            }
            
            .total-row td {
                font-weight: bold !important;
            }
        }

        @media screen {
            body {
                padding: 20px;
                max-width: 300px;
                margin: 0 auto;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        🖨️ Cetak Nota
    </button>

    <table class="main-table">
        <tr>
            <td colspan="4" class="text-center" style="padding-bottom: 10px;">
                <div class="header-title">GERAI UMKM MART</div>
                <div class="header-address">Jl. Perak Timur No.620, Perak Utara, Kec. Pabean Cantikan, Surabaya, Jawa Timur 60165</div>
                <div class="header-address">Telp: 0813-3242-1401</div>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="divider"></td>
        </tr>

        <tr>
            <td colspan="4" style="padding-top: 10px; padding-bottom: 5px;">
                @foreach($transaction as $trans)
                <table>
                    <tr>
                        <td width="30%">No. Nota</td>
                        <td width="5%">:</td>
                        <td width="65%">{{ $trans->no_nota }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ $trans->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Kasir</td>
                        <td>:</td>
                        <td>{{ $trans->user->nama ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Metode Bayar</td>
                        <td>:</td>
                        <td>{{ $trans->metode_pembayaran }}</td>
                    </tr>
                </table>
                @endforeach
            </td>
        </tr>

        <tr class="items-header">
            <th width="45%">Item</th>
            <th width="10%" class="text-center">Qty</th>
            <th width="20%" class="text-right">Harga</th>
            <th width="25%" class="text-right">Subtotal</th>
        </tr>

        @foreach($orders as $order)
        <tr class="item-row">
            <td>{{ $order->good->nama ?? 'N/A' }}</td>
            <td class="text-center">{{ $order->qty }}</td>
            <td class="text-right">{{ number_format($order->price, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($order->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="4" class="divider" style="padding-top: 5px;"></td>
        </tr>

        <tr>
            <td colspan="4" style="padding-top: 5px;">
                @foreach($transaction as $trans)
                <table style="width: 100%;">
                    <tr class="total-row">
                        <td class="font-bold">TOTAL</td>
                        <td class="text-right font-bold">Rp {{ number_format($trans->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Bayar</td>
                        <td class="text-right">Rp {{ number_format($trans->bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Kembalian</td>
                        <td class="text-right">Rp {{ number_format($trans->kembalian, 0, ',', '.') }}</td>
                    </tr>
                </table>
                @endforeach
            </td>
        </tr>

        <tr>
            <td colspan="4" class="barcode-section">
                @foreach($transaction as $trans)
                @php
                    $notaParts = explode('-', $trans->no_nota);
                    $shortBarcode = (count($notaParts) >= 3) ? $notaParts[1] . '-' . $notaParts[2] : $trans->no_nota;
                @endphp
                <div class="barcode-container">
                    <svg id="barcode-{{ $trans->no_nota }}" width="150" height="40"></svg>
                    <div class="barcode-text">{{ $shortBarcode }}</div>
                </div>
                @endforeach
            </td>
        </tr>

        <tr>
             <td colspan="4" style="padding-top: 10px;">
                <div class="dashed-divider"></div>
                <div class="text-center" style="padding-top: 5px; font-weight: bold;">*** TERIMA KASIH ***</div>
                <div class="text-center" style="font-size: 9px; font-weight: bold;">Barang yang sudah dibeli tidak dapat dikembalikan.</div>
                <div class="text-center" style="font-size: 9px; padding-top: 5px; font-weight: bold;">{{ date('d/m/Y H:i:s') }}</div>
             </td>
        </tr>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach($transaction as $trans)
            @php
                $notaParts = explode('-', $trans->no_nota);
                $shortBarcode = (count($notaParts) >= 3) ? $notaParts[1] . '-' . $notaParts[2] : $trans->no_nota;
            @endphp
            JsBarcode("#barcode-{{ $trans->no_nota }}", "{{ $shortBarcode }}", {
                format: "CODE128",
                width: 1,
                height: 40,
                displayValue: false,
                margin: 0
            });
            @endforeach
        });

        // Auto print ketika halaman dimuat (opsional)
        // window.onload = function() {
        //     window.print();
        // }
        
        window.onafterprint = function() {
            // window.close();
        }
    </script>
</body>
</html>
