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
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('index.php/umkm/dashboard') ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
        </li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Kelola</div>
        <li class="nav-item active">
            <a class="nav-link" href="<?= base_url('index.php/umkm/produk') ?>">
                <i class="fas fa-fw fa-box"></i><span>Kelola Produk</span>
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
                            <img class="img-profile rounded-circle" style="width:32px;height:32px;object-fit:cover"
                                 src="<?= base_url('assets/img/undraw_profile.svg') ?>">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
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
                    <h1 class="h3 mb-0 text-gray-800">Kelola Produk</h1>
                    <a href="<?= base_url('index.php/umkm/produk/tambah') ?>" class="btn btn-primary btn-sm shadow-sm">
                        <i class="fas fa-plus fa-sm mr-1"></i> Tambah Produk
                    </a>
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

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover dataTable" width="100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Foto</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
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
                                        <td><?= htmlspecialchars($p->kategori_nama ?? '-') ?></td>
                                        <td>Rp <?= number_format($p->harga, 0, ',', '.') ?></td>
                                        <td><?= $p->stok ?></td>
                                        <td>
                                            <span class="badge badge-<?= $p->is_active ? 'success' : 'secondary' ?>">
                                                <?= $p->is_active ? 'Aktif' : 'Nonaktif' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('index.php/umkm/produk/' . $p->id . '/edit') ?>"
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('index.php/umkm/produk/' . $p->id . '/hapus') ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin hapus produk ini?')">
                                                <i class="fas fa-trash"></i>
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
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });
});
</script>
</body>
</html>