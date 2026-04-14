<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- HERO -->
<section class="hero-section">
    <div class="hero-inner">
        <h1>Temukan Produk UMKM Lokal Terbaik</h1>
        <p>Belanja langsung dari pemilik UMKM, pesan via WhatsApp!</p>
        <form method="get" action="<?= base_url() ?>" class="search-form">
            <input type="text" name="q" value="<?= htmlspecialchars($keyword ?? '') ?>"
                   placeholder="Cari produk atau nama toko..." class="search-input">
            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>
    </div>
</section>

<!-- FILTER KATEGORI -->
<section class="katalog-section">
    <div class="container-katalog">
        <div class="filter-kategori">
            <a href="<?= base_url() ?>" class="filter-btn <?= !$kategori_aktif ? 'active' : '' ?>">Semua</a>
            <?php foreach ($kategori as $k): ?>
                <a href="<?= base_url('?kategori=' . $k->id) ?>"
                   class="filter-btn <?= ($kategori_aktif == $k->id) ? 'active' : '' ?>">
                    <?= htmlspecialchars($k->nama) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- GRID PRODUK -->
        <?php if (empty($produk)): ?>
            <div class="empty-state">
                <i class="fas fa-box-open fa-3x mb-3"></i>
                <p>Tidak ada produk yang ditemukan.</p>
            </div>
        <?php else: ?>
        <div class="produk-grid">
            <?php foreach ($produk as $p): ?>
            <div class="produk-card">
                <a href="<?= base_url('produk/' . $p->id) ?>" class="card-img-wrap">
                    <?php if ($p->foto): ?>
                        <img src="<?= base_url('uploads/produk/' . $p->foto) ?>"
                             alt="<?= htmlspecialchars($p->nama) ?>">
                    <?php else: ?>
                        <div class="no-img"><i class="fas fa-image"></i></div>
                    <?php endif; ?>
                    <?php if ($p->kategori_nama): ?>
                        <span class="badge-kategori"><?= htmlspecialchars($p->kategori_nama) ?></span>
                    <?php endif; ?>
                </a>
                <div class="card-body">
                    <p class="card-toko"><i class="fas fa-store"></i> <?= htmlspecialchars($p->nama_toko) ?></p>
                    <h3 class="card-nama"><?= htmlspecialchars($p->nama) ?></h3>
                    <p class="card-harga">Rp <?= number_format($p->harga, 0, ',', '.') ?></p>
                    <div class="card-actions">
                        <a href="<?= base_url('produk/' . $p->id) ?>" class="btn-detail">Detail</a>
                        <a href="<?= base_url('keranjang/tambah/' . $p->id) ?>" class="btn-keranjang">
                            <i class="fas fa-cart-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Keranjang floating button -->
<?php
$keranjang = $this->session->userdata('keranjang') ?: [];
$total_item = array_sum(array_column($keranjang, 'qty'));
?>
<?php if ($total_item > 0): ?>
<a href="<?= base_url('keranjang') ?>" class="fab-keranjang">
    <i class="fas fa-shopping-cart"></i>
    <span class="fab-badge"><?= $total_item ?></span>
</a>
<?php endif; ?>