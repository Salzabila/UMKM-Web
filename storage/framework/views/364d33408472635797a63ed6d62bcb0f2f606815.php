<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode - <?php echo e($good->nama); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .barcode-container {
            text-align: center;
            border: 2px solid #000;
            padding: 20px;
            margin: 20px auto;
            width: 300px;
        }
        .product-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .barcode-code {
            font-size: 14px;
            margin-top: 10px;
        }
        .price {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px;">Print Barcode</button>
        <button onclick="window.location.href='<?php echo e(url('/dashboard/goods/' . $good->id . '/edit')); ?>'" style="padding: 10px 20px; font-size: 16px; margin-left: 10px;">Close</button>
    </div>

    <div class="barcode-container">
        <div class="product-name"><?php echo e($good->nama); ?></div>
        <canvas id="barcode"></canvas>
        <div class="barcode-code"><?php echo e($good->barcode); ?></div>
        <div class="price">Rp <?php echo e(number_format($good->harga)); ?></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        JsBarcode("#barcode", "<?php echo e($good->barcode); ?>", {
            format: "CODE128",
            width: 2,
            height: 80,
            displayValue: false
        });
    </script>
</body>
</html>
<?php /**PATH D:\SEMESTER 6\KERJA PRAKTEK PELINDO\project umkm\NEW\kasirku-main\resources\views/dashboard/goods/print-barcode.blade.php ENDPATH**/ ?>