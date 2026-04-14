<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= isset($title) ? $title . ' - UMKM Catalog' : 'UMKM Catalog' ?></title>
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
</head>
<body id="page-top">

<div id="wrapper">

    <!-- SIDEBAR -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <?php $role = $this->session->userdata('role'); ?>

        <?php if ($role === 'admin'): ?>
        <!-- ===== SIDEBAR ADMIN ===== -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('admin') ?>">
            <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-store"></i></div>
            <div class="sidebar-brand-text mx-3">UMKM Catalog</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item <?= (uri_string() == 'admin') ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('admin') ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
        </li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Kelola</div>
        <li class="nav-item <?= (strpos(uri_string(), 'admin/umkm') !== false) ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('admin/umkm') ?>">
                <i class="fas fa-fw fa-store"></i><span>Data UMKM</span>
            </a>
        </li>
        <li class="nav-item <?= (strpos(uri_string(), 'admin/users') !== false) ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('admin/users') ?>">
                <i class="fas fa-fw fa-users"></i><span>Data Pengguna</span>
            </a>
        </li>
        <li class="nav-item <?= (strpos(uri_string(), 'admin/laporan') !== false) ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('admin/laporan') ?>">
                <i class="fas fa-fw fa-chart-bar"></i><span>Laporan</span>
            </a>
        </li>

        <?php elseif ($role === 'umkm'): ?>
        <!-- ===== SIDEBAR UMKM ===== -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('umkm/dashboard') ?>">
            <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-store"></i></div>
            <div class="sidebar-brand-text mx-3">UMKM Catalog</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item <?= (uri_string() == 'umkm/dashboard') ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('umkm/dashboard') ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
        </li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Toko Saya</div>
        <li class="nav-item <?= (strpos(uri_string(), 'umkm/produk') !== false) ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('umkm/produk') ?>">
                <i class="fas fa-fw fa-box"></i><span>Kelola Produk</span>
            </a>
        </li>
        <li class="nav-item <?= (strpos(uri_string(), 'umkm/pesanan') !== false) ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('umkm/pesanan') ?>">
                <i class="fas fa-fw fa-shopping-bag"></i><span>Pesanan</span>
            </a>
        </li>
        <li class="nav-item <?= (strpos(uri_string(), 'umkm/laporan') !== false) ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('umkm/laporan') ?>">
                <i class="fas fa-fw fa-chart-bar"></i><span>Laporan</span>
            </a>
        </li>
        <?php endif; ?>

        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- END SIDEBAR -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- TOPBAR -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                <?= isset($user['nama']) ? htmlspecialchars($user['nama']) : $this->session->userdata('nama') ?>
                            </span>
                            <img class="img-profile rounded-circle"
                                 style="width:32px;height:32px;object-fit:cover;"
                                 src="<?= base_url('assets/img/undraw_profile.svg') ?>">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                            <div class="dropdown-item">
                                <i class="fas fa-phone fa-sm fa-fw mr-2 text-gray-400"></i>
                                <?= isset($user['no_wa']) ? htmlspecialchars($user['no_wa']) : $this->session->userdata('no_wa') ?>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('logout') ?>"
                               onclick="return confirm('Yakin mau logout?')">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- END TOPBAR -->

            <!-- Page Content -->
            <div class="container-fluid">