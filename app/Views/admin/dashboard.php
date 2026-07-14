<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple">🍜</div>
        <div>
            <div class="stat-value"><?= $total_menu ?></div>
            <div class="stat-label">Total Menu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink">🏷️</div>
        <div>
            <div class="stat-value"><?= $total_category ?></div>
            <div class="stat-label">Kategori</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">✅</div>
        <div>
            <div class="stat-value"><?= $total_available ?></div>
            <div class="stat-label">Menu Tersedia</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">❌</div>
        <div>
            <div class="stat-value"><?= $total_menu - $total_available ?></div>
            <div class="stat-label">Tidak Tersedia</div>
        </div>
    </div>
</div>

<!-- Recent Menus -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">🕐 Menu Terbaru</h2>
        <a href="<?= site_url('/menu/create') ?>" class="btn btn-primary btn-sm">+ Tambah Menu</a>
    </div>
    <div class="table-wrapper">
        <?php if (empty($recent_menus)): ?>
            <div class="empty-state">
                <div class="empty-icon">🍽️</div>
                <h3>Belum ada menu</h3>
                <p>Mulai tambahkan menu pertama Anda!</p>
            </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_menus as $menu): ?>
                <tr>
                    <td>
                        <?php if ($menu['image_url']): ?>
                            <img src="<?= esc($menu['image_url']) ?>" alt="<?= esc($menu['name']) ?>" class="menu-thumb">
                        <?php else: ?>
                            <div class="menu-thumb-placeholder">🍽️</div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= esc($menu['name']) ?></strong></td>
                    <td><span class="badge badge-info"><?= esc($menu['category_name'] ?? '-') ?></span></td>
                    <td><span class="price-tag">Rp <?= number_format($menu['price'], 0, ',', '.') ?></span></td>
                    <td>
                        <?php if ($menu['is_available']): ?>
                            <span class="badge badge-success">✅ Tersedia</span>
                        <?php else: ?>
                            <span class="badge badge-danger">❌ Tidak Tersedia</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="<?= site_url('/menu/edit/'.$menu['id']) ?>" class="btn btn-outline btn-sm">✏️ Edit</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Links -->
<div style="display:flex;gap:16px;margin-top:20px;flex-wrap:wrap;">
    <a href="<?= site_url('/menu') ?>" class="btn btn-primary">🍜 Kelola Menu</a>
    <a href="<?= site_url('/category') ?>" class="btn btn-outline">🏷️ Kelola Kategori</a>
    <a href="<?= site_url('/') ?>" target="_blank" class="btn btn-ghost">🌐 Lihat Menu Publik</a>
</div>

<?= $this->endSection() ?>
