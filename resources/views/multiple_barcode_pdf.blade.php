<!DOCTYPE html>
<html>
<head>
    <title>Cetak Barcode Barang</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 10mm;
        }

        .barcode-table {
            width: 100%;
            border-collapse: collapse;
            page-break-after: always;
        }

        .barcode-table:last-child {
            page-break-after: avoid;
        }

        .barcode-cell {
            width: 25%;
            height: 20mm; 
            text-align: center;
            padding: 1mm;
            vertical-align: top;
            border: 0.5px solid #ccc;
            box-sizing: border-box;
            overflow: hidden;
        }

        .barcode-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .barcode-item img {
            width: 90%;
            height: 25px;
            margin-bottom: 1mm;
        }

        .barcode-item p {
            margin: 0;
            padding: 0;
            font-size: 6.5pt;
            line-height: 1.1;
        }

        .barcode-item .item-name {
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .barcode-item .item-price {
            font-weight: bold;
            margin-bottom: 0.5mm;
        }

        .barcode-item .item-barcode-value {
            font-size: 5.5pt;
            margin-top: 0.3mm;
        }
    </style>
</head>
<body>
    @php
        $chunkedBarcodes = array_chunk($barcodesData, 52);
    @endphp

    @foreach ($chunkedBarcodes as $pageBarcodes)
        <table class="barcode-table">
            @foreach (array_chunk($pageBarcodes, 4) as $row)
                <tr>
                    @foreach ($row as $data)
                        <td class="barcode-cell">
                            <div class="barcode-item">
                                <p class="item-name">{{ $data['nama'] }}</p>
                                <p class="item-price">Rp {{ number_format($data['harga'], 0, ',', '.') }}</p>
                                <img src="data:image/svg+xml;base64,{{ base64_encode($data['barcode_svg']) }}" alt="Barcode">
                                <p class="item-barcode-value">{{ $data['barcode_value'] }}</p>
                            </div>
                        </td>
                    @endforeach
                    @if (count($row) < 4)
                        @for ($i = 0; $i < (4 - count($row)); $i++)
                            <td class="barcode-cell"></td>
                        @endfor
                    @endif
                </tr>
            @endforeach
        </table>
    @endforeach
</body>
</html>
