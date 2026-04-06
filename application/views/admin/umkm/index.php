<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Kelola UMKM</h1>
    <a href="<?= base_url('index.php/admin/umkm/tambah') ?>" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus fa-sm mr-1"></i> Tambah UMKM
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
        <h6 class="m-0 font-weight-bold text-primary">Daftar Toko UMKM</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tabelUmkm" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%">Foto</th>
                        <th>Nama Toko</th>
                        <th>Pemilik</th>
                        <th>No WA</th>
                        <th>Alamat</th>
                        <th width="10%">Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($list_umkm)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data UMKM.</td></tr>
                <?php else: ?>
                    <?php foreach ($list_umkm as $i => $u): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td class="text-center">
                            <?php if ($u->foto): ?>
                                <img src="<?= base_url('uploads/umkm/' . $u->foto) ?>"
                                     class="rounded" width="50" height="50" style="object-fit:cover">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="width:50px;height:50px;margin:auto">
                                    <i class="fas fa-store text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($u->nama_toko) ?></strong>
                            <?php if ($u->deskripsi): ?>
                                <br><small class="text-muted"><?= htmlspecialchars(substr($u->deskripsi, 0, 60)) ?>...</small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($u->nama_pemilik) ?></td>
                        <td><?= htmlspecialchars($u->no_wa_toko ?: '-') ?></td>
                        <td><?= htmlspecialchars($u->alamat ?: '-') ?></td>
                        <td class="text-center">
                            <a href="<?= base_url('index.php/admin/umkm/' . $u->id . '/toggle') ?>"
                               class="badge badge-<?= $u->is_active ? 'success' : 'secondary' ?>"
                               style="cursor:pointer;font-size:0.85em"
                               onclick="return confirm('Ubah status UMKM ini?')">
                                <?= $u->is_active ? 'Aktif' : 'Nonaktif' ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= base_url('index.php/admin/umkm/' . $u->id . '/edit') ?>"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= base_url('index.php/admin/umkm/' . $u->id . '/hapus') ?>"
                               class="btn btn-danger btn-sm" title="Hapus"
                               onclick="return confirm('Yakin hapus UMKM ini? Semua produknya juga akan terhapus.')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
