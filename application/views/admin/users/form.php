<?php defined('BASEPATH') OR exit('No direct script access allowed');
$is_edit = isset($user_data);
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    <a href="<?= base_url('index.php/admin/users') ?>" class="btn btn-secondary btn-sm">
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
        <h6 class="m-0 font-weight-bold text-primary"><?= $title ?></h6>
    </div>
    <div class="card-body">
        <form method="post"
              action="<?= $is_edit
                ? base_url('index.php/admin/users/' . $user_data->id . '/update')
                : base_url('index.php/admin/users/simpan') ?>">

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <input type="text" name="nama" class="form-control" required
                           value="<?= $is_edit ? htmlspecialchars($user_data->nama) : '' ?>"
                           placeholder="Nama lengkap pengguna">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No WhatsApp <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <input type="text" name="no_wa" class="form-control" required
                           value="<?= $is_edit ? htmlspecialchars($user_data->no_wa) : '' ?>"
                           placeholder="08xxxxxxxxxx"
                           <?= $is_edit ? '' : '' ?>>
                    <small class="text-muted">Format bebas, akan otomatis dikonversi ke 62xxx.</small>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Role <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <select name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin"  <?= ($is_edit && $user_data->role == 'admin')  ? 'selected' : '' ?>>Admin</option>
                        <option value="umkm"   <?= ($is_edit && $user_data->role == 'umkm')   ? 'selected' : '' ?>>Pemilik UMKM</option>
                        <option value="user"   <?= ($is_edit && $user_data->role == 'user')   ? 'selected' : '' ?>>User (Pembeli)</option>
                    </select>
                </div>
            </div>

            <?php if ($is_edit): ?>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Status Verifikasi</label>
                <div class="col-sm-9">
                    <select name="is_verified" class="form-control">
                        <option value="1" <?= $user_data->is_verified ? 'selected' : '' ?>>Terverifikasi</option>
                        <option value="0" <?= !$user_data->is_verified ? 'selected' : '' ?>>Belum Verifikasi</option>
                    </select>
                </div>
            </div>
            <?php endif; ?>

            <hr>
            <div class="form-group row">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> <?= $is_edit ? 'Simpan Perubahan' : 'Tambah Pengguna' ?>
                    </button>
                    <a href="<?= base_url('index.php/admin/users') ?>" class="btn btn-secondary ml-2">Batal</a>
                </div>
            </div>

        </form>
    </div>
</div>