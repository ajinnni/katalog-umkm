<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Laporan - UMKM Catalog Admin</title>
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body id="page-top">
<div id="wrapper">

    
            

            <div class="container-fluid">

              

                <!-- ── RINGKASAN UTAMA ── -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pesanan</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800"><?= number_format($total_pesanan) ?></div>
                                        <div class="text-xs text-muted mt-1"><?= $pesanan_bulan_ini ?> bulan ini</div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-shopping-bag fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Omzet</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_omzet, 0, ',', '.') ?></div>
                                        <div class="text-xs text-muted mt-1">dari pesanan selesai</div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-rupiah-sign fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total UMKM Aktif</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800"><?= $total_umkm_aktif ?></div>
                                        <div class="text-xs text-muted mt-1"><?= $total_umkm_pending ?> menunggu aktivasi</div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-store fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Produk Aktif</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800"><?= $total_produk_aktif ?></div>
                                        <div class="text-xs text-muted mt-1">dari <?= $total_produk ?> total produk</div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-box fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── ROW 2: STATUS PESANAN + GRAFIK BULANAN ── -->
                <div class="row mb-4">

                    <!-- Status Pesanan Pie -->
                    <div class="col-xl-4 col-lg-5 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-circle-half-stroke mr-1"></i> Status Pesanan
                                </h6>
                            </div>
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <canvas id="chartStatus" style="max-height:220px;"></canvas>
                                <div class="mt-3 w-100">
                                    <?php
                                    $status_colors = [
                                        'pending'      => ['#f6c23e', 'warning'],
                                        'dikonfirmasi' => ['#36b9cc', 'info'],
                                        'diproses'     => ['#4e73df', 'primary'],
                                        'dikirim'      => ['#858796', 'secondary'],
                                        'selesai'      => ['#1cc88a', 'success'],
                                        'dibatalkan'   => ['#e74a3b', 'danger'],
                                    ];
                                    foreach ($status_pesanan as $s):
                                        $color = $status_colors[$s->status] ?? ['#ccc', 'secondary'];
                                    ?>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small">
                                            <i class="fas fa-circle text-<?= $color[1] ?> mr-1" style="font-size:10px"></i>
                                            <?= ucfirst($s->status) ?>
                                        </span>
                                        <span class="small font-weight-bold"><?= $s->jumlah ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik Pesanan Bulanan -->
                    <div class="col-xl-8 col-lg-7 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-bar mr-1"></i> Pesanan per Bulan (<?= date('Y') ?>)
                                </h6>
                            </div>
                            <div class="card-body">
                                <canvas id="chartBulanan" style="min-height:220px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── ROW 3: TOP UMKM + USER BARU ── -->
                <div class="row mb-4">

                    <!-- Top UMKM by Omzet -->
                    <div class="col-xl-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-trophy mr-1"></i> Top 5 UMKM by Omzet
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="40">#</th>
                                                <th>Nama Toko</th>
                                                <th>Pesanan</th>
                                                <th>Omzet</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (empty($top_umkm)): ?>
                                            <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($top_umkm as $i => $u): ?>
                                            <tr>
                                                <td>
                                                    <?php if ($i === 0): ?>
                                                        <i class="fas fa-trophy text-warning"></i>
                                                    <?php elseif ($i === 1): ?>
                                                        <i class="fas fa-trophy text-secondary"></i>
                                                    <?php elseif ($i === 2): ?>
                                                        <i class="fas fa-trophy" style="color:#cd7f32"></i>
                                                    <?php else: ?>
                                                        <span class="text-muted"><?= $i + 1 ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?= htmlspecialchars($u->nama_toko) ?></strong>
                                                    <br><small class="text-muted"><?= htmlspecialchars($u->nama_pemilik ?? '-') ?></small>
                                                </td>
                                                <td><span class="badge badge-primary"><?= $u->total_pesanan ?></span></td>
                                                <td class="font-weight-bold text-success">
                                                    Rp <?= number_format($u->total_omzet, 0, ',', '.') ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Produk Terlaris -->
                    <div class="col-xl-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-fire mr-1"></i> Top 5 Produk Terlaris
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="40">#</th>
                                                <th>Produk</th>
                                                <th>Toko</th>
                                                <th>Terjual</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (empty($top_produk)): ?>
                                            <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($top_produk as $i => $p): ?>
                                            <tr>
                                                <td class="text-muted"><?= $i + 1 ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($p->nama_produk) ?></strong>
                                                    <br><small class="text-success">Rp <?= number_format($p->harga_satuan, 0, ',', '.') ?></small>
                                                </td>
                                                <td><small class="text-muted"><?= htmlspecialchars($p->nama_toko ?? '-') ?></small></td>
                                                <td>
                                                    <span class="badge badge-success"><?= number_format($p->total_terjual) ?> pcs</span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── ROW 4: UMKM PENDING + RINGKASAN USER ── -->
                <div class="row mb-4">

                    <!-- UMKM Menunggu Aktivasi -->
                    <div class="col-xl-7 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-warning">
                                    <i class="fas fa-clock mr-1"></i> UMKM Menunggu Aktivasi
                                    <?php if ($total_umkm_pending > 0): ?>
                                        <span class="badge badge-warning ml-1"><?= $total_umkm_pending ?></span>
                                    <?php endif; ?>
                                </h6>
                                <a href="<?= base_url('index.php/admin/umkm') ?>" class="btn btn-sm btn-outline-warning">
                                    Kelola <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Nama Toko</th>
                                                <th>Pemilik</th>
                                                <th>Daftar</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (empty($umkm_pending)): ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-3">
                                                    <i class="fas fa-check-circle text-success mr-1"></i>
                                                    Tidak ada UMKM yang menunggu aktivasi
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($umkm_pending as $u): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($u->nama_toko) ?></strong></td>
                                                <td><?= htmlspecialchars($u->nama_pemilik ?? '-') ?></td>
                                                <td><small class="text-muted"><?= date('d/m/Y', strtotime($u->created_at)) ?></small></td>
                                                <td>
                                                    <a href="<?= base_url('index.php/admin/umkm/' . $u->id . '/toggle') ?>"
                                                       class="btn btn-success btn-sm"
                                                       onclick="return confirm('Aktifkan toko <?= htmlspecialchars($u->nama_toko) ?>?')">
                                                        <i class="fas fa-check"></i> Aktifkan
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan User -->
                    <div class="col-xl-5 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-users mr-1"></i> Ringkasan Pengguna
                                </h6>
                            </div>
                            <div class="card-body">
                                <!-- User -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small font-weight-bold">Pembeli (User)</span>
                                        <span class="small font-weight-bold"><?= $total_user ?></span>
                                    </div>
                                    <div class="progress" style="height:10px;">
                                        <div class="progress-bar bg-primary" style="width:<?= $total_user_pct ?>%"></div>
                                    </div>
                                </div>
                                <!-- UMKM -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small font-weight-bold">Pemilik UMKM</span>
                                        <span class="small font-weight-bold"><?= $total_umkm_user ?></span>
                                    </div>
                                    <div class="progress" style="height:10px;">
                                        <div class="progress-bar bg-success" style="width:<?= $total_umkm_pct ?>%"></div>
                                    </div>
                                </div>
                                <!-- Admin -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small font-weight-bold">Admin</span>
                                        <span class="small font-weight-bold"><?= $total_admin ?></span>
                                    </div>
                                    <div class="progress" style="height:10px;">
                                        <div class="progress-bar bg-warning" style="width:<?= $total_admin_pct ?>%"></div>
                                    </div>
                                </div>

                                <hr>
                                <div class="text-center">
                                    <div class="h4 font-weight-bold text-gray-800"><?= $total_semua_user ?></div>
                                    <div class="text-xs text-muted text-uppercase">Total Pengguna Terdaftar</div>
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-user-plus mr-1 text-success"></i>
                                        <?= $user_baru_bulan_ini ?> pengguna baru bulan ini
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── ROW 5: PESANAN TERBARU ── -->
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-list mr-1"></i> Pesanan Terbaru
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover dataTable" width="100%">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Kode</th>
                                                <th>Pemesan</th>
                                                <th>Toko</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (empty($pesanan_terbaru)): ?>
                                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pesanan</td></tr>
                                        <?php else: ?>
                                            <?php
                                            $badge_map = [
                                                'pending'      => 'warning',
                                                'dikonfirmasi' => 'info',
                                                'diproses'     => 'primary',
                                                'dikirim'      => 'secondary',
                                                'selesai'      => 'success',
                                                'dibatalkan'   => 'danger',
                                            ];
                                            ?>
                                            <?php foreach ($pesanan_terbaru as $i => $p): ?>
                                            <tr>
                                                <td><?= $i + 1 ?></td>
                                                <td><span class="badge badge-secondary"><?= $p->kode_pesanan ?></span></td>
                                                <td><?= htmlspecialchars($p->nama_pemesan) ?></td>
                                                <td><?= htmlspecialchars($p->nama_toko ?? '-') ?></td>
                                                <td class="font-weight-bold">Rp <?= number_format($p->total_harga, 0, ',', '.') ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $badge_map[$p->status] ?? 'secondary' ?>">
                                                        <?= ucfirst($p->status) ?>
                                                    </span>
                                                </td>
                                                <td><?= date('d/m/Y H:i', strtotime($p->created_at)) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /container-fluid -->
        </div><!-- /content -->
    </div><!-- /content-wrapper -->
</div><!-- /wrapper -->

<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>
<script>
// ── CHART STATUS PESANAN (PIE) ──
var ctxStatus = document.getElementById('chartStatus').getContext('2d');
new Chart(ctxStatus, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_map(fn($s) => ucfirst($s->status), $status_pesanan)) ?>,
        datasets: [{
            data: <?= json_encode(array_map(fn($s) => $s->jumlah, $status_pesanan)) ?>,
            backgroundColor: ['#f6c23e','#36b9cc','#4e73df','#858796','#1cc88a','#e74a3b'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        cutout: '65%',
    }
});

// ── CHART PESANAN BULANAN (BAR) ──
var ctxBulanan = document.getElementById('chartBulanan').getContext('2d');
new Chart(ctxBulanan, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_map(fn($b) => $b->bulan, $pesanan_per_bulan)) ?>,
        datasets: [
            {
                label: 'Jumlah Pesanan',
                data: <?= json_encode(array_map(fn($b) => $b->jumlah, $pesanan_per_bulan)) ?>,
                backgroundColor: 'rgba(78,115,223,0.8)',
                borderColor: '#4e73df',
                borderWidth: 1,
                yAxisID: 'y',
            },
            {
                label: 'Omzet (Rp)',
                data: <?= json_encode(array_map(fn($b) => $b->omzet, $pesanan_per_bulan)) ?>,
                type: 'line',
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28,200,138,0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        if (ctx.datasetIndex === 1) {
                            return 'Omzet: Rp ' + ctx.raw.toLocaleString('id-ID');
                        }
                        return 'Pesanan: ' + ctx.raw;
                    }
                }
            }
        },
        scales: {
            y:  { type: 'linear', position: 'left',  beginAtZero: true, title: { display: true, text: 'Jumlah Pesanan' } },
            y1: { type: 'linear', position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, title: { display: true, text: 'Omzet (Rp)' } }
        }
    }
});

// ── DATATABLE ──
$(document).ready(function() {
    $('.dataTable').DataTable({
        order: [[6, 'desc']],
        pageLength: 10,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' }
    });
});
</script>
</body>
</html>