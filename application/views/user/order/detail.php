<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- ============================================================
     FILE: views/user/order/detail.php
     Detail Pesanan User — tema sesuai landing page
     ============================================================ -->

<style>
.det-wrap { max-width: 700px; margin: 0 auto; padding: 32px 16px 80px; }
.det-back { display: inline-flex; align-items: center; gap: 6px; color: #8B6F47; font-size: .85rem; font-weight: 600; text-decoration: none; margin-bottom: 20px; }
.det-back:hover { color: #6d5336; text-decoration: none; }
.det-title { font-family: 'Playfair Display', Georgia, serif; font-size: 1.7rem; color: #2c1a0e; margin-bottom: 4px; }
.det-kode { font-size: .78rem; font-weight: 700; letter-spacing: .1em; color: #8B6F47; margin-bottom: 24px; }

.det-card {
    background: #fff; border-radius: 18px; padding: 24px;
    box-shadow: 0 4px 16px rgba(74,55,40,.09);
    border: 1.5px solid #e8ddd0; margin-bottom: 18px;
}
.det-card-title { font-size: .75rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #8B6F47; margin-bottom: 14px; }

.info-row { display: flex; justify-content: space-between; font-size: .87rem; padding: 7px 0; border-bottom: 1px solid #f5ede2; }
.info-row:last-child { border-bottom: none; }
.info-row span:first-child { color: #7a6652; flex-shrink: 0; margin-right: 12px; }
.info-row span:last-child { font-weight: 600; color: #2c1a0e; text-align: right; }

.status-pill { padding: 4px 14px; border-radius: 20px; font-size: .78rem; font-weight: 700; }
.s-pending      { background: #FEF3C7; color: #92400E; }
.s-dikonfirmasi { background: #DBEAFE; color: #1E40AF; }
.s-diproses     { background: #EDE9FE; color: #5B21B6; }
.s-dikirim      { background: #D1FAE5; color: #065F46; }
.s-selesai      { background: #F3F4F6; color: #374151; }
.s-dibatalkan   { background: #FEE2E2; color: #991B1B; }

.ship-card {
    border-radius: 14px; padding: 16px;
    display: flex; gap: 14px; align-items: flex-start; margin-bottom: 4px;
}
.ship-pickup   { background: #EBF3EA; border: 1.5px solid #A8D5A2; }
.ship-delivery { background: #EAF1F8; border: 1.5px solid #9EC5E8; }
.ship-icon { font-size: 1.6rem; flex-shrink: 0; }
.ship-info-title { font-weight: 700; color: #2c1a0e; font-size: .92rem; }
.ship-info-sub { font-size: .8rem; color: #7a6652; margin-top: 3px; }
.ship-detail-row { font-size: .82rem; margin-top: 8px; color: #2c1a0e; }
.ship-detail-row span { color: #7a6652; margin-right: 4px; }

.produk-list-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0; border-bottom: 1px solid #f5ede2;
}
.produk-list-item:last-child { border-bottom: none; }
.produk-thumb { width: 50px; height: 50px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
.produk-thumb-ph {
    width: 50px; height: 50px; border-radius: 10px;
    background: #f0e8dc; display: flex; align-items: center; justify-content: center;
    color: #c8b9a8; flex-shrink: 0;
}
.pli-nama { font-weight: 600; font-size: .87rem; color: #2c1a0e; }
.pli-sub  { font-size: .78rem; color: #7a6652; margin-top: 2px; }
.pli-harga { margin-left: auto; font-weight: 700; font-size: .87rem; color: #2c1a0e; text-align: right; white-space: nowrap; }

.total-box { background: #2c1a0e; color: white; border-radius: 14px; padding: 16px 20px; }
.total-box-row { display: flex; justify-content: space-between; font-size: .87rem; padding: 5px 0; }
.total-box-row span:first-child { opacity: .65; }
.total-box-divider { border: none; border-top: 1px solid rgba(255,255,255,.15); margin: 10px 0; }
.total-box-final { display: flex; justify-content: space-between; font-size: 1rem; font-weight: 800; }
.total-box-final span:last-child { color: #f0c070; font-size: 1.1rem; }

.wa-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 22px; background: #25D366; color: white;
    border-radius: 12px; font-weight: 700; font-size: .85rem;
    text-decoration: none; transition: all .18s; margin-top: 12px;
}
.wa-btn:hover { background: #1ebe5c; color: white; text-decoration: none; }

.resi-badge {
    display: inline-block;
    background: #EAF1F8; color: #1E4F7A;
    padding: 4px 12px; border-radius: 20px;
    font-weight: 700; font-size: .8rem;
    font-family: 'Courier New', monospace;
}
</style>

<div class="det-wrap">
    <a href="<?= site_url('order/riwayat') ?>" class="det-back">← Kembali ke Riwayat</a>

    <h1 class="det-title">Detail Pesanan</h1>
    <div class="det-kode">📋 <?= htmlspecialchars($pesanan->kode_pesanan) ?></div>

    <!-- Status -->
    <?php
    $sc = ['pending'=>'s-pending','dikonfirmasi'=>'s-dikonfirmasi','diproses'=>'s-diproses',
           'dikirim'=>'s-dikirim','selesai'=>'s-selesai','dibatalkan'=>'s-dibatalkan'];
    $sc_class = $sc[$pesanan->status] ?? 's-pending';
    ?>
    <div class="det-card">
        <div class="det-card-title">Info Pesanan</div>
        <div class="info-row">
            <span>Toko</span>
            <span><?= htmlspecialchars($pesanan->nama_toko ?? '-') ?></span>
        </div>
        <div class="info-row">
            <span>Tanggal</span>
            <span><?= date('d M Y, H:i', strtotime($pesanan->created_at)) ?></span>
        </div>
        <div class="info-row">
            <span>Status</span>
            <span><span class="status-pill <?= $sc_class ?>"><?= ucfirst($pesanan->status) ?></span></span>
        </div>
        <?php if ($pesanan->catatan): ?>
        <div class="info-row">
            <span>Catatan</span>
            <span><?= htmlspecialchars($pesanan->catatan) ?></span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Info Pengiriman -->
    <div class="det-card">
        <div class="det-card-title">Info Pengiriman</div>

        <?php if ($pesanan->metode_pengiriman === 'pickup'): ?>
        <div class="ship-card ship-pickup">
            <div class="ship-icon">🏪</div>
            <div>
                <div class="ship-info-title">Ambil Sendiri di Toko</div>
                <div class="ship-info-sub"><?= htmlspecialchars($pesanan->alamat_toko ?? 'Hubungi toko untuk info lokasi') ?></div>
                <?php if ($pesanan->status === 'dikirim'): ?>
                <div class="ship-detail-row" style="color:#2E5C28;font-weight:700;margin-top:8px">✅ Pesanan siap diambil!</div>
                <?php endif; ?>
            </div>
        </div>

        <?php else: ?>
        <div class="info-row">
            <span>Nama Penerima</span><span><?= htmlspecialchars($pesanan->nama_pemesan) ?></span>
        </div>
        <div class="info-row">
            <span>No. WA</span><span><?= htmlspecialchars($pesanan->no_wa_pemesan) ?></span>
        </div>
        <div class="info-row">
            <span>Alamat</span><span><?= nl2br(htmlspecialchars($pesanan->alamat_pengiriman)) ?></span>
        </div>

        <?php if ($pesanan->metode_kirim_umkm): ?>
        <div style="margin-top: 12px">
            <?php if ($pesanan->metode_kirim_umkm === 'jasa'): ?>
            <div class="ship-card ship-delivery">
                <div class="ship-icon">📦</div>
                <div>
                    <div class="ship-info-title">Dikirim via <?= htmlspecialchars($pesanan->jasa_kurir ?? 'Kurir') ?></div>
                    <?php if ($pesanan->no_resi): ?>
                    <div class="ship-detail-row">
                        <span>No. Resi:</span>
                        <span class="resi-badge"><?= htmlspecialchars($pesanan->no_resi) ?></span>
                    </div>
                    <?php else: ?>
                    <div class="ship-detail-row" style="color:#7a6652">Nomor resi belum tersedia</div>
                    <?php endif; ?>
                    <?php if ($pesanan->estimasi_pengiriman): ?>
                    <div class="ship-detail-row"><span>Estimasi:</span><?= htmlspecialchars($pesanan->estimasi_pengiriman) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="ship-card ship-delivery">
                <div class="ship-icon">🛵</div>
                <div>
                    <div class="ship-info-title">Diantar Langsung</div>
                    <?php if ($pesanan->nama_pengantar): ?>
                    <div class="ship-detail-row"><span>Pengantar:</span><?= htmlspecialchars($pesanan->nama_pengantar) ?></div>
                    <div class="ship-detail-row"><span>No. HP:</span><?= htmlspecialchars($pesanan->no_hp_pengantar) ?></div>
                    <?php endif; ?>
                    <?php if ($pesanan->estimasi_pengiriman): ?>
                    <div class="ship-detail-row"><span>Estimasi:</span><?= htmlspecialchars($pesanan->estimasi_pengiriman) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div style="margin-top:12px;padding:12px;background:#fef9f5;border-radius:10px;border:1.5px dashed #e8ddd0;font-size:.83rem;color:#a08060;text-align:center">
            ⏳ Menunggu penjual mengatur pengiriman...
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <!-- Hubungi Toko -->
        <?php if ($pesanan->no_wa_toko ?? null): ?>
        <a href="https://wa.me/<?= preg_replace('/^0/', '62', $pesanan->no_wa_toko) ?>?text=Halo, saya ingin tanya pesanan <?= urlencode($pesanan->kode_pesanan) ?>"
           target="_blank" class="wa-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Hubungi Toko
        </a>
        <?php endif; ?>
    </div>

    <!-- Produk -->
    <div class="det-card">
        <div class="det-card-title">Produk Dipesan</div>
        <?php foreach ($pesanan->details as $d): ?>
        <div class="produk-list-item">
            <?php if ($d->foto): ?>
            <img src="<?= base_url('uploads/produk/'.$d->foto) ?>" class="produk-thumb" alt="">
            <?php else: ?>
            <div class="produk-thumb-ph">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <?php endif; ?>
            <div>
                <div class="pli-nama"><?= htmlspecialchars($d->nama_produk) ?></div>
                <div class="pli-sub">Rp <?= number_format($d->harga, 0, ',', '.') ?> × <?= $d->qty ?></div>
            </div>
            <div class="pli-harga">Rp <?= number_format($d->subtotal, 0, ',', '.') ?></div>
        </div>
        <?php endforeach; ?>

        <div class="total-box" style="margin-top:16px">
            <div class="total-box-divider"></div>
            <div class="total-box-final">
                <span>Total Pembayaran</span>
                <span>Rp <?= number_format($pesanan->total_harga, 0, ',', '.') ?></span>
            </div>
        </div>
    </div>

</div>
