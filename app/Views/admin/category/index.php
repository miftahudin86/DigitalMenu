<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">✅ <?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">⚠️ <?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

    <!-- ── Add Category ────────────────────────────────────── -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">➕ Tambah Kategori</h2>
        </div>
        <div class="card-body">
            <form action="<?= site_url('/category/store') ?>" method="POST" id="addCatForm">
                <div class="form-group">
                    <label class="form-label" for="newCatName">Nama Kategori <span style="color:var(--danger)">*</span></label>
                    <input type="text" id="newCatName" name="name" class="form-control"
                           placeholder="Contoh: Minuman Segar" required>
                </div>
                <button type="submit" class="btn btn-primary">💾 Simpan Kategori</button>
            </form>
        </div>
    </div>

    <!-- ── Category List ───────────────────────────────────── -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">🏷️ Daftar Kategori</h2>
            <span class="badge badge-info"><?= count($categories) ?> kategori</span>
        </div>
        <div class="table-wrapper">
            <?php if (empty($categories)): ?>
                <div class="empty-state" style="padding:40px;">
                    <div class="empty-icon">🏷️</div>
                    <h3>Belum ada kategori</h3>
                    <p>Tambahkan kategori pertama Anda</p>
                </div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($categories as $cat): ?>
                    <tr>
                        <td style="color:var(--text-muted);font-size:12px;"><?= $no++ ?></td>
                        <td><strong><?= esc($cat['name']) ?></strong></td>
                        <td>
                            <div class="action-group">
                                <!-- Edit Button triggers modal -->
                                <button class="btn btn-outline btn-sm"
                                        onclick="openEditModal(<?= $cat['id'] ?>, '<?= esc($cat['name'], 'js') ?>')">
                                    ✏️ Edit
                                </button>
                                <a href="<?= site_url('/category/delete/'.$cat['id']) ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Hapus kategori ini? Menu yang terhubung akan ikut terhapus!')">
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

</div>

<!-- ── Edit Modal ─────────────────────────────────────────────────────── -->
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title">✏️ Edit Kategori</span>
            <button class="modal-close" onclick="closeEditModal()">×</button>
        </div>
        <form id="editForm" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="editName">Nama Kategori <span style="color:var(--danger)">*</span></label>
                    <input type="text" id="editName" name="name" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name) {
    document.getElementById('editName').value = name;
    document.getElementById('editForm').action = '<?= site_url('/category/update/') ?>' + id;
    document.getElementById('editModal').classList.add('active');
}
function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}
// Close on overlay click
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>

<?= $this->endSection() ?>
