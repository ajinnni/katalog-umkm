<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- ============================================================
     FILE: views/user/order/riwayat.php
     Riwayat Pesanan — tema sesuai landing page
     ============================================================ -->

<style>
.riwayat-wrap { max-width: 760px; margin: 0 auto; padding: 32px 16px 80px; }
.riwayat-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 2rem; color: #2c1a0e; margin-bottom: 4px;
}
.riwayat-title em { color: #8B6F47; font-style: italic; }
.riwayat-sub { color: #7a6652; font-size: .9rem; margin-bottom: 32px; }

.pesanan-card {
    background: #fff; border-radius: 18px; padding: 22px 24px;
    box-shadow: 0 4px 16px rgba(74,55,40,.09);
    border: 1.5px solid #e8ddd0; margin-bottom: 16px;
    transition: transform .18s, box-shadow .18s;
}
.pesanan-card:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(74,55,40,.14); }

.pcard-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px; }
.pcard-kode { font-size: .78rem; font-weight: 700; letter-spacing: .08em; color: #8B6F47; }
.pcard-toko { font-size: .9rem; font-weight: 700; color: #2c1a0e; margin-top: 2px; }
.pcard-tanggal { font-size: .78rem; color: #a08060; margin-top: 2px; }

.status-pill {
    padding: 5px 14px; border-radius: 20px;
    font-size: .75rem; font-weight: 700; white-space: nowrap;
}
.s-pending      { background: #FEF3C7; color: #92400E; }
.s-dikonfirmasi { background: #DBEAFE; color: #1E40AF; }
.s-diproses     { background: #EDE9FE; color: #5B21B6; }
.s-dikirim      { background: #D1FAE5; color: #065F46; }
.s-selesai      { background: #F3F4F6; color: #374151; }
.s-dibatalkan   { background: #FEE2E2; color: #991B1B; }

.pcard-divider { border: none; border-top: 1px solid #f0e8dc; margin: 14px 0; }

.pcard-meta { display: flex; gap: 20px; flex-wrap: wrap; }
.pcard-meta-item { font-size: .82rem; color: #7a6652; }
.pcard-meta-item strong { color: #2c1a0e; display: block; }

.badge-shipmethod {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px; font-size: .75rem; font-weight: 700;
}
.bm-pickup   { background: #EBF3EA; color: #2E5C28; }
.bm-delivery { background: #EAF1F8; color: #1E4F7A; }

.pcard-bottom { display: flex; justify-content: space-between; align-items: center; margin-top: 14px; flex-wrap: wrap; gap: 8px; }
.pcard-total { font-size: .9rem; color: #2c1a0e; }
.pcard-total strong { font-size: 1.05rem; color: #8B6F47; }

.btn-detail-link {
    padding: 8px 20px; background: #8B6F47; color: white;
    border-radius: 10px; font-weight: 700; font-size: .82rem;
    text-decoration: none; transition: all .18s;
}
.btn-detail-link:hover { background: #6d5336; color: white; text-decoration: none; }

.empty-riwayat { text-align: center; padding: 60px 0; color: #a08060; }
.empty-riwayat svg { opacity: .4; margin-bottom: 16px; }
.empty-riwayat p { font-size: .95rem; margin-bottom: 16px; }
.btn-belanja {
    padding: 12px 28px; background: #8B6F47; color: white;
    border-radius: 12px; font-weight: 700; font-size: .9rem;
    text-decoration: none; transition: all .2s; display: inline-block;
}
.btn-belanja:hover { background: #6d5336; color: white; text-decoration: none; }

/* Kurir badge */
.kurir-info { font-size: .78rem; color: #3B6FA0; font-weight: 600; margin-top: 4px; }
</style>

<div class="riwayat-wrap">
    <h1 class="riwayat-title">Riwayat <em>Pesanan</em></h1>
    <p class="riwayat-sub">Pantau status semua pesananmu di sini</p>

    <?php if (empty($pesanan)): ?>
    <div class="empty-riwayat">
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p>Belum ada pesanan</p>
        <a href="<?= site_url('user') ?>" class="btn-belanja">🛍️ Mulai Belanja</a>
    </div>

    <?php else: ?>
    <?php foreach ($pesanan as $p): ?>
    <div class="pesanan-card">
        <div class="pcard-top">
            <div>
                <div class="pcard-kode"><?= htmlspecialchars($p->kode_pesanan) ?></div>
                <div class="pcard-toko">🏬 <?= htmlspecialchars($p->nama_toko ?? 'Toko') ?></div>
                <div class="pcard-tanggal"><?= date('d M Y, H:i', strtotime($p->created_at)) ?></div>
            </div>
            <?php
            $sc = [
                'pending'=>'s-pending','dikonfirmasi'=>'s-dikonfirmasi',
                'diproses'=>'s-diproses','dikirim'=>'s-dikirim',
                'selesai'=>'s-selesai','dibatalkan'=>'s-dibatalkan'
            ];
            $sc_class = $sc[$p->status] ?? 's-pending';
            ?>
            <span class="status-pill <?= $sc_class ?>"><?= ucfirst($p->status) ?></span>
        </div>

        <hr class="pcard-divider">

        <div class="pcard-meta">
            <div class="pcard-meta-item">
                <strong><?= count($p->details) ?> produk</strong>
                Item
            </div>
            <div class="pcard-meta-item">
                <strong>
                    <span class="badge-shipmethod <?= $p->metode_pengiriman === 'pickup' ? 'bm-pickup' : 'bm-delivery' ?>">
                        <?= $p->metode_pengiriman === 'pickup' ? '🏪 Ambil Sendiri' : '🚚 Diantar' ?>
                    </span>
                </strong>
                Pengiriman
                <?php if ($p->metode_pengiriman === 'delivery' && $p->metode_kirim_umkm): ?>
                <div class="kurir-info">
                    <?php if ($p->metode_kirim_umkm === 'jasa' && $p->jasa_kurir): ?>
                        📦 <?= htmlspecialchars($p->jasa_kurir) ?>
                        <?= $p->no_resi ? '· Resi: '.$p->no_resi : '' ?>
                    <?php elseif ($p->metode_kirim_umkm === 'sendiri'): ?>
                        🛵 Antar Sendiri <?= $p->estimasi_pengiriman ? '· '.$p->estimasi_pengiriman : '' ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="pcard-bottom">
            <div class="pcard-total">
                Total: <strong>Rp <?= number_format($p->total_harga, 0, ',', '.') ?></strong>
            </div>
            <a href="<?= site_url('order/detail/'.$p->id) ?>" class="btn-detail-link">Lihat Detail →</a>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

</div>
