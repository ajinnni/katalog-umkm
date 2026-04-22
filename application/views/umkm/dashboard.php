<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - UMKM Catalog' : 'UMKM Catalog' ?></title>
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
        <li class="nav-item <?= (uri_string() == 'umkm/dashboard') ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('index.php/umkm/dashboard') ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
        </li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Kelola</div>
        <li class="nav-item <?= (strpos(uri_string(), 'umkm/produk') !== false) ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('index.php/umkm/produk') ?>">
                <i class="fas fa-fw fa-box"></i><span>Kelola Produk</span>
            </a>
        </li>
        <li class="nav-item <?= (strpos(uri_string(), 'umkm/laporan') !== false) ? 'active' : '' ?>">
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
            <!-- Topbar -->
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
                    <h1 class="h3 mb-0 text-gray-800">Dashboard UMKM</h1>
                </div>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('info')): ?>
                    <div class="alert alert-info alert-dismissible fade show">
                        <?= $this->session->flashdata('info') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <?php if (!$umkm): ?>
                <!-- ── BELUM PUNYA TOKO ── -->
                <div class="card shadow mb-4 border-left-warning">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-store-slash fa-4x text-warning mb-3"></i>
                        <h4 class="font-weight-bold text-gray-800">Kamu belum punya toko!</h4>
                        <p class="text-muted mb-4">Daftarkan toko kamu sekarang untuk mulai berjualan di UMKM Catalog.</p>
                        <a href="<?= base_url('index.php/umkm/daftar-toko') ?>" class="btn btn-warning btn-lg">
                            <i class="fas fa-plus-circle mr-2"></i> Daftarkan Toko Sekarang
                        </a>
                    </div>
                </div>

                <?php else: ?>
                <!-- ── PROFIL TOKO ── -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Profil UMKM</h6>
                        <?php if (!$umkm->is_active): ?>
                            <span class="badge badge-warning px-3 py-2">
                                <i class="fas fa-clock mr-1"></i> Menunggu Aktivasi Admin
                            </span>
                        <?php else: ?>
                            <span class="badge badge-success px-3 py-2">
                                <i class="fas fa-check-circle mr-1"></i> Toko Aktif
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php if (!empty($umkm->foto)): ?>
                            <div class="col-md-2 text-center mb-3">
                                <img src="<?= base_url('uploads/toko/' . $umkm->foto) ?>"
                                     style="width:80px;height:80px;object-fit:cover;border-radius:50%;border:3px solid #4e73df;">
                            </div>
                            <div class="col-md-10">
                            <?php else: ?>
                            <div class="col-md-12">
                            <?php endif; ?>
                                <p class="mb-1"><strong>Nama:</strong> <?= htmlspecialchars($user->nama ?? '-') ?></p>
                                <p class="mb-1"><strong>No WA:</strong> <?= htmlspecialchars($user->no_wa ?? '-') ?></p>
                                <p class="mb-1"><strong>Nama Toko:</strong> <?= htmlspecialchars($umkm->nama_toko ?? '-') ?></p>
                                <p class="mb-1"><strong>Alamat:</strong> <?= htmlspecialchars($umkm->alamat ?? '-') ?></p>
                                <p class="mb-0"><strong>No WA Toko:</strong> <?= htmlspecialchars($umkm->no_wa_toko ?? '-') ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!$umkm->is_active): ?>
                <!-- Toko belum aktif — info saja -->
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Toko kamu sedang menunggu aktivasi dari admin. Kamu belum bisa menambah produk sampai toko diaktifkan.
                </div>

                <?php else: ?>
                <!-- ── PRODUK ── -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Produk Saya</h6>
                        <a href="<?= base_url('index.php/umkm/produk/tambah') ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th><th>Foto</th><th>Nama</th><th>Harga</th>
                                        <th>Stok</th><th>Kategori</th><th>Status</th><th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            Belum ada produk.
                                            <a href="<?= base_url('index.php/umkm/produk/tambah') ?>">Tambah sekarang</a>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($products as $i => $p): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td>
                                            <?php if (!empty($p->foto)): ?>
                                                <img src="<?= base_url('uploads/produk/' . $p->foto) ?>"
                                                     width="50" height="50"
                                                     style="object-fit:cover;border-radius:4px">
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($p->nama) ?></td>
                                        <td>Rp <?= number_format($p->harga, 0, ',', '.') ?></td>
                                        <td><?= $p->stok ?></td>
                                        <td><?= htmlspecialchars($p->kategori_nama ?? '-') ?></td>
                                        <td>
                                            <span class="badge badge-<?= $p->is_active ? 'success' : 'secondary' ?>">
                                                <?= $p->is_active ? 'Aktif' : 'Nonaktif' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('index.php/umkm/produk/' . $p->id . '/edit') ?>"
                                               class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <a href="<?= base_url('index.php/umkm/produk/' . $p->id . '/hapus') ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin hapus produk ini?')">
                                               <i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; // is_active ?>
                <?php endif; // umkm ?>

            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>
</body>
</html>