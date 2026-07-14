<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle) ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .qr-card {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .brand {
            font-size: 24px;
            font-weight: 800;
            color: #6C63FF;
            margin-bottom: 5px;
        }
        .instruction {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
        }
        .qr-image-wrapper {
            background: #fff;
            padding: 15px;
            border: 2px solid #eee;
            border-radius: 16px;
            display: inline-block;
            margin-bottom: 25px;
        }
        .qr-image-wrapper img {
            display: block;
            width: 200px;
            height: 200px;
        }
        .table-name {
            font-size: 28px;
            font-weight: 800;
            color: #333;
            margin-bottom: 5px;
        }
        .table-meta {
            font-size: 14px;
            color: #888;
        }
        .print-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: #6C63FF;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 30px;
            transition: background 0.3s;
        }
        .print-btn:hover {
            background: #5A52E0;
        }
        @media print {
            body { background: none; }
            .qr-card { box-shadow: none; padding: 0; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>

<div class="qr-card">
    <div class="brand">🍽️ Digital Menu</div>
    <div class="instruction">Scan QR Code ini untuk memesan</div>

    <div class="qr-image-wrapper">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&margin=10&data=<?= urlencode($menuUrl) ?>" alt="QR Code">
    </div>

    <div class="table-name"><?= esc($table['name']) ?></div>
    <div class="table-meta">Kapasitas: <?= esc($table['capacity']) ?> Orang <?= $table['location'] ? '• ' . esc($table['location']) : '' ?></div>

    <button class="print-btn" onclick="window.print()">🖨️ Cetak QR Code</button>
</div>

</body>
</html>
