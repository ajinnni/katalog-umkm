<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- SB Admin 2 — Kelola Pesanan -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Pesanan</h1>
    </div>

    <!-- Flashdata -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>

    <!-- Stat Cards -->
    <div class="row mb-4">
        <?php
        $stat_items = [
            ['label' => 'Pending',      'key' => 'pending',      'color' => 'warning', 'icon' => 'clock'],
            ['label' => 'Dikonfirmasi', 'key' => 'dikonfirmasi', 'color' => 'info',    'icon' => 'check-circle'],
            ['label' => 'Diproses',     'key' => 'diproses',     'color' => 'primary', 'icon' => 'cog'],
            ['label' => 'Dikirim',      'key' => 'dikirim',      'color' => 'success', 'icon' => 'truck'],
            ['label' => 'Selesai',      'key' => 'selesai',      'color' => 'secondary','icon' => 'check'],
        ];
        foreach ($stat_items as $s):
        ?>
        <div class="col-xl col-md-4 mb-3">
            <div class="card border-left-<?= $s['color'] ?> shadow h-100 py-2">
                <div class="card-body py-2">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-<?= $s['color'] ?> text-uppercase mb-1"><?= $s['label'] ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats[$s['key']] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-<?= $s['icon'] ?> fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Filter Tabs -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link <?= !$status_filter ? 'active' : '' ?>" href="<?= site_url('pesanan') ?>">Semua</a>
                </li>
                <?php foreach (['pending','dikonfirmasi','diproses','dikirim','selesai','dibatalkan'] as $s): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $status_filter === $s ? 'active' : '' ?>" href="<?= site_url('pesanan?status='.$s) ?>">
                        <?= ucfirst($s) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tblPesanan" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Pembeli</th>
                            <th>Total</th>
                            <th>Pengiriman</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pesanan)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Belum ada pesanan
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($pesanan as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><strong><?= htmlspecialchars($p->kode_pesanan) ?></strong></td>
                            <td>
                                <?= htmlspecialchars($p->nama_pemesan) ?><br>
                                <small class="text-muted"><?= htmlspecialchars($p->no_wa_pemesan) ?></small>
                            </td>
                            <td>Rp <?= number_format($p->total_harga, 0, ',', '.') ?></td>
                            <td>
                                <?php if ($p->metode_pengiriman === 'pickup'): ?>
                                    <span class="badge badge-success"><i class="fas fa-store mr-1"></i>Ambil Sendiri</span>
                                <?php else: ?>
                                    <span class="badge badge-info"><i class="fas fa-truck mr-1"></i>Diantar</span>
                                    <?php if ($p->metode_kirim_umkm === 'jasa' && $p->jasa_kurir): ?>
                                        <br><small class="text-muted"><?= htmlspecialchars($p->jasa_kurir) ?></small>
                                    <?php elseif ($p->metode_kirim_umkm === 'sendiri'): ?>
                                        <br><small class="text-muted">Antar Sendiri</small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $badge_map = [
                                    'pending'      => 'warning',
                                    'dikonfirmasi' => 'info',
                                    'diproses'     => 'primary',
                                    'dikirim'      => 'success',
                                    'selesai'      => 'secondary',
                                    'dibatalkan'   => 'danger',
                                ];
                                $bc = $badge_map[$p->status] ?? 'light';
                                ?>
                                <span class="badge badge-<?= $bc ?>"><?= ucfirst($p->status) ?></span>
                            </td>
                            <td><?= date('d M Y', strtotime($p->created_at)) ?></td>
                            <td>
                                <a href="<?= site_url('pesanan/detail/'.$p->id) ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- /card -->

</div>

<script>
$(document).ready(function() {
    $('#tblPesanan').DataTable({ order: [] });
});
</script>
