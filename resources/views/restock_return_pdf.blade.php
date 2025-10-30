<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Restock & Return Barang UMKM MART</title>
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
            border: 2px solid #6f42c1; 
            border-radius: 10px;
            padding: 25px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .summary-title {
            color: #6f42c1; 
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
        .type-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        .type-return {
            background-color: #dc3545; 
        }
        .type-restock {
            background-color: #28a745; 
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>GERAI UMKM MART</h1>
        <h2>LAPORAN RESTOCK & RETURN BARANG</h2>
        <p>Periode: {{ $tgl_awal }} - {{ $tgl_akhir }}</p>
    </div>

    <!-- <div class="summary-section">
        <div class="summary-title">RINGKASAN PERGERAKAN STOK</div>
        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-number">{{ $total_restocks }}</span>
                <span class="summary-label">Total Restock</span>
            </div>
            <div class="summary-item">
                <span class="summary-number">Rp {{ number_format($total_nilai_restock, 0, ',', '.') }}</span>
                <span class="summary-label">Total Nilai Restock</span>
            </div>
            <div class="summary-item">
                <span class="summary-number">{{ $total_returns }}</span>
                <span class="summary-label">Total Return</span>
            </div>
            <div class="summary-item">
                <span class="summary-number">Rp {{ number_format($total_nilai_return, 0, ',', '.') }}</span>
                <span class="summary-label">Total Nilai Return</span>
            </div>
        </div>
    </div> -->

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Nama Barang</th>
                <th>Barcode</th>
                <th>Mitra</th>
                <th>Jumlah</th>
                <th>Keterangan / Alasan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($combinedData as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                <td>
                    <span class="type-badge type-{{ strtolower($item->type) }}">
                        {{ $item->type }}
                    </span>
                </td>
                <td>{{ $item->good->nama ?? 'N/A' }}</td>
                <td>{{ $item->good->barcode ?? '-' }}</td>
                <td>{{ $item->good->category->nama ?? '-' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->type == 'Restock' ? ($item->keterangan ?? '-') : ($item->alasan ?? '-') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; color: #666; font-style: italic; padding: 20px;">
                    Tidak ada data restock atau return barang pada periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis pada {{ $generated_at }}</p>
        <p><strong>UMKM MART - Sistem Manajemen Stok</strong></p>
    </div>
</body>
</html>
