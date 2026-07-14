<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('errors') || isset($errors)): ?>
    <?php $errs = session()->getFlashdata('errors') ?? $errors; ?>
    <div class="alert alert-danger">
        ⚠️ <strong>Ada kesalahan:</strong><br>
        <?php foreach ((array)$errs as $err): ?>
            • <?= esc($err) ?><br>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
    <a href="<?= site_url('/menu') ?>" class="btn btn-ghost btn-sm">← Kembali</a>
    <h1 style="font-size:20px;font-weight:700;"><?= esc($pageTitle) ?></h1>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;">

    <!-- ── Main Form ───────────────────────────────────────── -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">📋 Informasi Menu</h2>
        </div>
        <div class="card-body">
            <form action="<?= esc($action) ?>" method="POST" enctype="multipart/form-data" id="menuForm">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Menu <span>*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            placeholder="Contoh: Nasi Goreng Spesial"
                            value="<?= esc(old('name', $menu['name'] ?? '')) ?>"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="category_id">Kategori <span>*</span></label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"
                                    <?= (old('category_id', $menu['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                                    <?= esc($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea
                        id="description"
                        name="description"
                        class="form-control"
                        placeholder="Deskripsi singkat tentang menu ini..."
                        rows="3"
                    ><?= esc(old('description', $menu['description'] ?? '')) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="price">Harga (Rp) <span>*</span></label>
                        <input
                            type="number"
                            id="price"
                            name="price"
                            class="form-control"
                            placeholder="Contoh: 25000"
                            value="<?= esc(old('price', $menu['price'] ?? '')) ?>"
                            min="0"
                            step="500"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Ketersediaan</label>
                        <label class="form-check" style="margin-top:10px;">
                            <input
                                type="checkbox"
                                name="is_available"
                                id="is_available"
                                value="1"
                                <?= (old('is_available', $menu['is_available'] ?? 1)) ? 'checked' : '' ?>
                            >
                            <span class="form-check-label">Tersedia untuk dipesan</span>
                        </label>
                    </div>
                </div>

                <div style="display:flex;gap:12px;margin-top:8px;">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        💾 <?= ($menu) ? 'Perbarui Menu' : 'Simpan Menu' ?>
                    </button>
                    <a href="<?= site_url('/menu') ?>" class="btn btn-ghost">Batal</a>
                </div>

            </form>
        </div>
    </div>

    <!-- ── Image Upload Panel ───────────────────────────────── -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">🖼️ Foto Menu</h2>
        </div>
        <div class="card-body">
            <!-- Existing image -->
            <?php if (!empty($menu['image_url'])): ?>
                <div style="margin-bottom:16px;text-align:center;">
                    <img src="<?= esc($menu['image_url']) ?>"
                         alt="Current"
                         style="width:100%;max-height:200px;object-fit:cover;border-radius:10px;border:2px solid var(--border);"
                         id="currentImg">
                    <p style="font-size:11px;color:var(--text-muted);margin-top:6px;">Foto saat ini (dari S3)</p>
                </div>
            <?php endif; ?>

            <div class="upload-area" id="uploadArea">
                <input type="file" name="image" id="imageInput" accept="image/*" form="menuForm">
                <div class="upload-icon">📁</div>
                <p style="font-size:14px;font-weight:600;margin-bottom:4px;">
                    <?= ($menu && $menu['image_url']) ? 'Ganti Foto' : 'Upload Foto' ?>
                </p>
                <p class="upload-text">Klik atau drag & drop gambar<br><small>JPG, PNG, WEBP — maks. 5 MB</small></p>
            </div>

            <div class="upload-preview" id="uploadPreview">
                <p style="font-size:12px;color:var(--text-muted);margin-bottom:8px;">Preview foto baru:</p>
                <img id="previewImg" src="" alt="Preview">
            </div>

            <div style="margin-top:16px;padding:12px;background:var(--bg);border-radius:8px;">
                <p style="font-size:11px;color:var(--text-muted);line-height:1.6;">
                    ☁️ <strong>AWS S3:</strong> Gambar akan diupload ke bucket S3 secara otomatis.<br>
                    📍 Jika S3 belum dikonfigurasi, gambar disimpan lokal.
                </p>
            </div>
        </div>
    </div>

</div>

<script>
// Image preview
const input   = document.getElementById('imageInput');
const preview = document.getElementById('uploadPreview');
const previewImg = document.getElementById('previewImg');
const area    = document.getElementById('uploadArea');

input.addEventListener('change', function () {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
    }
});

// Drag & drop visual
area.addEventListener('dragover', e => { e.preventDefault(); area.classList.add('drag-over'); });
area.addEventListener('dragleave', () => area.classList.remove('drag-over'));
area.addEventListener('drop', e => { e.preventDefault(); area.classList.remove('drag-over'); });

// Disable button on submit
document.getElementById('menuForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    btn.textContent = '⏳ Menyimpan...';
    btn.disabled = true;
});
</script>

<?= $this->endSection() ?>
