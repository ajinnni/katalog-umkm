<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - UMKM Catalog' : 'UMKM Catalog' ?></title>
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
        <li class="nav-item active">
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
                    <h1 class="h3 mb-0 text-gray-800"><?= isset($title) ? htmlspecialchars($title) : '' ?></h1>
                    <a href="<?= base_url('index.php/umkm/produk') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
                    </a>
                </div>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><?= isset($title) ? htmlspecialchars($title) : '' ?></h6>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data"
                              action="<?= isset($product)
                                ? base_url('index.php/umkm/produk/' . $product->id . '/update')
                                : base_url('index.php/umkm/produk/simpan') ?>">

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Nama Produk <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama" class="form-control" required
                                           placeholder="Contoh: Keripik Tempe Pedas"
                                           value="<?= isset($product) ? htmlspecialchars($product->nama) : '' ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Kategori <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="kategori_id" class="form-control" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php foreach ($kategori as $k): ?>
                                            <option value="<?= $k->id ?>"
                                                <?= (isset($product) && $product->kategori_id == $k->id) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($k->nama) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Harga <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" name="harga" class="form-control" required
                                               min="0" placeholder="0"
                                               value="<?= isset($product) ? $product->harga : '' ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Stok</label>
                                <div class="col-sm-9">
                                    <input type="number" name="stok" class="form-control"
                                           min="0" placeholder="0"
                                           value="<?= isset($product) ? $product->stok : '0' ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Deskripsi</label>
                                <div class="col-sm-9">
                                    <textarea name="deskripsi" class="form-control" rows="4"
                                              placeholder="Deskripsi produk..."><?= isset($product) ? htmlspecialchars($product->deskripsi) : '' ?></textarea>
                                </div>
                            </div>

                            <!-- FOTO — wajib saat tambah, opsional saat edit -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">
                                    Foto Produk
                                    <?php if (!isset($product)): ?>
                                        <span class="text-danger">*</span>
                                    <?php endif; ?>
                                </label>
                                <div class="col-sm-9">
                                    <?php if (isset($product) && $product->foto): ?>
                                        <div class="mb-2">
                                            <img id="previewImg"
                                                 src="<?= base_url('uploads/produk/' . $product->foto) ?>"
                                                 height="100" style="object-fit:cover;border-radius:6px;border:2px solid #4e73df;">
                                            <small class="text-muted ml-2">Upload baru untuk mengganti.</small>
                                        </div>
                                    <?php else: ?>
                                        <!-- Preview untuk tambah baru -->
                                        <div id="previewWrap" style="display:none;margin-bottom:10px;">
                                            <img id="previewImg" src="" alt="preview"
                                                 style="height:100px;object-fit:cover;border-radius:6px;border:2px solid #4e73df;">
                                        </div>
                                    <?php endif; ?>

                                    <input type="file" name="foto" id="fotoInput" class="form-control-file"
                                           accept="image/jpeg,image/png"
                                           <?= !isset($product) ? 'required' : '' ?>>
                                    <small class="text-muted">
                                        Format: JPG, PNG. Maks 2MB.
                                        <?= !isset($product) ? '<strong>Wajib diisi.</strong>' : '' ?>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9 d-flex align-items-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active"
                                               name="is_active" value="1"
                                               <?= (!isset($product) || $product->is_active) ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="is_active">Produk Aktif</label>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i>
                                        <?= isset($product) ? 'Simpan Perubahan' : 'Tambah Produk' ?>
                                    </button>
                                    <a href="<?= base_url('index.php/umkm/produk') ?>" class="btn btn-secondary ml-2">
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
// Preview foto + validasi ukuran
document.getElementById('fotoInput').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;

    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran file maksimal 2MB!');
        this.value = '';
        <?php if (!isset($product)): ?>
        document.getElementById('previewWrap').style.display = 'none';
        <?php endif; ?>
        return;
    }

    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        <?php if (!isset($product)): ?>
        document.getElementById('previewWrap').style.display = 'block';
        <?php endif; ?>
    };
    reader.readAsDataURL(file);
});
</script>
</body>
</html>