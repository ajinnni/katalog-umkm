<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.sukses-wrap { max-width: 600px; margin: 0 auto; padding: 40px 16px 80px; text-align: center; }
.sukses-icon {
    width: 80px; height: 80px; border-radius: 50%;
    background: #EBF3EA; margin: 0 auto 20px;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.2rem;
    animation: popIn .4s cubic-bezier(.17,.67,.38,1.3);
}
@keyframes popIn {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.sukses-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.9rem; color: #2c1a0e; margin-bottom: 6px;
}
.sukses-title em { color: #4A6741; font-style: italic; }
.sukses-sub { color: #7a6652; font-size: .9rem; margin-bottom: 32px; }
.kode-box {
    background: #2c1a0e; color: #f0c070;
    border-radius: 14px; padding: 16px 24px;
    font-size: 1.1rem; font-weight: 800;
    letter-spacing: .08em; margin-bottom: 28px;
    font-family: 'Courier New', monospace;
}
.detail-card {
    background: #fff; border-radius: 18px;
    padding: 24px; box-shadow: 0 4px 20px rgba(74,55,40,.1);
    border: 1.5px solid #e8ddd0; text-align: left; margin-bottom: 20px;
}
.detail-row { display: flex; justify-content: space-between; font-size: .88rem; padding: 7px 0; border-bottom: 1px solid #f0e8dc; }
.detail-row:last-child { border-bottom: none; }
.detail-row span:first-child { color: #7a6652; }
.detail-row span:last-child { font-weight: 600; color: #2c1a0e; text-align: right; max-width: 60%; }
.badge-pickup { background: #EBF3EA; color: #2E5C28; padding: 3px 10px; border-radius: 20px; font-size: .78rem; font-weight: 700; }
.badge-delivery { background: #EAF1F8; color: #1E4F7A; padding: 3px 10px; border-radius: 20px; font-size: .78rem; font-weight: 700; }
.btn-riwayat {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 13px 28px; background: #8B6F47; color: white;
    border-radius: 12px; font-weight: 700; font-size: .9rem;
    text-decoration: none; transition: all .2s; margin-right: 10px;
}
.btn-riwayat:hover { background: #6d5336; transform: translateY(-1px); color: white; text-decoration: none; }
.btn-outline-co {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 13px 28px; background: transparent; color: #8B6F47;
    border-radius: 12px; font-weight: 700; font-size: .9rem;
    text-decoration: none; transition: all .2s;
    border: 2px solid #8B6F47;
}
.btn-outline-co:hover { background: #f8f4ee; color: #6d5336; text-decoration: none; }
</style>

<div class="sukses-wrap">
    <div class="sukses-icon">✅</div>
    <h1 class="sukses-title">Pesanan <em>Berhasil!</em></h1>
    <p class="sukses-sub">Terima kasih! Pesananmu sudah kami terima dan akan segera diproses.</p>

    <div class="kode-box">📋 <?= htmlspecialchars($pesanan->kode_pesanan) ?></div>

    <div class="detail-card">
        <div class="detail-row">
            <span>Toko</span>
            <span><?= htmlspecialchars($pesanan->nama_toko ?? '-') ?></span>
        </div>
        <div class="detail-row">
            <span>Total Pembayaran</span>
            <span>Rp <?= number_format($pesanan->total_harga, 0, ',', '.') ?></span>
        </div>
        <div class="detail-row">
            <span>Metode Pengiriman</span>
            <span>
                <?php if ($pesanan->metode_pengiriman === 'pickup'): ?>
                    <span class="badge-pickup">🏪 Ambil Sendiri</span>
                <?php else: ?>
                    <span class="badge-delivery">🚚 Diantar ke Alamat</span>
                <?php endif; ?>
            </span>
        </div>
        <?php if ($pesanan->metode_pengiriman === 'delivery' && $pesanan->alamat_pengiriman): ?>
        <div class="detail-row">
            <span>Alamat</span>
            <span><?= nl2br(htmlspecialchars($pesanan->alamat_pengiriman)) ?></span>
        </div>
        <?php endif; ?>
        <div class="detail-row">
            <span>Status</span>
            <span>⏳ Menunggu konfirmasi penjual</span>
        </div>
        <?php if ($pesanan->no_wa_toko ?? null): ?>
        <div class="detail-row">
            <span>Hubungi Toko</span>
            <span>
                <a href="https://wa.me/<?= preg_replace('/^0/', '62', $pesanan->no_wa_toko) ?>?text=Halo, saya ingin konfirmasi pesanan <?= urlencode($pesanan->kode_pesanan) ?>"
                   target="_blank" style="color:#4A6741;font-weight:700;">
                    💬 WhatsApp
                </a>
            </span>
        </div>
        <?php endif; ?>
    </div>

    <div>
        <a href="<?= site_url('order/riwayat') ?>" class="btn-riwayat">📋 Riwayat Pesanan</a>
        <a href="<?= site_url('user') ?>" class="btn-outline-co">🛍️ Belanja Lagi</a>
    </div>
</div>
