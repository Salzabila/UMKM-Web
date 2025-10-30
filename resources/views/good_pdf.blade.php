<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Barang - GERAI UMKM MART</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 10px; 
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: #000;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            color: #666;
        }

        .header p {
            margin: 5px 0;
            color: #888;
            font-size: 12px;
        }

        .summary-section {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border: 2px solid #000;
            border-radius: 8px;
        }

        .summary-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #000;
            text-align: center;
        }

        .summary-stats {
            display: flex;
            justify-content: space-around;
            align-items: center;
            position: relative;
        }

        .stat-item {
            text-align: center;
            flex: 1;
            padding: 10px;
        }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            display: block;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
            line-height: 1.2;
        }

        .stat-separator {
            width: 1px;
            height: 40px;
            background-color: #ccc;
            position: absolute;
        }

        .separator-1 {
            left: 33.33%;
        }

        .separator-2 {
            left: 66.66%;
        }

        .table-container {
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
            font-size: 9px; 
        }

        th {
            background-color: #e0e0e0;
            font-weight: bold;
            color: #000;
            text-align: center;
        }

        td {
            color: #333;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .category-badge {
            background-color: #f0f0f0;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 8px;
            border: 1px solid #ccc;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }

        .col-no { width: 3%; }
        .col-date { width: 8%; }
        .col-name { width: 15%; }
        .col-type { width: 8%; }
        .col-expired { width: 8%; }
        .col-supplier { width: 10%; }
        .col-stock { width: 6%; }
        .col-barcode { width: 10%; }
        .col-harga-beli { width: 10%; }
        .col-harga-jual { width: 10%; }
        .col-harga-peats { width: 12%; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UMKM MART</h1>
        <h2>DATA BARANG</h2>
        <p>Inventori Lengkap Toko</p>
    </div>

    <!-- <div class="summary-section">
        <div class="summary-title">RINGKASAN INVENTORI</div>
        <div class="summary-stats">
            <div class="stat-item">
                <span class="stat-number">{{ $goods->count() }}</span>
                <div class="stat-label">Total Barang</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $goods->sum('stok') }}</span>
                <div class="stat-label">Total Stok</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">Rp {{ number_format($goods->sum(function($good) { return $good->stok * $good->harga; }), 0, ',', '.') }}</span>
                <div class="stat-label">Total Nilai Inventori (Harga Jual)</div>
            </div>
            <div class="stat-separator separator-1"></div>
            <div class="stat-separator separator-2"></div>
        </div>
    </div> -->

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-date">Tgl Masuk</th>
                    <th class="col-name">Nama Barang</th>
                    <th class="col-type">Jenis</th>
                    <th class="col-expired">Expired</th>
                    <th class="col-supplier">Mitra</th>
                    <th class="col-stock">Stok</th>
                    <th class="col-barcode">Barcode</th>
                    <th class="col-harga-beli">Harga Beli</th>
                    <th class="col-harga-jual">Harga Tunai</th>
                    <th class="col-harga-peats">Harga P-Eats</th>
                </tr>
            </thead>
            <tbody>
                @foreach($goods as $index => $good)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        @if($good->tgl_masuk)
                            {{ date('d/m/Y', strtotime($good->tgl_masuk)) }}
                        @else
                            {{ date('d/m/Y', strtotime($good->created_at)) }}
                        @endif
                    </td>
                    <td>{{ $good->nama }}</td>
                    <td class="text-center">
                        <span class="category-badge">{{ $good->type ?? ($good->category->nama ?? '-') }}</span>
                    </td>
                    <td class="text-center">
                        @if($good->expired_date && $good->expired_date != '0000-00-00')
                            {{ date('d/m/Y', strtotime($good->expired_date)) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if($good->category && isset($good->category->nama))
                            {{ $good->category->nama }}
                        @else
                            Umum
                        @endif
                    </td>
                    <td class="text-center">{{ $good->stok }} unit</td>
                    <td class="text-center">{{ $good->barcode ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($good->harga_asli, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($good->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($good->harga_p_eats, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                @if($goods->isEmpty())
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data barang yang tersedia.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis pada {{ date('d/m/Y H:i:s') }}</p>
        <p>UMKM MART - Sistem Manajemen Inventori</p>
    </div>
</body>
</html>
