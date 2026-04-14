<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$is_edit = isset($user_data);
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
    </a>
</div>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <form method="POST"
              action="<?= $is_edit ? base_url('admin/users/' . $user_data->id . '/update') : base_url('admin/users/simpan') ?>">

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <input type="text" name="nama" class="form-control" required
                           value="<?= $is_edit ? htmlspecialchars($user_data->nama) : '' ?>"
                           placeholder="Nama lengkap">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No WhatsApp <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <input type="text" name="no_wa" class="form-control" required
                           value="<?= $is_edit ? htmlspecialchars($user_data->no_wa) : '' ?>"
                           placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Role <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <select name="role" class="form-control" required>
                        <?php foreach (['admin','umkm','user'] as $r): ?>
                            <option value="<?= $r ?>"
                                <?= ($is_edit && $user_data->role === $r) ? 'selected' : '' ?>>
                                <?= ucfirst($r) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <?php if ($is_edit): ?>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Status Verifikasi</label>
                <div class="col-sm-9 d-flex align-items-center">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_verified"
                               name="is_verified" value="1"
                               <?= $user_data->is_verified ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="is_verified">Sudah Terverifikasi</label>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <hr>
            <div class="form-group row">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        <?= $is_edit ? 'Simpan Perubahan' : 'Tambah Pengguna' ?>
                    </button>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary ml-2">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>