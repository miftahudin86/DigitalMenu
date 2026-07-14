<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">✅ <?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">⚠️ <?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <div>
        <h1 style="font-size:20px;font-weight:700;">🪑 Daftar Meja</h1>
        <p style="font-size:13px;color:var(--text-muted);"><?= count($tables) ?> meja terdaftar</p>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="<?= site_url('/tables/print-all') ?>" target="_blank" class="btn btn-outline">🖨️ Cetak Semua QR</a>
        <button class="btn btn-primary" onclick="openAddModal()">➕ Tambah Meja</button>
    </div>
</div>

<div class="card">
    <div class="table-wrapper">
        <?php if (empty($tables)): ?>
            <div class="empty-state" style="padding:60px;">
                <div class="empty-icon">🪑</div>
                <h3>Belum ada meja</h3>
                <p>Mulai tambahkan meja pertama Anda</p>
            </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>No. Meja</th>
                    <th>Nama Meja</th>
                    <th>Kapasitas</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($tables as $t): ?>
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;"><?= $no++ ?></td>
                    <td><strong style="font-size:16px;"><?= esc($t['number']) ?></strong></td>
                    <td><strong><?= esc($t['name']) ?></strong></td>
                    <td><?= esc($t['capacity']) ?> Orang</td>
                    <td><?= esc($t['location'] ?? '-') ?></td>
                    <td>
                        <?php if ($t['is_active']): ?>
                            <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="<?= site_url('/tables/qr/'.$t['id']) ?>" target="_blank" class="btn btn-primary btn-sm" title="Lihat/Cetak QR">
                                📱 QR
                            </a>
                            <button class="btn btn-outline btn-sm"
                                    onclick="openEditModal(<?= $t['id'] ?>, '<?= esc($t['name'], 'js') ?>', <?= $t['number'] ?>, <?= $t['capacity'] ?>, '<?= esc($t['location'] ?? '', 'js') ?>', <?= $t['is_active'] ?>)">
                                ✏️ Edit
                            </button>
                            <a href="<?= site_url('/tables/delete/'.$t['id']) ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Hapus meja ini?')">
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

<!-- ── Add/Edit Modal ─────────────────────────────────────────────────────── -->
<div class="modal-overlay" id="tableModal">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title" id="modalTitle">➕ Tambah Meja</span>
            <button class="modal-close" onclick="closeTableModal()">×</button>
        </div>
        <form id="tableForm" method="POST" action="<?= site_url('/tables/store') ?>">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="number">Nomor Meja <span style="color:var(--danger)">*</span></label>
                        <input type="number" id="number" name="number" class="form-control" required min="1" placeholder="Misal: 1">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="capacity">Kapasitas Kursi</label>
                        <input type="number" id="capacity" name="capacity" class="form-control" min="1" value="4" placeholder="Misal: 4">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="name">Nama/Label (Opsional)</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Otomatis diisi 'Meja X' jika kosong">
                </div>
                <div class="form-group">
                    <label class="form-label" for="location">Lokasi (Opsional)</label>
                    <input type="text" id="location" name="location" class="form-control" placeholder="Misal: Indoor / Lantai 2">
                </div>
                <div class="form-group" id="statusGroup" style="display:none;">
                    <label class="form-label">Status Aktif</label>
                    <label class="form-check" style="margin-top:10px;">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                        <span class="form-check-label">Meja bisa digunakan</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeTableModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSubmit">💾 Simpan Meja</button>
            </div>
        </form>
    </div>
</div>

<script>
const modal = document.getElementById('tableModal');
const form = document.getElementById('tableForm');
const modalTitle = document.getElementById('modalTitle');
const statusGroup = document.getElementById('statusGroup');
const btnSubmit = document.getElementById('btnSubmit');

function openAddModal() {
    modalTitle.textContent = '➕ Tambah Meja';
    form.action = '<?= site_url('/tables/store') ?>';
    form.reset();
    document.getElementById('capacity').value = 4;
    statusGroup.style.display = 'none'; // hidden for create
    btnSubmit.innerHTML = '💾 Simpan Meja';
    modal.classList.add('active');
}

function openEditModal(id, name, number, capacity, location, isActive) {
    modalTitle.textContent = '✏️ Edit Meja';
    form.action = '<?= site_url('/tables/update/') ?>' + id;
    
    document.getElementById('name').value = name;
    document.getElementById('number').value = number;
    document.getElementById('capacity').value = capacity;
    document.getElementById('location').value = location;
    
    document.getElementById('is_active').checked = isActive == 1;
    statusGroup.style.display = 'block'; // show for edit
    
    btnSubmit.innerHTML = '💾 Update Meja';
    modal.classList.add('active');
}

function closeTableModal() {
    modal.classList.remove('active');
}

// Close on overlay click
modal.addEventListener('click', function(e) {
    if (e.target === this) closeTableModal();
});
</script>

<?= $this->endSection() ?>
