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

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('index.php/umkm/dashboard') ?>">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-store"></i>
    </div>
    <div class="sidebar-brand-text mx-3">UMKM Catalog</div>
</a>

<!-- Dashboard -->
<li class="nav-item <?= (uri_string() == 'umkm/dashboard') ? 'active' : '' ?>">
    <a class="nav-link" href="<?= base_url('index.php/umkm/dashboard') ?>">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>

<!-- Produk -->
<li class="nav-item <?= (strpos(uri_string(), 'umkm/produk') !== false) ? 'active' : '' ?>">
    <a class="nav-link" href="<?= base_url('index.php/umkm/kelola_produk') ?>">
        <i class="fas fa-fw fa-box"></i>
         <span>Kelola Produk</span>
    </a>
</li>

<hr class="sidebar-divider d-none d-md-block">
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

        <!-- UMKM -->
        <li class="nav-item <?= (strpos(uri_string(), 'admin/umkm') !== false) ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('index.php/admin/umkm') ?>">
                <i class="fas fa-fw fa-store"></i>
                <span>Data UMKM</span>
            </a>
        </li>

        <!-- Users -->
        <li class="nav-item <?= (strpos(uri_string(), 'admin/users') !== false) ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('index.php/admin/users') ?>">
                <i class="fas fa-fw fa-users"></i>
                <span>Data Pengguna</span>
            </a>
        </li>

        <!-- Laporan -->
        <li class="nav-item <?= (strpos(uri_string(), 'admin/laporan') !== false) ? 'active' : '' ?>">
            <a class="nav-link" href="<?= base_url('index.php/admin/laporan') ?>">
                <i class="fas fa-fw fa-chart-bar"></i>
                <span>Laporan</span>
            </a>
        </li>

        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <div class="dropdown-item">
            <i class="fas fa-phone fa-sm fa-fw mr-2 text-gray-400"></i>
            <?= isset($user->no_wa) ? htmlspecialchars($user->no_wa) : '-' ?>
            </div>

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-toggle="dropdown">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                <?= isset($user->nama) ? htmlspecialchars($user->nama) : 'UMKM' ?>
                            </span>
                            <img class="img-profile rounded-circle"
                                 src="<?= base_url('assets/img/undraw_profile.svg') ?>">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                            <div class="dropdown-item">
                                <i class="fas fa-phone fa-sm fa-fw mr-2 text-gray-400"></i>
                                <?= isset($user['no_wa']) ? htmlspecialchars($user['no_wa']) : '' ?>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('index.php/logout') ?>"
                               onclick="return confirm('Yakin mau logout?')">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>

            </nav>
            <!-- End Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">