<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <span class="text-muted small">Selamat datang, <strong><?= htmlspecialchars($user['nama']) ?></strong> 👋</span>
</div>

<!-- Stat Cards -->
<div class="row">

    <!-- Total User -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total User</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_user ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total UMKM -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total UMKM</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_umkm ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-store fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Admin -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Admin</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_admin ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Sistem -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Status Sistem</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <span class="badge badge-success">Online</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-server fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Info Panel -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Ringkasan Sistem</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">Kamu login sebagai <strong>Admin</strong> dengan nomor WA: <code><?= htmlspecialchars($user['no_wa']) ?></code></p>
                <p class="mb-0 text-muted">Gunakan menu di sidebar untuk mengelola data pengguna dan UMKM yang terdaftar di sistem.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="<?= base_url('index.php/admin/users') ?>" class="btn btn-primary btn-sm btn-block mb-2">
                    <i class="fas fa-users mr-1"></i> Kelola Pengguna
                </a>
                <a href="<?= base_url('index.php/logout') ?>" class="btn btn-outline-danger btn-sm btn-block"
                   onclick="return confirm('Yakin mau logout?')">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>