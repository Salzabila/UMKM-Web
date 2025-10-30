```html
    &lt;!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Laporan Keuangan UMKM MART</title>
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
            .status-debet {
                color: #dc3545;
                font-weight: bold;
            }
            .status-credit {
                color: #28a745;
                font-weight: bold;
            }
            .currency {
                text-align: right;
                font-weight: bold;
            }
            .summary-totals {
                margin-top: 30px;
                background: white;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 20px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }
            .summary-totals h3 {
                text-align: center;
                color: #333;
                margin-bottom: 15px;
                font-size: 16px;
            }
            .summary-totals table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            .summary-totals th, .summary-totals td {
                padding: 8px;
                border: 1px solid #eee;
                text-align: left;
                font-size: 11px;
            }
            .summary-totals th {
                background-color: #f2f2f2;
                font-weight: bold;
            }
            .summary-totals .total-row {
                font-weight: bold;
                background-color: #e9ecef;
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
        </style>
    </head>
    <body>
        <div class="header">
            <h1>GERAI UMKM MART</h1>
            <h2>LAPORAN KEUANGAN</h2>
            <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Mitra/Sumber</th>
                    <th>Status</th>
                    <th>Nominal</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}</td>
                    <td>{{ $item['mitra'] }}</td>
                    <td class="{{ $item['status'] == 'DEBET' ? 'status-debet' : 'status-credit' }}">
                        {{ $item['status'] }}
                    </td>
                    <td class="currency">Rp {{ number_format($item['nominal'], 0, ',', '.') }}</td>
                    <td>{{ $item['keterangan'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #666; font-style: italic; padding: 20px;">
                        Tidak ada data keuangan pada periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary-totals">
            <h3>Ringkasan Keuangan</h3>
            <table>
                <tbody>
                    <tr>
                        <td>Total Pemasukan (CREDIT)</td>
                        <td class="currency status-credit">Rp {{ number_format($totalCredit, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Pengeluaran (DEBET)</td>
                        <td class="currency status-debet">Rp {{ number_format($totalDebit, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Saldo Akhir</td>
                        <td class="currency">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>Laporan ini digenerate secara otomatis pada {{ $generated_at->format('d F Y H:i:s') }}</p>
            <p><strong>GERAI UMKM MART - Sistem Manajemen Keuangan</strong></p>
        </div>
    </body>
    </html>