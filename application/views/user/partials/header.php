<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Toko UMKM' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/user/css/style.css') ?>">
</head>
<body>

<nav class="navbar">
    <!-- Brand -->
    <a href="<?= site_url('index.php/user') ?>" class="navbar-brand">
        <span class="brand-dot"></span> TokoKu
    </a>

    <!-- Kanan: cart + profile -->
    <div class="navbar-right">

        <!-- Keranjang -->
        <a href="<?= site_url('index.php/user/keranjang') ?>" class="cart-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span class="cart-count" id="cartCount">
                <?php
                    $keranjang = $this->session->userdata('keranjang') ?? [];
                    echo count($keranjang);
                ?>
            </span>
        </a>

        <!-- Profile Dropdown -->
        <div class="profile-wrap" id="profileWrap">
            <button class="profile-btn" id="profileBtn" type="button">
                <div class="profile-avatar">
                    <?= strtoupper(substr($this->session->userdata('nama') ?? 'U', 0, 1)) ?>
                </div>
                <span class="profile-nama"><?= htmlspecialchars($this->session->userdata('nama') ?? 'User') ?></span>
                <svg class="profile-caret" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <div class="dropdown-avatar">
                        <?= strtoupper(substr($this->session->userdata('nama') ?? 'U', 0, 1)) ?>
                    </div>
                    <div>
                        <p class="dropdown-nama"><?= htmlspecialchars($this->session->userdata('nama') ?? 'User') ?></p>
                        <p class="dropdown-role">Member</p>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="<?= site_url('index.php/user') ?>" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Beranda
                </a>
                <a href="<?= site_url('index.php/user/keranjang') ?>" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Keranjang
                </a>

                <!-- ← TAMBAHAN: Riwayat Pesanan -->
                <a href="<?= site_url('index.php/user/riwayat') ?>" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Riwayat Pesanan
                </a>

                <div class="dropdown-divider"></div>
                <a href="<?= site_url('index.php/user/logout') ?>" class="dropdown-item logout-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </a>
            </div>
        </div>

    </div>
</nav>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn  = document.getElementById('profileBtn');
    var wrap = document.getElementById('profileWrap');
    if (btn && wrap) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            wrap.classList.toggle('open');
        });
        document.addEventListener('click', function(e) {
            if (!wrap.contains(e.target)) {
                wrap.classList.remove('open');
            }
        });
    }
});
</script>