<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data UMKM</h1>
    <a href="<?= base_url('admin/umkm/tambah') ?>" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus fa-sm mr-1"></i> Tambah UMKM
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
                    <tr>
                        <th>#</th><th>Foto</th><th>Nama Toko</th><th>Pemilik</th>
                        <th>No WA</th><th>Alamat</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($list_umkm)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada UMKM terdaftar.</td></tr>
                <?php else: ?>
                    <?php foreach ($list_umkm as $i => $u): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <?php if (!empty($u->foto)): ?>
                                <img src="<?= base_url('uploads/umkm/' . $u->foto) ?>"
                                     width="50" height="50" style="object-fit:cover;border-radius:4px">
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($u->nama_toko) ?></strong></td>
                        <td><?= htmlspecialchars($u->nama_pemilik ?? '-') ?></td>
                        <td>
                            <?php if (!empty($u->no_wa_toko)): ?>
                                <a href="https://wa.me/<?= $u->no_wa_toko ?>" target="_blank" class="text-success">
                                    <i class="fab fa-whatsapp"></i> <?= $u->no_wa_toko ?>
                                </a>
                            <?php else: ?>-<?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($u->alamat ?? '-') ?></td>
                        <td>
                            <a href="<?= base_url('admin/umkm/' . $u->id . '/toggle') ?>"
                               class="badge badge-<?= $u->is_active ? 'success' : 'secondary' ?>"
                               style="cursor:pointer;padding:5px 10px;font-size:.85rem">
                                <?= $u->is_active ? 'Aktif' : 'Nonaktif' ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/umkm/' . $u->id . '/edit') ?>"
                               class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="<?= base_url('admin/umkm/' . $u->id . '/hapus') ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Hapus UMKM ini?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
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