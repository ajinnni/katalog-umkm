<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Pengguna</h1>
    <a href="<?= base_url('index.php/admin/users/tambah') ?>" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus fa-sm mr-1"></i> Tambah Pengguna
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
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama</th>
                        <th>No WhatsApp</th>
                        <th width="12%">Role</th>
                        <th width="12%">Status</th>
                        <th width="15%">Terdaftar</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pengguna.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $i => $u): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <strong><?= htmlspecialchars($u->nama) ?></strong>
                            <?php if ($u->id == $this->session->userdata('user_id')): ?>
                                <span class="badge badge-info ml-1">Kamu</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($u->no_wa) ?></td>
                        <td>
                            <?php
                            $role_class = ['admin' => 'danger', 'umkm' => 'warning', 'user' => 'secondary'];
                            $role_label = ['admin' => 'Admin', 'umkm' => 'UMKM', 'user' => 'User'];
                            $rc = $role_class[$u->role] ?? 'secondary';
                            $rl = $role_label[$u->role] ?? $u->role;
                            ?>
                            <span class="badge badge-<?= $rc ?>"><?= $rl ?></span>
                        </td>
                        <td>
                            <span class="badge badge-<?= $u->is_verified ? 'success' : 'light text-muted' ?>">
                                <?= $u->is_verified ? 'Terverifikasi' : 'Belum Verifikasi' ?>
                            </span>
                        </td>
                        <td><small><?= date('d M Y', strtotime($u->created_at)) ?></small></td>
                        <td>
                            <a href="<?= base_url('index.php/admin/users/' . $u->id . '/edit') ?>"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if ($u->id != $this->session->userdata('user_id')): ?>
                            <a href="<?= base_url('index.php/admin/users/' . $u->id . '/hapus') ?>"
                               class="btn btn-danger btn-sm" title="Hapus"
                               onclick="return confirm('Yakin hapus pengguna ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>