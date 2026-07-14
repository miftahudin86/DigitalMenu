<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">✅ <?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">⚠️ <?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<!-- Toolbar -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <div>
        <h1 style="font-size:20px;font-weight:700;">🍜 Daftar Menu</h1>
        <p style="font-size:13px;color:var(--text-muted);"><?= count($menus) ?> menu ditemukan</p>
    </div>
    <a href="<?= site_url('/menu/create') ?>" class="btn btn-primary" id="addMenuBtn">
        ➕ Tambah Menu Baru
    </a>
</div>

<div class="card">
    <div class="table-wrapper">
        <?php if (empty($menus)): ?>
            <div class="empty-state">
                <div class="empty-icon">🍽️</div>
                <h3>Belum ada menu</h3>
                <p>Klik tombol "Tambah Menu Baru" untuk mulai</p>
            </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Gambar</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($menus as $menu): ?>
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;"><?= $no++ ?></td>
                    <td>
                        <?php if ($menu['image_url']): ?>
                            <img src="<?= esc($menu['image_url']) ?>" alt="<?= esc($menu['name']) ?>" class="menu-thumb">
                        <?php else: ?>
                            <div class="menu-thumb-placeholder">🍽️</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?= esc($menu['name']) ?></strong>
                        <?php if ($menu['description']): ?>
                            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                                <?= esc(mb_strimwidth($menu['description'], 0, 50, '...')) ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge badge-info"><?= esc($menu['category_name'] ?? '-') ?></span></td>
                    <td><span class="price-tag">Rp <?= number_format($menu['price'], 0, ',', '.') ?></span></td>
                    <td>
                        <a href="<?= site_url('/menu/toggle/'.$menu['id']) ?>" class="badge <?= $menu['is_available'] ? 'badge-success' : 'badge-danger' ?>" style="cursor:pointer;text-decoration:none;" title="Klik untuk toggle">
                            <?= $menu['is_available'] ? '✅ Tersedia' : '❌ Tidak Tersedia' ?>
                        </a>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="<?= site_url('/menu/edit/'.$menu['id']) ?>" class="btn btn-outline btn-sm">✏️ Edit</a>
                            <a href="<?= site_url('/menu/delete/'.$menu['id']) ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Hapus menu ini? Gambar di S3 juga akan dihapus.')">
                                🗑️ Hapus
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
