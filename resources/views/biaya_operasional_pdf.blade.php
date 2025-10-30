<!DOCTYPE html>
<html>
<head>
    <title>Laporan Biaya Operasional</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10pt;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h2 {
            margin: 0;
            font-size: 18pt;
        }
        .header p {
            margin: 5px 0;
            font-size: 11pt;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row td {
            font-weight: bold;
            background-color: #e9e9e9;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Biaya Operasional</h2>
        <p>Periode: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">NO</th>
                <th style="width: 15%;">Tanggal</th>
                <th>Uraian/Keterangan</th>
                <th style="width: 10%;">Qty</th>
                <th style="width: 20%;" class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($biayaOperasional as $biaya)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($biaya->tanggal)->format('d M Y') }}</td>
                    <td>{{ $biaya->uraian }}</td>
                    <td style="text-align: center;">{{ $biaya->qty }}</td>
                    <td class="text-right">Rp {{ number_format($biaya->nominal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>Total Biaya Operasional:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalBiaya, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ $generated_at->format('d M Y H:i:s') }}
    </div>
</body>
</html>
