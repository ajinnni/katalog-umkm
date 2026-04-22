<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= htmlspecialchars($title) ?> - UMKM Catalog</title>
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('index.php/umkm/dashboard') ?>">
            <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-store"></i></div>
            <div class="sidebar-brand-text mx-3">UMKM Catalog</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('index.php/umkm/dashboard') ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
        </li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Kelola</div>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('index.php/umkm/produk') ?>">
                <i class="fas fa-fw fa-box"></i><span>Kelola Produk</span>
            </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="<?= base_url('index.php/umkm/laporan') ?>">
                <i class="fas fa-fw fa-chart-bar"></i><span>Laporan Penjualan</span>
            </a>
        </li>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                <?= isset($user->nama) ? htmlspecialchars($user->nama) : 'UMKM' ?>
                            </span>
                            <img class="img-profile rounded-circle" style="width:32px;height:32px;object-fit:cover"
                                 src="<?= base_url('assets/img/undraw_profile.svg') ?>">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                            <div class="dropdown-item">
                                <i class="fas fa-phone fa-sm fa-fw mr-2 text-gray-400"></i>
                                <?= isset($user->no_wa) ? htmlspecialchars($user->no_wa) : '-' ?>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="<?= base_url('index.php/logout') ?>"
                               onclick="return confirm('Yakin mau logout?')">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Laporan Penjualan</h1>
                </div>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pesanan</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_pesanan ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-shopping-bag fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Konfirmasi</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_pending ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pesanan Selesai</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_selesai ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Omzet</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_omzet, 0, ',', '.') ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Pesanan Masuk</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover dataTable" width="100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Kode</th>
                                        <th>Pemesan</th>
                                        <th>No WA</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($pesanan)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">Belum ada pesanan masuk.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($pesanan as $i => $p): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><span class="badge badge-secondary"><?= $p->kode_pesanan ?></span></td>
                                        <td><?= htmlspecialchars($p->nama_pemesan) ?></td>
                                        <td>
                                            <a href="https://wa.me/<?= preg_replace('/\D/', '', $p->no_wa_pemesan) ?>"
                                               target="_blank" class="text-success">
                                                <i class="fab fa-whatsapp"></i> <?= $p->no_wa_pemesan ?>
                                            </a>
                                        </td>
                                        <td>Rp <?= number_format($p->total_harga, 0, ',', '.') ?></td>
                                        <td>
                                            <?php
                                            $badge = [
                                                'pending'      => 'warning',
                                                'dikonfirmasi' => 'info',
                                                'diproses'     => 'primary',
                                                'dikirim'      => 'secondary',
                                                'selesai'      => 'success',
                                                'dibatalkan'   => 'danger',
                                            ];
                                            $b = $badge[$p->status] ?? 'secondary';
                                            ?>
                                            <span class="badge badge-<?= $b ?>"><?= ucfirst($p->status) ?></span>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($p->created_at)) ?></td>
                                        <td>
                                            <form method="POST" action="<?= base_url('index.php/umkm/pesanan/' . $p->id . '/status') ?>" style="display:inline">
                                                <select name="status" class="form-control form-control-sm d-inline-block w-auto"
                                                        onchange="this.form.submit()">
                                                    <?php foreach (['pending','dikonfirmasi','diproses','dikirim','selesai','dibatalkan'] as $s): ?>
                                                        <option value="<?= $s ?>" <?= $p->status === $s ? 'selected' : '' ?>>
                                                            <?= ucfirst($s) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </form>
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
    </div>
</div>

<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>
<script>
$(document).ready(function() {
    $('.dataTable').DataTable({
        order: [[6, 'desc']],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' }
    });
});
</script>
</body>
</html>