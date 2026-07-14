<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan — Digital Menu</title>
    <link rel="stylesheet" href="<?= base_url('css/public.css') ?>">
    <style>
        .dashboard-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .status-menunggu { background: #fff3cd; color: #856404; }
        .status-disiapkan { background: #cce5ff; color: #004085; }
        .status-selesai { background: #d4edda; color: #155724; }
        
        .order-items { margin-top: 20px; }
        .order-item {
            display: flex; justify-content: space-between;
            padding: 10px 0; border-bottom: 1px dashed #eee;
        }
        .order-total {
            margin-top: 20px; font-size: 18px; font-weight: bold; text-align: right;
        }
    </style>
</head>
<body style="background: var(--bg-color);">

<header class="pub-header">
    <div class="pub-header-inner">
        <div class="pub-logo">
            <div class="pub-logo-icon">🍽️</div>
            <div>
                <div class="pub-logo-text">Digital Menu</div>
            </div>
        </div>
        <div class="pub-header-actions">
            <a href="<?= site_url('/') ?>"><button class="pub-admin-btn">Kembali ke Menu</button></a>
        </div>
    </div>
</header>

<div class="dashboard-container">
    <?php if (!$order): ?>
        <div style="text-align:center; padding: 40px 0;">
            <div style="font-size: 50px; margin-bottom: 15px;">🛒</div>
            <h2>Belum Ada Pesanan</h2>
            <p style="color: var(--text-light);">Silakan pesan menu terlebih dahulu melalui halaman utama.</p>
            <a href="<?= site_url('/') ?>" style="color: var(--primary); text-decoration: none; font-weight: bold; margin-top: 15px; display: inline-block;">Lihat Menu</a>
        </div>
    <?php else: ?>
        <h2 style="margin-top:0;">Pesanan Anda</h2>
        <p style="color: var(--text-light);">Meja: <strong><?= esc($order['table_number'] ?? 'Tidak diketahui') ?></strong></p>
        
        <?php 
            $statusClass = 'status-menunggu';
            if ($order['status'] === 'Disiapkan') $statusClass = 'status-disiapkan';
            if ($order['status'] === 'Selesai') $statusClass = 'status-selesai';
        ?>
        <div class="status-badge <?= $statusClass ?>">
            Status: <?= esc($order['status']) ?>
        </div>

        <div class="order-items">
            <h3>Daftar Item</h3>
            <?php foreach ($items as $item): ?>
                <div class="order-item">
                    <div>
                        <strong><?= esc($item['menu_name']) ?></strong> x<?= esc($item['quantity']) ?>
                    </div>
                    <div>
                        Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="order-total">
            Total Harga: Rp <?= number_format($order['total_price'], 0, ',', '.') ?>
        </div>
        
        <p style="font-size: 13px; color: #888; margin-top: 20px; text-align: center;">
            Halaman ini akan otomatis diperbarui. Silakan tunggu pesanan Anda diantarkan ke meja.
        </p>
    <?php endif; ?>
</div>

<script>
    // Simple auto refresh every 10 seconds to check status
    if (<?= $order ? 'true' : 'false' ?>) {
        setTimeout(function() {
            window.location.reload();
        }, 10000);
    }
</script>

</body>
</html>
