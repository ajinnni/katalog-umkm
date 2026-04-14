<!-- KERANJANG PAGE -->
<section class="page-header">
    <div class="page-header-inner">
        <a href="<?= site_url('index.php/user') ?>" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Lanjut Belanja
        </a>
        <h2 class="page-title">Keranjang Belanja</h2>
    </div>
</section>

<section class="keranjang-section">
    <?php if (empty($keranjang)): ?>
    <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <p>Keranjang kamu masih kosong.</p>
        <a href="<?= site_url('index.php/user') ?>" class="btn-primary">Mulai Belanja</a>
    </div>
    <?php else: ?>

    <div class="keranjang-layout">
        <!-- TABEL KERANJANG -->
        <div class="keranjang-items">
            <div class="items-header">
                <span>Produk</span>
                <span>Harga</span>
                <span>Qty</span>
                <span>Subtotal</span>
                <span></span>
            </div>

            <?php foreach ($keranjang as $id => $item): ?>
            <div class="keranjang-row" data-id="<?= $id ?>">
                <div class="item-info">
                    <?php if (!empty($item['foto'])): ?>
                        <img src="<?= base_url('uploads/produk/' . $item['foto']) ?>"
                             alt="<?= htmlspecialchars($item['nama']) ?>" class="item-thumb">
                    <?php else: ?>
                        <div class="item-thumb-placeholder"></div>
                    <?php endif; ?>
                    <span class="item-nama"><?= htmlspecialchars($item['nama']) ?></span>
                </div>
                <span class="item-harga">Rp <?= number_format($item['harga'], 0, ',', '.') ?></span>
                <div class="item-qty">
                    <button class="qty-btn minus" data-id="<?= $id ?>">−</button>
                    <span class="qty-val"><?= $item['qty'] ?></span>
                    <button class="qty-btn plus" data-id="<?= $id ?>">+</button>
                </div>
                <span class="item-subtotal">Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?></span>
                <a href="<?= site_url('index.php/user/hapus_keranjang/' . $id) ?>"
                   class="btn-hapus" onclick="return confirm('Hapus item ini?')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- FORM CHECKOUT -->
        <div class="checkout-panel">
            <h3 class="panel-title">Data Pemesan</h3>
            <form method="POST" action="<?= site_url('index.php/user/checkout') ?>" id="formCheckout">
                <!-- CSRF CI3 -->
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                       value="<?= $this->security->get_csrf_hash() ?>">

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama"
                           placeholder="Masukkan nama lengkap" required class="form-input"
                           value="<?= htmlspecialchars($this->session->userdata('nama') ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="no_wa">No WhatsApp</label>
                    <input type="text" id="no_wa" name="no_wa"
                           placeholder="08xxxxxxxxxx" required class="form-input"
                           value="<?= htmlspecialchars($this->session->userdata('no_wa') ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat Pengiriman</label>
                    <textarea id="alamat" name="alamat"
                              placeholder="Jl. Contoh No. 1, Kota..."
                              rows="4" required class="form-input"></textarea>
                </div>

                <div class="ringkasan">
                    <div class="ringkasan-row">
                        <span>Total Item</span>
                        <span><?= array_sum(array_column($keranjang, 'qty')) ?> item</span>
                    </div>
                    <div class="ringkasan-row grand">
                        <span>Grand Total</span>
                        <span>Rp <?= number_format($grand_total, 0, ',', '.') ?></span>
                    </div>
                </div>

                <button type="submit" class="btn-wa">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.556 4.123 1.529 5.856L.057 23.885l6.154-1.612A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.894a9.869 9.869 0 01-5.032-1.378l-.361-.214-3.741.981.998-3.648-.235-.374A9.861 9.861 0 012.106 12C2.106 6.58 6.58 2.106 12 2.106S21.894 6.58 21.894 12 17.42 21.894 12 21.894z"/></svg>
                    Pesan via WhatsApp
                </button>
            </form>
        </div>
    </div>

    <?php endif; ?>
</section>

<script>
// Update qty via AJAX
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id  = this.dataset.id;
        const act = this.classList.contains('plus') ? 'tambah' : 'kurang';
        fetch('<?= site_url('index.php/user/update_keranjang') ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id=${id}&aksi=${act}&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>`
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) location.reload();
        });
    });
});
</script>