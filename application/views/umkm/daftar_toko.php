<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Daftar Toko - UMKM Catalog</title>
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
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
        <li class="nav-item">
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
                    <h1 class="h3 mb-0 text-gray-800">Daftar Toko Saya</h1>
                </div>

                <!-- Info box -->
                <div class="alert alert-info alert-dismissible fade show mb-4">
                    <i class="fas fa-info-circle mr-2"></i>
                    Lengkapi data toko kamu. Setelah didaftarkan, toko akan <strong>menunggu aktivasi dari admin</strong> sebelum bisa tampil di katalog.
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-store mr-1"></i> Informasi Toko
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data"
                              action="<?= base_url('index.php/umkm/simpan-toko') ?>">

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">
                                    Nama Toko <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama_toko" class="form-control" required
                                           placeholder="Contoh: Warung Bu Siti"
                                           value="<?= htmlspecialchars($this->input->post('nama_toko') ?? '') ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">
                                    Deskripsi Toko <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <textarea name="deskripsi" class="form-control" rows="3" required
                                              placeholder="Ceritakan tentang toko kamu..."><?= htmlspecialchars($this->input->post('deskripsi') ?? '') ?></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">
                                    Alamat Toko <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <textarea name="alamat" class="form-control" rows="2" required
                                              placeholder="Jl. Contoh No. 1, Kota..."><?= htmlspecialchars($this->input->post('alamat') ?? '') ?></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">
                                    No WhatsApp Toko <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" name="no_wa_toko" class="form-control" required
                                           placeholder="08xxxxxxxxxx"
                                           value="<?= htmlspecialchars($this->input->post('no_wa_toko') ?? '') ?>">
                                    <small class="text-muted">Nomor WA yang akan dihubungi pembeli</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">
                                    Foto Toko <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <!-- Preview -->
                                    <div id="previewWrap" style="display:none;margin-bottom:10px;">
                                        <img id="previewImg" src="" alt="preview"
                                             style="height:120px;object-fit:cover;border-radius:8px;border:2px solid #4e73df;">
                                    </div>
                                    <input type="file" name="foto" id="fotoInput" class="form-control-file" 
                                           accept="image/jpeg,image/png" required>
                                    <small class="text-muted">Format: JPG, PNG. Maks 2MB. <strong>Wajib diisi.</strong></small>
                                </div>
                            </div>

                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane mr-1"></i> Daftarkan Toko
                                    </button>
                                    <a href="<?= base_url('index.php/umkm/dashboard') ?>" class="btn btn-secondary ml-2">
                                        Batal
                                    </a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>
<script>
// Preview foto sebelum upload
document.getElementById('fotoInput').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran file maksimal 2MB!');
        this.value = '';
        document.getElementById('previewWrap').style.display = 'none';
        return;
    }
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewWrap').style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
</body>
</html>