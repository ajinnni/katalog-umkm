<!-- HERO -->
<section class="hero">
    <div class="hero-inner">
        <p class="hero-label">Produk Lokal Pilihan</p>
        <h1 class="hero-title">Belanja Mudah,<br>Langsung ke <em>UMKM</em></h1>
        <p class="hero-sub">Temukan produk terbaik dari usaha lokal kami.</p>
    </div>
    <div class="hero-shape"></div>
</section>

<!-- SEARCH & FILTER -->
<section class="search-section">
    <form method="GET" action="<?= site_url('user') ?>" class="search-form">
        <div class="search-wrap">
            <input
                type="text"
                name="q"
                placeholder="Cari produk..."
                value="<?= htmlspecialchars($keyword ?? '') ?>"
                class="search-input"
            >
            <button type="submit" class="search-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari
            </button>
        </div>

        <!-- Kategori chips -->
        <div class="kategori-chips">
            <a href="<?= site_url('user') ?>" class="chip <?= empty($kategori_aktif) ? 'active' : '' ?>">Semua</a>
            <?php foreach ($kategori as $k): ?>
            <a href="<?= site_url('user?kategori=' . $k->id) ?>" class="chip <?= ($kategori_aktif == $k->id) ? 'active' : '' ?>">
                <?= htmlspecialchars($k->nama) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </form>
</section>

<!-- PRODUK GRID -->
<section class="produk-section">
    <?php if (empty($produk)): ?>
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p>Produk tidak ditemukan.</p>
            <a href="<?= site_url('user') ?>" class="btn-outline">Lihat Semua</a>
        </div>
    <?php else: ?>
    <div class="produk-grid">
        <?php foreach ($produk as $p): ?>
        <div class="produk-card" data-id="<?= $p->id ?>">
            <div class="produk-img-wrap">
                <?php if ($p->foto): ?>
                    <img src="<?= base_url('uploads/produk/' . $p->foto) ?>" alt="<?= htmlspecialchars($p->nama) ?>" class="produk-img">
                <?php else: ?>
                    <div class="produk-img-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                <?php endif; ?>
                <span class="produk-badge"><?= htmlspecialchars($p->nama_kategori ?? '-') ?></span>
            </div>
            <div class="produk-body">
                <h3 class="produk-nama"><?= htmlspecialchars($p->nama) ?></h3>
                <p class="produk-harga">Rp <?= number_format($p->harga, 0, ',', '.') ?></p>
                <p class="produk-stok">Stok: <?= $p->stok ?></p>
                <form method="POST" action="<?= site_url('index.php/user/tambah_keranjang') ?>" class="form-keranjang">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="hidden" name="id_produk" value="<?= $p->id ?>">
                    <div class="qty-wrap">
                        <button type="button" class="qty-btn minus">−</button>
                        <input type="number" name="qty" value="1" min="1" max="<?= $p->stok ?>" class="qty-input">
                        <button type="button" class="qty-btn plus">+</button>
                    </div>
                    <button type="submit" class="btn-keranjang">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Tambah
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<script>
var BASE_URL  = '<?= base_url() ?>';
var CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
var CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';

// ── QTY BUTTONS ──────────────────────────────────────────
document.querySelectorAll('.qty-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var wrap  = btn.closest('.qty-wrap');
        var input = wrap.querySelector('.qty-input');
        var val   = parseInt(input.value) || 1;
        var max   = parseInt(input.max) || 99;

        if (btn.classList.contains('plus')) {
            if (val < max) input.value = val + 1;
        } else if (btn.classList.contains('minus')) {
            if (val > 1) input.value = val - 1;
        }
    });
});

// ── FORM SUBMIT ───────────────────────────────────────────
document.querySelectorAll('.form-keranjang').forEach(function(form) {
    var submitted = false;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (submitted) return;
        submitted = true;

        var btn     = form.querySelector('.btn-keranjang');
        var svgHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>';

        btn.disabled  = true;
        btn.innerHTML = 'Menambahkan...';

        var formData = new FormData(form);
        formData.set(CSRF_NAME, CSRF_HASH);

        fetch(BASE_URL + 'index.php/user/tambah_keranjang', {
            method : 'POST',
            body   : formData,
        })
        .then(function(res) {
            var ct = res.headers.get('content-type') || '';
            if (!ct.includes('application/json')) {
                throw new Error('Response bukan JSON — cek CSRF atau controller');
            }
            return res.json();
        })
        .then(function(data) {
            if (data.status === 'ok') {
                // update CSRF untuk request berikutnya
                if (data.csrf_hash) CSRF_HASH = data.csrf_hash;

                // update badge keranjang
                var badge = document.getElementById('cartCount');
                if (badge) badge.textContent = data.total;

                // redirect ke keranjang
                window.location.href = BASE_URL + 'index.php/user/keranjang';
            } else {
                submitted     = false;
                btn.disabled  = false;
                btn.innerHTML = svgHTML + ' Tambah';
            }
        })
        .catch(function(err) {
            console.error('Keranjang error:', err);
            submitted     = false;
            btn.disabled  = false;
            btn.innerHTML = svgHTML + ' Tambah';
        });
    });
});
</script>