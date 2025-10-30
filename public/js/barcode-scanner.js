document.addEventListener('DOMContentLoaded', () => {
    let isScanning = false;
    let scannerInitialized = false;
    const startScannerBtn = document.getElementById('start-scanner-btn');
    const scannerContainer = document.getElementById('barcode-scanner-container');
    const barcodeResult = document.getElementById('barcode-result');
    const scannedBarcodeInput = document.getElementById('scanned-barcode-input');

    if (!startScannerBtn || !scannerContainer || !barcodeResult || !scannedBarcodeInput) {
        console.error('Elemen DOM untuk pemindai tidak ditemukan. Pastikan ID HTML sudah benar.');
        return;
    }

    // Inisialisasi QuaggaJS
    const initScanner = () => {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: scannerContainer,
                constraints: {
                    facingMode: "environment" // Gunakan kamera belakang ponsel
                },
            },
            decoder: {
                readers: ["ean_reader", "ean_8_reader", "upc_reader", "code_128_reader"]
            }
        }, function (err) {
            if (err) {
                console.error("Gagal menginisialisasi QuaggaJS:", err);
                return;
            }
            console.log("Inisialisasi QuaggaJS berhasil.");
            Quagga.start();
            scannerInitialized = true;
        });
    };

    // Fungsi untuk menghentikan pemindai
    const stopScanner = () => {
        if (scannerInitialized) {
            Quagga.stop();
            scannerInitialized = false;
        }
    };

    // Event listener untuk tombol "Mulai Pindai"
    startScannerBtn.addEventListener('click', () => {
        if (!isScanning) {
            startScannerBtn.innerText = 'Stop Scan';
            scannerContainer.classList.remove('hidden');
            barcodeResult.innerText = 'Mencari barcode...';
            initScanner();
            isScanning = true;
        } else {
            startScannerBtn.innerText = 'Start Scan';
            scannerContainer.classList.add('hidden');
            barcodeResult.innerText = '';
            stopScanner();
            isScanning = false;
        }
    });

    // Tangani event ketika barcode berhasil dideteksi
    Quagga.onDetected((data) => {
        if (data && data.codeResult && data.codeResult.code) {
            const barcode = data.codeResult.code;
            scannedBarcodeInput.value = barcode;
            barcodeResult.innerText = `Barcode ditemukan: ${barcode}`;

            // Kirim barcode ke server untuk mengisi form
            // Anda bisa menggunakan fetch API atau library seperti Axios
            fetch('/dashboard/goods/auto-fill-barcode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ barcode: barcode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Isi form dengan data produk yang diterima
                    document.getElementById('good_name').value = data.data.good_name || '';
                    document.getElementById('stock').value = data.data.stock || '';
                    // Tambahkan field lain sesuai kebutuhan Anda
                } else {
                    console.error('Produk tidak ditemukan:', data.message);
                    barcodeResult.innerText = 'Produk tidak ditemukan.';
                }
                // Hentikan scanner setelah berhasil
                startScannerBtn.click();
            })
            .catch(error => {
                console.error('Error:', error);
                barcodeResult.innerText = 'Error saat mengirim data.';
                // Hentikan scanner jika ada error
                startScannerBtn.click();
            });
        }
    });
});