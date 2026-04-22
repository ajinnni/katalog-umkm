<!-- RIWAYAT PEMBELIAN -->
<style>
.riwayat-section { max-width: 900px; margin: 0 auto; padding: 24px 16px 60px; }
.page-heading { font-size: 22px; font-weight: 700; color: #1a1a2e; margin-bottom: 20px; }
.page-heading span { color: #4e73df; }
.empty-riwayat { text-align: center; padding: 60px 20px; color: #aaa; }
.empty-riwayat svg { display: block; margin: 0 auto 16px; opacity: .4; }
.empty-riwayat p { font-size: 15px; margin-bottom: 16px; }
.card-pesanan { background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.07); margin-bottom: 16px; overflow: hidden; border: 1px solid #f0f0f0; }
.card-pesanan-header { display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; background: #f8f9fc; border-bottom: 1px solid #eee; flex-wrap: wrap; gap: 8px; }
.kode-pesanan { font-weight: 700; font-size: 14px; color: #333; font-family: monospace; }
.tgl-pesanan { font-size: 12px; color: #999; }
.badge-status { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.badge-pending      { background: #fff3cd; color: #856404; }
.badge-dikonfirmasi { background: #cff4fc; color: #0c5460; }
.badge-diproses     { background: #cce5ff; color: #004085; }
.badge-dikirim      { background: #e2e3e5; color: #383d41; }
.badge-selesai      { background: #d4edda; color: #155724; }
.badge-dibatalkan   { background: #f8d7da; color: #721c24; }
.card-pesanan-body { padding: 14px 18px; }
.pesanan-toko { font-size: 13px; color: #666; margin-bottom: 10px; }
.pesanan-toko strong { color: #4e73df; }
.detail-item { display: flex; justify-content: space-between; font-size: 13px; padding: 4px 0; border-bottom: 1px dashed #f0f0f0; color: #555; }
.detail-item:last-child { border-bottom: none; }
.card-pesanan-footer { display: flex; justify-content: space-between; align-items: center; padding: 12px 18px; background: #fafafa; border-top: 1px solid #eee; flex-wrap: wrap; gap: 8px; }
.total-label { font-size: 13px; color: #888; }
.total-val { font-size: 16px; font-weight: 700; color: #1cc88a; }
.btn-lacak { display: inline-block; padding: 7px 16px; background: #4e73df; color: #fff; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; transition: .2s; }
.btn-lacak:hover { background: #2e59d9; color: #fff; }
.status-timeline { display: flex; gap: 0; margin: 14px 0 4px; overflow-x: auto; }
.step { flex: 1; min-width: 80px; text-align: center; position: relative; }
.step::before { content: ''; position: absolute; top: 14px; left: 50%; right: -50%; height: 2px; background: #e0e0e0; z-index: 0; }
.step:last-child::before { display: none; }
.step-dot { width: 28px; height: 28px; border-radius: 50%; background: #e0e0e0; color: #aaa; display: flex; align-items: center; justify-content: center; margin: 0 auto 6px; font-size: 12px; position: relative; z-index: 1; }
.step-dot.done { background: #1cc88a; color: #fff; }
.step-dot.active { background: #4e73df; color: #fff; box-shadow: 0 0 0 3px rgba(78,115,223,.25); }
.step-label { font-size: 11px; color: #aaa; }
.step-label.done, .step-label.active { color: #333; font-weight: 600; }
</style>

<div class="riwayat-section">
    <h2 class="page-heading">Riwayat <span>Pembelian</span></h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success" style="padding:10px 14px;border-radius:8px;background:#d4edda;color:#155724;margin-bottom:16px;font-size:13px;">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($pesanan)): ?>
        <div class="empty-riwayat">
            <svg width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p>Belum ada riwayat pembelian.</p>
            <a href="<?= site_url('index.php/user') ?>" class="btn-primary" style="display:inline-block;padding:10px 24px;background:#4e73df;color:#fff;border-radius:10px;text-decoration:none;font-weight:600;">Mulai Belanja</a>
        </div>
    <?php else: ?>

        <?php
        $steps_all = ['pending','dikonfirmasi','diproses','dikirim','selesai'];
        $step_icon = ['⏳','✓','⚙','🚚','✔'];
        $step_label = ['Pending','Dikonfirmasi','Diproses','Dikirim','Selesai'];
        ?>

        <?php foreach ($pesanan as $p): ?>
        <div class="card-pesanan">
            <div class="card-pesanan-header">
                <div>
                    <div class="kode-pesanan"><?= htmlspecialchars($p->kode_pesanan) ?></div>
                    <div class="tgl-pesanan"><?= date('d M Y, H:i', strtotime($p->created_at)) ?></div>
                </div>
                <div>
                    <?php
                    $badge_map = [
                        'pending'      => 'pending',
                        'dikonfirmasi' => 'dikonfirmasi',
                        'diproses'     => 'diproses',
                        'dikirim'      => 'dikirim',
                        'selesai'      => 'selesai',
                        'dibatalkan'   => 'dibatalkan',
                    ];
                    $b = $badge_map[$p->status] ?? 'pending';
                    ?>
                    <span class="badge-status badge-<?= $b ?>"><?= ucfirst($p->status) ?></span>
                </div>
            </div>

            <div class="card-pesanan-body">
                <div class="pesanan-toko">
                    <i class="fas fa-store"></i> Toko: <strong><?= htmlspecialchars($p->nama_toko ?? '-') ?></strong>
                </div>

                <!-- Timeline Status -->
                <?php if ($p->status !== 'dibatalkan'): ?>
                <div class="status-timeline">
                    <?php
                    $current_idx = array_search($p->status, $steps_all);
                    foreach ($steps_all as $si => $s):
                        $is_done   = $si < $current_idx;
                        $is_active = $si === $current_idx;
                    ?>
                    <div class="step">
                        <div class="step-dot <?= $is_done ? 'done' : ($is_active ? 'active' : '') ?>">
                            <?= $step_icon[$si] ?>
                        </div>
                        <div class="step-label <?= $is_done ? 'done' : ($is_active ? 'active' : '') ?>">
                            <?= $step_label[$si] ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Detail Item -->
                <?php if (!empty($p->details)): ?>
                    <?php foreach ($p->details as $d): ?>
                    <div class="detail-item">
                        <span><?= htmlspecialchars($d->nama_produk) ?> × <?= $d->qty ?></span>
                        <span>Rp <?= number_format($d->subtotal, 0, ',', '.') ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="card-pesanan-footer">
                <div>
                    <div class="total-label">Total Pembayaran</div>
                    <div class="total-val">Rp <?= number_format($p->total_harga, 0, ',', '.') ?></div>
                </div>
                <?php if (!empty($p->no_wa_toko)): ?>
                <a href="https://wa.me/<?= preg_replace('/\D/', '', $p->no_wa_toko) ?>?text=<?= urlencode('Halo, saya ingin menanyakan pesanan saya dengan kode: ' . $p->kode_pesanan) ?>"
                   target="_blank" class="btn-lacak">
                    <i class="fab fa-whatsapp"></i> Hubungi Toko
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

    <?php endif; ?>
</div>