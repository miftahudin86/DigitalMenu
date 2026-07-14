<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Digital — Restoran</title>
    <meta name="description" content="Lihat menu lengkap kami. Pilih makanan dan minuman favorit Anda.">
    <link rel="stylesheet" href="<?= base_url('css/public.css') ?>">
</head>
<body>

<!-- ── Header ──────────────────────────────────────────────────────────── -->
<header class="pub-header">
    <div class="pub-header-inner">
        <div class="pub-logo">
            <div class="pub-logo-icon">🍽️</div>
            <div>
                <div class="pub-logo-text">Digital Menu</div>
                <div class="pub-logo-sub">Pilih makanan favoritmu</div>
            </div>
        </div>
        <div class="pub-header-actions">
            <a href="<?= site_url('/auth') ?>">
                <button class="pub-admin-btn" id="adminLoginBtn">🔐 Admin</button>
            </a>
        </div>
    </div>
</header>

<!-- ── Hero ────────────────────────────────────────────────────────────── -->
<section class="hero">
    <div class="hero-content">
        <?php if (!empty($tableInfo)): ?>
            <div class="hero-badge" style="background:var(--success); color:#fff; border-color:transparent;">
                🪑 Anda berada di: <strong><?= esc($tableInfo['name']) ?></strong>
            </div>
        <?php else: ?>
            <div class="hero-badge">🌟 Menu Terbaru Tersedia</div>
        <?php endif; ?>
        <h1>Temukan <span>Cita Rasa</span><br>Terbaik Kami</h1>
        <p>Jelajahi pilihan menu lezat yang siap memanjakan selera Anda setiap hari.</p>
    </div>
</section>

<!-- ── Category Filter ─────────────────────────────────────────────────── -->
<?php if (!empty($categories)): ?>
<div class="category-filter">
    <div class="category-filter-inner">
        <button class="cat-btn active" data-cat="all" id="catAll">🍽️ Semua</button>
        <?php foreach ($categories as $cat): ?>
            <?php if (!empty($cat['menus'])): ?>
                <button class="cat-btn" data-cat="cat-<?= $cat['id'] ?>" id="catBtn-<?= $cat['id'] ?>">
                    <?= esc($cat['name']) ?>
                </button>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- ── Menu Sections ────────────────────────────────────────────────────── -->
<div class="menu-section" id="menuContainer">

    <?php if (empty($categories)): ?>
        <div style="text-align:center;padding:80px 20px;color:var(--text-light);">
            <div style="font-size:72px;margin-bottom:20px;opacity:.3;">🍽️</div>
            <h2 style="font-size:24px;color:var(--dark);margin-bottom:10px;">Menu Belum Tersedia</h2>
            <p>Silakan kembali lagi nanti atau hubungi admin.</p>
        </div>
    <?php else: ?>

        <?php $hasAnyMenu = false; ?>
        <?php foreach ($categories as $cat): ?>
            <?php if (empty($cat['menus'])) continue; ?>
            <?php $hasAnyMenu = true; ?>

        <div class="category-section" data-section="cat-<?= $cat['id'] ?>" id="section-<?= $cat['id'] ?>">
            <div class="section-header">
                <div class="section-icon">
                    <?php
                    $icons = ['Makanan' => '🍜', 'Minuman' => '🥤', 'Dessert' => '🍰', 'Snack' => '🍟'];
                    echo $icons[$cat['name']] ?? '🍽️';
                    ?>
                </div>
                <h2 class="section-title"><?= esc($cat['name']) ?></h2>
                <span class="section-count"><?= count($cat['menus']) ?> item</span>
            </div>

            <div class="menu-grid">
                <?php foreach ($cat['menus'] as $menu): ?>
                <div class="menu-card">
                    <div class="menu-card-img">
                        <?php if ($menu['image_url']): ?>
                            <img src="<?= esc($menu['image_url']) ?>"
                                 alt="<?= esc($menu['name']) ?>"
                                 loading="lazy">
                        <?php else: ?>
                            <div class="menu-card-img-placeholder">
                                <?php echo $icons[$cat['name']] ?? '🍽️'; ?>
                            </div>
                        <?php endif; ?>
                        <div class="menu-card-badge">
                            Rp <?= number_format($menu['price'], 0, ',', '.') ?>
                        </div>
                    </div>
                    <div class="menu-card-body">
                        <div class="menu-card-name"><?= esc($menu['name']) ?></div>
                        <?php if ($menu['description']): ?>
                            <div class="menu-card-desc"><?= esc($menu['description']) ?></div>
                        <?php else: ?>
                            <div class="menu-card-desc" style="font-style:italic;">Tidak ada deskripsi</div>
                        <?php endif; ?>
                        <div class="menu-card-footer">
                            <span class="menu-price">Rp <?= number_format($menu['price'], 0, ',', '.') ?></span>
                            <button class="menu-order-btn" onclick="alert('Silakan pesan langsung ke kasir!')">
                                Pesan
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php endforeach; ?>

        <?php if (!$hasAnyMenu): ?>
            <div style="text-align:center;padding:80px 20px;color:var(--text-light);">
                <div style="font-size:72px;margin-bottom:20px;opacity:.3;">😔</div>
                <h2 style="font-size:24px;color:var(--dark);margin-bottom:10px;">Belum ada menu tersedia</h2>
                <p>Semua menu sedang tidak tersedia. Coba lagi nanti.</p>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>

<!-- ── Footer ──────────────────────────────────────────────────────────── -->
<footer class="pub-footer">
    <p>&copy; <?= date('Y') ?> <strong>Digital Menu</strong> — Powered by CodeIgniter 4 &amp; AWS S3</p>
</footer>

<script>
// Category filter logic
const catBtns = document.querySelectorAll('.cat-btn');
const sections = document.querySelectorAll('.category-section');

catBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        // Update active button
        catBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const target = btn.dataset.cat;

        if (target === 'all') {
            sections.forEach(s => {
                s.style.display = 'block';
                s.style.animation = 'slideUp .4s ease';
            });
        } else {
            sections.forEach(s => {
                if (s.dataset.section === target) {
                    s.style.display = 'block';
                    s.style.animation = 'slideUp .4s ease';
                } else {
                    s.style.display = 'none';
                }
            });
        }
    });
});
</script>

</body>
</html>
