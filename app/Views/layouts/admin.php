<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> — Digital Menu</title>
    <link rel="stylesheet" href="<?= base_url('css/admin.css') ?>">
</head>
<body>
<div class="layout">

    <!-- ── Sidebar ───────────────────────────────────────────── -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">🍽️</div>
            <div>
                <div class="brand-text">DigitalMenu</div>
                <div class="brand-sub">Management System</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Main</div>
            <a href="<?= site_url('/dashboard') ?>" class="nav-item <?= (uri_string() === 'dashboard') ? 'active' : '' ?>">
                <span class="nav-icon">📊</span>
                Dashboard
            </a>

            <div class="nav-label" style="margin-top:8px;">Management</div>
            <a href="<?= site_url('/menu') ?>" class="nav-item <?= (str_starts_with(uri_string(), 'menu')) ? 'active' : '' ?>">
                <span class="nav-icon">🍜</span>
                Menu
            </a>
            <a href="<?= site_url('/category') ?>" class="nav-item <?= (str_starts_with(uri_string(), 'category')) ? 'active' : '' ?>">
                <span class="nav-icon">🏷️</span>
                Kategori
            </a>
            <a href="<?= site_url('/tables') ?>" class="nav-item <?= (str_starts_with(uri_string(), 'tables')) ? 'active' : '' ?>">
                <span class="nav-icon">🪑</span>
                Meja & QR Code
            </a>

            <div class="nav-label" style="margin-top:8px;">View</div>
            <a href="<?= site_url('/') ?>" target="_blank" class="nav-item">
                <span class="nav-icon">🌐</span>
                Lihat Menu Publik
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="<?= site_url('/auth/logout') ?>" class="nav-item" style="color:rgba(255,71,87,.7);">
                <span class="nav-icon">🚪</span>
                Logout
            </a>
        </div>
    </aside>

    <!-- ── Main Content ────────────────────────────────────────── -->
    <div class="main">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="topbar-left">
                <div class="page-title"><?= $pageTitle ?? 'Dashboard' ?></div>
                <div class="page-breadcrumb">Digital Menu Management</div>
            </div>
            <div class="topbar-right">
                <div class="admin-badge">
                    <div class="avatar"><?= strtoupper(substr(session()->get('username') ?? 'A', 0, 1)) ?></div>
                    <?= esc(session()->get('username')) ?>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="content">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>
</body>
</html>
