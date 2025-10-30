<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barcode - {{ $good->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .barcode-container {
            text-align: center;
            border: 2px solid #000;
            padding: 20px;
            margin: 20px auto;
            width: 300px;
        }
        .product-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .product-price {
            font-size: 16px;
            color: #666;
            margin-bottom: 15px;
        }
        .barcode-visual {
            height: 60px;
            background: repeating-linear-gradient(
                90deg,
                #000 0px,
                #000 2px,
                #fff 2px,
                #fff 4px
            );
            margin: 10px 0;
        }
        .barcode-text {
            font-size: 14px;
            margin-top: 10px;
            font-family: monospace;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div class="barcode-container">
        <div class="product-name">{{ $good->nama }}</div>
        <div class="product-price">Rp {{ number_format($good->harga, 0, ',', '.') }}</div>
        <div class="barcode-visual"></div>
        <div class="barcode-text">{{ $good->barcode }}</div>
    </div>
</body>
</html>
