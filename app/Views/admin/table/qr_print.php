<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Semua QR Code</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        .controls {
            text-align: center;
            margin-bottom: 30px;
        }
        .print-btn {
            padding: 12px 24px;
            background: #6C63FF;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .print-btn:hover {
            background: #5A52E0;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .qr-card {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
            border: 1px dashed #ccc;
            page-break-inside: avoid;
        }
        .brand {
            font-size: 20px;
            font-weight: 800;
            color: #6C63FF;
            margin-bottom: 5px;
        }
        .instruction {
            font-size: 12px;
            color: #666;
            margin-bottom: 15px;
        }
        .qr-image-wrapper {
            background: #fff;
            padding: 10px;
            border: 2px solid #eee;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 15px;
        }
        .qr-image-wrapper img {
            display: block;
            width: 150px;
            height: 150px;
        }
        .table-name {
            font-size: 24px;
            font-weight: 800;
            color: #333;
            margin-bottom: 5px;
        }

        @media print {
            body { background: #fff; padding: 0; }
            .controls { display: none; }
            .grid { gap: 10px; max-width: none; }
            .qr-card { border: 1px dashed #999; box-shadow: none; border-radius: 0; }
        }
    </style>
</head>
<body>

<div class="controls">
    <button class="print-btn" onclick="window.print()">🖨️ Cetak Halaman Ini</button>
    <p style="color:#666; font-size:14px; margin-top:10px;">Tips: Gunakan opsi "Save as PDF" di dialog print untuk menyimpan ke file.</p>
</div>

<div class="grid">
    <?php foreach ($tables as $table): ?>
    <div class="qr-card">
        <div class="brand">🍽️ Digital Menu</div>
        <div class="instruction">Scan QR Code ini untuk memesan</div>

        <div class="qr-image-wrapper">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&margin=10&data=<?= urlencode($menuUrls[$table['id']]) ?>" alt="QR Code <?= esc($table['name']) ?>">
        </div>

        <div class="table-name"><?= esc($table['name']) ?></div>
    </div>
    <?php endforeach; ?>
</div>

</body>
</html>
