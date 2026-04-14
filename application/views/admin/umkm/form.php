<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$is_edit = isset($umkm);
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    <a href="<?= base_url('admin/umkm') ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
    </a>
</div>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data"
              action="<?= $is_edit ? base_url('admin/umkm/' . $umkm->id . '/update') : base_url('admin/umkm/simpan') ?>">

            <?php if (!$is_edit): ?>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Pemilik <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Pilih Pemilik --</option>
                        <?php foreach ($owners as $o): ?>
                            <option value="<?= $o->id ?>"><?= htmlspecialchars($o->nama) ?> (<?= $o->no_wa ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($owners)): ?>
                        <small class="text-warning">Belum ada user role UMKM.
                            <a href="<?= base_url('admin/users/tambah') ?>">Tambah user dulu</a>.
                        </small>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Pemilik</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control"
                           value="<?= htmlspecialchars($umkm->nama_pemilik ?? '-') ?>" disabled>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama Toko <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <input type="text" name="nama_toko" class="form-control" required
                           value="<?= $is_edit ? htmlspecialchars($umkm->nama_toko) : '' ?>"
                           placeholder="Contoh: Warung Maju Jaya">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No WA Toko</label>
                <div class="col-sm-9">
                    <input type="text" name="no_wa_toko" class="form-control"
                           value="<?= $is_edit ? htmlspecialchars($umkm->no_wa_toko ?? '') : '' ?>"
                           placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat</label>
                <div class="col-sm-9">
                    <textarea name="alamat" class="form-control" rows="2"
                              placeholder="Alamat toko"><?= $is_edit ? htmlspecialchars($umkm->alamat ?? '') : '' ?></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Deskripsi</label>
                <div class="col-sm-9">
                    <textarea name="deskripsi" class="form-control" rows="3"
                              placeholder="Deskripsi singkat toko"><?= $is_edit ? htmlspecialchars($umkm->deskripsi ?? '') : '' ?></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Foto Toko</label>
                <div class="col-sm-9">
                    <?php if ($is_edit && !empty($umkm->foto)): ?>
                        <img src="<?= base_url('uploads/umkm/' . $umkm->foto) ?>"
                             height="80" class="rounded mb-2 d-block" style="object-fit:cover">
                        <small class="text-muted">Upload baru untuk mengganti.</small><br>
                    <?php endif; ?>
                    <input type="file" name="foto" class="form-control-file mt-1" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG. Maks 2MB.</small>
                </div>
            </div>

            <hr>
            <div class="form-group row">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        <?= $is_edit ? 'Simpan Perubahan' : 'Tambah UMKM' ?>
                    </button>
                    <a href="<?= base_url('admin/umkm') ?>" class="btn btn-secondary ml-2">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>