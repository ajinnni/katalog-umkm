<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Pengguna</h1>
    <a href="<?= base_url('admin/users/tambah') ?>" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus fa-sm mr-1"></i> Tambah Pengguna
    </a>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $this->session->flashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $this->session->flashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover dataTable" width="100%">
                <thead class="thead-light">
                    <tr><th>#</th><th>Nama</th><th>No WA</th><th>Role</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php foreach ($users as $i => $u): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($u->nama) ?></td>
                    <td><?= htmlspecialchars($u->no_wa) ?></td>
                    <td>
                        <?php $rc = ['admin'=>'danger','umkm'=>'warning','user'=>'info']; ?>
                        <span class="badge badge-<?= $rc[$u->role] ?? 'secondary' ?>">
                            <?= ucfirst($u->role) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-<?= $u->is_verified ? 'success' : 'secondary' ?>">
                            <?= $u->is_verified ? 'Terverifikasi' : 'Belum' ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= base_url('admin/users/' . $u->id . '/edit') ?>"
                           class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                        <?php if ($u->id != $this->session->userdata('user_id')): ?>
                        <a href="<?= base_url('admin/users/' . $u->id . '/hapus') ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Hapus pengguna ini?')"><i class="fas fa-trash"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.dataTable').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' }
    });
});
</script>