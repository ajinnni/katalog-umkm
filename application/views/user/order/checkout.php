<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
/* ── Checkout — tema sesuai landing page ── */
.checkout-wrap {
    max-width: 860px;
    margin: 0 auto;
    padding: 32px 16px 64px;
}
.checkout-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 2rem;
    color: #2c1a0e;
    margin-bottom: 4px;
}
.checkout-title em { color: #8B6F47; font-style: italic; }
.checkout-sub { color: #7a6652; font-size: 0.9rem; margin-bottom: 32px; }

.checkout-grid { display: grid; grid-template-columns: 1fr 340px; gap: 24px; }
@media(max-width:720px){ .checkout-grid { grid-template-columns: 1fr; } }

.co-card {
    background: #fff;
    border-radius: 18px;
    padding: 28px;
    box-shadow: 0 4px 20px rgba(74,55,40,.1);
    border: 1.5px solid #e8ddd0;
    margin-bottom: 20px;
}
.co-card-title {
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: #8B6F47;
    margin-bottom: 16px;
}

/* Metode pengiriman cards */
.method-options { display: flex; flex-direction: column; gap: 12px; }
.method-card {
    border: 2px solid #e8ddd0;
    border-radius: 14px;
    padding: 16px;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    gap: 14px;
}
.method-card:hover { border-color: #8B6F47; transform: translateY(-1px); }
.method-card.active-pickup { border-color: #4A6741; background: #EBF3EA; }
.method-card.active-delivery { border-color: #3B6FA0; background: #EAF1F8; }
.method-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0;
}
.pickup-icon { background: #D4EDD1; }
.delivery-icon { background: #D2E4F0; }
.method-info { flex: 1; }
.method-title { font-weight: 700; font-size: 0.95rem; color: #2c1a0e; }
.method-desc { font-size: 0.78rem; color: #7a6652; margin-top: 2px; }
.method-radio {
    width: 20px; height: 20px; border-radius: 50%;
    border: 2px solid #c8b9a8;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; transition: all .2s;
}
.active-pickup .method-radio { background: #4A6741; border-color: #4A6741; }
.active-delivery .method-radio { background: #3B6FA0; border-color: #3B6FA0; }
.method-radio-dot {
    width: 8px; height: 8px; background: white;
    border-radius: 50%; opacity: 0; transform: scale(0);
    transition: all .2s;
}
.active-pickup .method-radio-dot,
.active-delivery .method-radio-dot { opacity: 1; transform: scale(1); }

/* Address form */
.addr-form { margin-top: 16px; animation: fadeDown .25s ease; }
@keyframes fadeDown {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
.form-group { margin-bottom: 14px; }
.form-group label { display: block; font-size: .8rem; font-weight: 600; color: #4a3728; margin-bottom: 5px; }
.form-group input,
.form-group textarea,
.form-group select {
    width: 100%; padding: 10px 13px;
    border: 1.5px solid #e8ddd0; border-radius: 10px;
    font-size: .85rem; color: #2c1a0e;
    outline: none; transition: border-color .2s;
    font-family: inherit; resize: none; background: #fdfbf8;
}
.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus { border-color: #8B6F47; }

/* Pickup info */
.pickup-info {
    margin-top: 14px; padding: 12px 14px;
    background: #fff; border-radius: 10px;
    border: 1.5px solid #A8D5A2;
    font-size: .82rem; color: #4A6741;
    display: flex; gap: 8px;
    animation: fadeDown .25s ease;
}

/* Order summary */
.summary-card { background: #2c1a0e; color: #fff; border-radius: 18px; padding: 24px; }
.summary-label { font-size: .72rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; opacity: .55; margin-bottom: 14px; }
.summary-item { display: flex; justify-content: space-between; font-size: .85rem; padding: 5px 0; }
.summary-item span:first-child { opacity: .7; }
.summary-item span:last-child { font-weight: 600; }
.summary-divider { border: none; border-top: 1px solid rgba(255,255,255,.15); margin: 12px 0; }
.summary-total { display: flex; justify-content: space-between; font-size: 1rem; }
.summary-total span:last-child { font-weight: 800; color: #f0c070; font-size: 1.1rem; }

.btn-checkout {
    width: 100%; margin-top: 18px; padding: 15px;
    background: #8B6F47; color: white; border: none;
    border-radius: 12px; font-family: inherit;
    font-size: .9rem; font-weight: 700; cursor: pointer;
    transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-checkout:hover { background: #6d5336; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(139,111,71,.35); }

.toko-info {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 14px; background: #f8f4ee;
    border-radius: 10px; margin-bottom: 16px;
    border: 1.5px solid #e8ddd0; font-size: .82rem;
}
.toko-info strong { display: block; font-size: .88rem; color: #2c1a0e; }

/* Produk list */
.produk-list-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0; border-bottom: 1px solid #f0e8dc;
}
.produk-list-item:last-child { border-bottom: none; }
.produk-thumb {
    width: 48px; height: 48px; border-radius: 10px;
    object-fit: cover; background: #f0e8dc; flex-shrink: 0;
}
.produk-thumb-placeholder {
    width: 48px; height: 48px; border-radius: 10px;
    background: #f0e8dc; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; color: #c8b9a8;
}
.produk-list-nama { font-weight: 600; font-size: .87rem; color: #2c1a0e; }
.produk-list-sub { font-size: .78rem; color: #7a6652; }
.produk-list-harga { margin-left: auto; font-weight: 700; font-size: .87rem; color: #2c1a0e; text-align: right; }

.flash-error {
    background: #fdecea; color: #c0392b;
    border-radius: 12px; padding: 12px 16px;
    margin-bottom: 20px; font-size: .88rem;
    border: 1.5px solid #f5c0b8;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    color: #8B6F47; /* 🔥 lebih kontras */
    text-decoration: none;
    margin-bottom: 16px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.back-link svg {
    stroke: #8B6F47;
}

.back-link:hover {
    color: #2c1a0e; /* 🔥 lebih gelap saat hover */
    transform: translateX(-3px);
}

.back-link:hover svg {
    stroke: #2c1a0e;
}
</style>

<div class="checkout-wrap">
    <h1 class="checkout-title">Konfirmasi <em>Pesanan</em></h1>
    <p class="checkout-sub">Periksa produk dan pilih metode pengiriman</p>

    <?php if ($this->session->flashdata('error')): ?>
    <div class="flash-error">⚠️ <?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= site_url('order/proses') ?>" id="formCheckout">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

        <div class="checkout-grid">

            <!-- Kiri -->
            <div>
<a href="<?= site_url('user/keranjang') ?>" class="back-link">
    ← Kembali ke Keranjang
</a>
                <!-- Info Toko -->
                <?php if ($umkm): ?>
                <div class="co-card">
                    
                    <div class="co-card-title">🏪 Toko</div>
                    <div class="toko-info">
                        <span style="font-size:1.4rem">🏬</span>
                        <div>
                            <strong><?= htmlspecialchars($umkm->nama_toko) ?></strong>
                            <?php if ($umkm->alamat ?? null): ?>
                            <span><?= htmlspecialchars($umkm->alamat) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Metode Pengiriman -->
                <div class="co-card">
                    <div class="co-card-title">🚚 Metode Pengiriman</div>
                    <div class="method-options">

                        <!-- Ambil Sendiri -->
                        <div class="method-card" id="cardPickup" onclick="setMetode('pickup')">
                            <div class="method-icon pickup-icon">🏪</div>
                            <div class="method-info">
                                <div class="method-title">Ambil Sendiri</div>
                                <div class="method-desc">Ambil pesanan langsung ke toko, gratis ongkos kirim</div>
                            </div>
                            <div class="method-radio"><div class="method-radio-dot"></div></div>
                        </div>
                        <div id="pickupInfo" style="display:none" class="pickup-info">
                            📍 <div>
                                <strong><?= htmlspecialchars($umkm->nama_toko ?? 'Toko') ?></strong><br>
                                <?= htmlspecialchars($umkm->alamat ?? 'Alamat toko') ?>
                                <?php if ($umkm->jam_buka ?? null): ?>
                                <br><?= htmlspecialchars($umkm->jam_buka) ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Delivery -->
                        <div class="method-card" id="cardDelivery" onclick="setMetode('delivery')">
                            <div class="method-icon delivery-icon">🚚</div>
                            <div class="method-info">
                                <div class="method-title">Diantar ke Alamat</div>
                                <div class="method-desc">Pesanan dikirim ke alamat yang kamu tentukan</div>
                            </div>
                            <div class="method-radio"><div class="method-radio-dot"></div></div>
                        </div>
                        <div id="addrForm" style="display:none" class="addr-form">
                            <div class="form-group">
                                <label>Nama Penerima</label>
                                <input type="text" name="nama_pemesan" placeholder="Nama lengkap penerima"
                                       value="<?= htmlspecialchars($this->session->userdata('nama') ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label>No. WhatsApp</label>
                                <input type="tel" name="no_wa_pemesan" placeholder="08xxxxxxxxxx">
                            </div>
                            <div class="form-group">
                                <label>Alamat Lengkap</label>
                                <textarea name="alamat_pengiriman" rows="3"
                                          placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..."></textarea>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="metode_pengiriman" id="metodePengiriman" value="">
                </div>

                <!-- Catatan -->
                <div class="co-card">
                    <div class="co-card-title">📝 Catatan (Opsional)</div>
                    <div class="form-group" style="margin-bottom:0">
                        <textarea name="catatan" rows="2" placeholder="Contoh: tolong dikemas rapi, jangan dilipat..."></textarea>
                    </div>
                </div>

            </div><!-- /kiri -->

            <!-- Kanan: Summary -->
            <div>
                <div class="co-card" style="padding:0;overflow:hidden;background:transparent;box-shadow:none;border:none">

                    <!-- Produk List -->
                    <div class="co-card" style="margin-bottom:16px">
                        <div class="co-card-title">📦 Produk (<?= count($items) ?> item)</div>
                        <?php foreach ($items as $item): ?>
                        <div class="produk-list-item">
                            <?php if ($item['produk']->foto): ?>
                            <img src="<?= base_url('uploads/produk/'.$item['produk']->foto) ?>"
                                 class="produk-thumb" alt="">
                            <?php else: ?>
                            <div class="produk-thumb-placeholder">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <?php endif; ?>
                            <div>
                                <div class="produk-list-nama"><?= htmlspecialchars($item['produk']->nama) ?></div>
                                <div class="produk-list-sub">
                                    Rp <?= number_format($item['produk']->harga, 0, ',', '.') ?> × <?= $item['qty'] ?>
                                </div>
                            </div>
                            <div class="produk-list-harga">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Total -->
                    <div class="summary-card">
                        <div class="summary-label">Ringkasan Pembayaran</div>
                        <div class="summary-item">
                            <span>Total Produk</span>
                            <span>Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
                        </div>
                        <div class="summary-item">
                            <span>Ongkos Kirim</span>
                            <span id="ongkirLabel">—</span>
                        </div>
                        <hr class="summary-divider">
                        <div class="summary-total">
                            <span>Total</span>
                            <span>Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
                        </div>
                        <button type="submit" class="btn-checkout" id="btnCheckout" disabled>
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Pesan Sekarang
                        </button>
                        <p style="text-align:center;font-size:.75rem;opacity:.5;margin-top:10px">
                            Dengan memesan, kamu menyetujui syarat & ketentuan
                        </p>
                    </div>

                </div>
            </div><!-- /kanan -->

        </div><!-- /checkout-grid -->
    </form>
</div>

<script>
function setMetode(val) {
    var cardPickup   = document.getElementById('cardPickup');
    var cardDelivery = document.getElementById('cardDelivery');
    var pickupInfo   = document.getElementById('pickupInfo');
    var addrForm     = document.getElementById('addrForm');
    var ongkir       = document.getElementById('ongkirLabel');
    var btn          = document.getElementById('btnCheckout');

    document.getElementById('metodePengiriman').value = val;

    cardPickup.className   = 'method-card' + (val === 'pickup'   ? ' active-pickup'   : '');
    cardDelivery.className = 'method-card' + (val === 'delivery' ? ' active-delivery' : '');

    pickupInfo.style.display = val === 'pickup'   ? 'flex'  : 'none';
    addrForm.style.display   = val === 'delivery' ? 'block' : 'none';

    ongkir.textContent = val === 'pickup' ? 'Gratis (Ambil Sendiri)' : 'Ditentukan penjual';
    btn.disabled = false;
}
</script>
