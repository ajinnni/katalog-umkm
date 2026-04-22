<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-white shadow-sm">
            <li class="breadcrumb-item"><a href="<?= site_url('pesanan') ?>">Pesanan</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($pesanan->kode_pesanan) ?></li>
        </ol>
    </nav>

    <!-- Flash -->
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

    <div class="row">

        <!-- Kiri: Info Pesanan + Produk -->
        <div class="col-lg-7">

            <!-- Info Pesanan -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-alt mr-1"></i> Pesanan <?= htmlspecialchars($pesanan->kode_pesanan) ?>
                    </h6>
                    <?php
                    $badge_map = [
                        'pending'      => 'warning',
                        'dikonfirmasi' => 'info',
                        'diproses'     => 'primary',
                        'dikirim'      => 'success',
                        'selesai'      => 'secondary',
                        'dibatalkan'   => 'danger',
                    ];
                    $bc = $badge_map[$pesanan->status] ?? 'light';
                    ?>
                    <span class="badge badge-<?= $bc ?> badge-pill px-3 py-2"><?= ucfirst($pesanan->status) ?></span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Pembeli:</strong> <?= htmlspecialchars($pesanan->nama_pemesan) ?></p>
                            <p class="mb-1"><strong>No. WA:</strong>
                                <a href="https://wa.me/<?= preg_replace('/^0/', '62', $pesanan->no_wa_pemesan) ?>" target="_blank">
                                    <?= htmlspecialchars($pesanan->no_wa_pemesan) ?>
                                </a>
                            </p>
                            <p class="mb-1"><strong>Tanggal:</strong> <?= date('d M Y H:i', strtotime($pesanan->created_at)) ?></p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1">
                                <strong>Metode Pengiriman:</strong><br>
                                <?php if ($pesanan->metode_pengiriman === 'pickup'): ?>
                                    <span class="badge badge-success p-2"><i class="fas fa-store mr-1"></i>Ambil Sendiri di Toko</span>
                                <?php else: ?>
                                    <span class="badge badge-info p-2"><i class="fas fa-truck mr-1"></i>Diantar ke Alamat</span>
                                <?php endif; ?>
                            </p>
                            <?php if ($pesanan->metode_pengiriman === 'delivery'): ?>
                            <p class="mb-1 mt-2"><strong>Alamat:</strong><br>
                                <span class="text-muted"><?= nl2br(htmlspecialchars($pesanan->alamat_pengiriman)) ?></span>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($pesanan->catatan): ?>
                    <div class="alert alert-light border mt-3 mb-0">
                        <strong><i class="fas fa-sticky-note mr-1"></i>Catatan:</strong> <?= htmlspecialchars($pesanan->catatan) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Detail Produk -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-box mr-1"></i> Produk Dipesan</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pesanan->details as $d): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($d->foto): ?>
                                            <img src="<?= base_url('uploads/produk/'.$d->foto) ?>" width="40" height="40"
                                                 class="rounded mr-3" style="object-fit:cover">
                                            <?php else: ?>
                                            <div class="bg-light rounded mr-3 d-flex align-items-center justify-content-center"
                                                 style="width:40px;height:40px">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                            <?php endif; ?>
                                            <?= htmlspecialchars($d->nama_produk) ?>
                                        </div>
                                    </td>
                                    <td class="text-center"><?= $d->qty ?></td>
                                    <td class="text-right">Rp <?= number_format($d->harga, 0, ',', '.') ?></td>
                                    <td class="text-right"><strong>Rp <?= number_format($d->subtotal, 0, ',', '.') ?></strong></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <td colspan="3" class="text-right font-weight-bold">TOTAL</td>
                                    <td class="text-right font-weight-bold text-primary">
                                        Rp <?= number_format($pesanan->total_harga, 0, ',', '.') ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div><!-- /col-lg-7 -->

        <!-- Kanan: Panel Aksi & Pengiriman -->
        <div class="col-lg-5">

            <!-- Aksi Cepat -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-bolt mr-1"></i> Aksi</h6>
                </div>
                <div class="card-body">
                    <?php if ($pesanan->status === 'pending'): ?>
                        <a href="<?= site_url('pesanan/konfirmasi/'.$pesanan->id) ?>"
                           class="btn btn-info btn-block mb-2"
                           onclick="return confirm('Konfirmasi pesanan ini?')">
                            <i class="fas fa-check-circle mr-1"></i> Konfirmasi Pesanan
                        </a>
                        <a href="<?= site_url('pesanan/batalkan/'.$pesanan->id) ?>"
                           class="btn btn-outline-danger btn-block"
                           onclick="return confirm('Batalkan pesanan ini?')">
                            <i class="fas fa-times mr-1"></i> Batalkan
                        </a>
                    <?php elseif ($pesanan->status === 'selesai'): ?>
                        <div class="text-center py-3">
                            <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                            <p class="text-muted mb-0">Pesanan telah selesai</p>
                        </div>
                    <?php elseif ($pesanan->status === 'dibatalkan'): ?>
                        <div class="text-center py-3">
                            <i class="fas fa-times-circle fa-3x text-danger mb-2"></i>
                            <p class="text-muted mb-0">Pesanan dibatalkan</p>
                        </div>
                    <?php elseif ($pesanan->status === 'dikirim'): ?>
                        <a href="<?= site_url('pesanan/selesai/'.$pesanan->id) ?>"
                           class="btn btn-success btn-block"
                           onclick="return confirm('Tandai pesanan selesai?')">
                            <i class="fas fa-flag-checkered mr-1"></i> Tandai Selesai
                        </a>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">Status: <strong><?= ucfirst($pesanan->status) ?></strong></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Panel Pengiriman -->
            <?php if (in_array($pesanan->status, ['dikonfirmasi','diproses','dikirim']) && $pesanan->metode_pengiriman === 'pickup'): ?>
            <!-- PICKUP -->
            <div class="card shadow mb-4 border-left-success">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-store mr-1"></i> Ambil Sendiri</h6>
                </div>
                <div class="card-body">
                    <p class="mb-3 text-muted">Pembeli akan mengambil pesanan langsung ke toko.</p>
                    <?php if ($pesanan->status !== 'dikirim'): ?>
                    <a href="<?= site_url('pesanan/atur_kirim/'.$pesanan->id) ?>"
                       class="btn btn-success btn-block"
                       onclick="return confirm('Tandai pesanan siap diambil?')">
                        <i class="fas fa-check mr-1"></i> Tandai Siap Diambil
                    </a>
                    <?php else: ?>
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle mr-1"></i> Pesanan sudah siap diambil
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php elseif (in_array($pesanan->status, ['dikonfirmasi','diproses']) && $pesanan->metode_pengiriman === 'delivery'): ?>
            <!-- FORM ATUR KIRIM (delivery, belum diatur) -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-truck mr-1"></i> Atur Pengiriman
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= site_url('pesanan/atur_kirim/'.$pesanan->id) ?>" id="formKirim">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                        <!-- Pilih Metode -->
                        <div class="form-group">
                            <label class="font-weight-bold">Metode Pengiriman</label>
                            <div class="d-flex gap-3">
                                <div class="custom-control custom-radio mr-4">
                                    <input type="radio" id="metodeJasa" name="metode_kirim_umkm" value="jasa"
                                           class="custom-control-input" <?= $pesanan->metode_kirim_umkm === 'jasa' ? 'checked' : '' ?>
                                           onchange="toggleMetode(this.value)">
                                    <label class="custom-control-label" for="metodeJasa">
                                        <i class="fas fa-box text-info mr-1"></i> Jasa Kurir
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="metodeSendiri" name="metode_kirim_umkm" value="sendiri"
                                           class="custom-control-input" <?= $pesanan->metode_kirim_umkm === 'sendiri' ? 'checked' : '' ?>
                                           onchange="toggleMetode(this.value)">
                                    <label class="custom-control-label" for="metodeSendiri">
                                        <i class="fas fa-motorcycle text-warning mr-1"></i> Antar Sendiri
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- JASA KURIR -->
                        <div id="sectionJasa" style="display:none">
                            <div class="form-group">
                                <label>Pilih Kurir</label>
                                <select name="jasa_kurir" class="form-control">
                                    <option value="">-- Pilih Kurir --</option>
                                    <?php
                                    $kurir_list = ['JNE','J&T Express','SiCepat','AnterAja','Pos Indonesia','GoSend','GrabExpress','Wahana'];
                                    foreach ($kurir_list as $k): ?>
                                    <option value="<?= $k ?>" <?= $pesanan->jasa_kurir === $k ? 'selected' : '' ?>><?= $k ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nomor Resi <small class="text-muted">(opsional, bisa diisi nanti)</small></label>
                                <input type="text" name="no_resi" class="form-control"
                                       placeholder="Contoh: JNE000123456789"
                                       value="<?= htmlspecialchars($pesanan->no_resi ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label>Estimasi Pengiriman</label>
                                <select name="estimasi_pengiriman" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <?php
                                    $estimasi_list = ['1-2 hari kerja','2-3 hari kerja','3-5 hari kerja','Hari ini (same day)','Besok (next day)'];
                                    foreach ($estimasi_list as $e): ?>
                                    <option value="<?= $e ?>" <?= $pesanan->estimasi_pengiriman === $e ? 'selected' : '' ?>><?= $e ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- ANTAR SENDIRI -->
                        <div id="sectionSendiri" style="display:none">
                            <div class="form-group">
                                <label>Nama Pengantar</label>
                                <input type="text" name="nama_pengantar" class="form-control"
                                       placeholder="Nama kurir / pengantar"
                                       value="<?= htmlspecialchars($pesanan->nama_pengantar ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label>No. HP Pengantar</label>
                                <input type="text" name="no_hp_pengantar" class="form-control"
                                       placeholder="08xxxxxxxxxx"
                                       value="<?= htmlspecialchars($pesanan->no_hp_pengantar ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label>Estimasi Waktu</label>
                                <select name="estimasi_pengiriman" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <?php
                                    $eta_list = ['Hari ini (dalam 2 jam)','Hari ini (dalam 4 jam)','Besok pagi','Besok siang','2-3 hari kerja'];
                                    foreach ($eta_list as $e): ?>
                                    <option value="<?= $e ?>" <?= $pesanan->estimasi_pengiriman === $e ? 'selected' : '' ?>><?= $e ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-2" id="btnKirim" disabled>
                            <i class="fas fa-paper-plane mr-1"></i> Konfirmasi & Kirim
                        </button>
                    </form>
                </div>
            </div>

            <?php elseif ($pesanan->status === 'dikirim' && $pesanan->metode_pengiriman === 'delivery'): ?>
            <!-- INFO PENGIRIMAN SUDAH DIATUR -->
            <div class="card shadow mb-4 border-left-success">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-shipping-fast mr-1"></i> Info Pengiriman</h6>
                </div>
                <div class="card-body">
                    <?php if ($pesanan->metode_kirim_umkm === 'jasa'): ?>
                        <p class="mb-1"><strong>Kurir:</strong> <?= htmlspecialchars($pesanan->jasa_kurir) ?></p>
                        <p class="mb-1"><strong>No. Resi:</strong>
                            <?= $pesanan->no_resi ? htmlspecialchars($pesanan->no_resi) : '<span class="text-muted">Belum diisi</span>' ?>
                        </p>
                        <p class="mb-3"><strong>Estimasi:</strong> <?= htmlspecialchars($pesanan->estimasi_pengiriman ?? '-') ?></p>

                        <!-- Update Resi -->
                        <form method="POST" action="<?= site_url('pesanan/update_resi/'.$pesanan->id) ?>">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                            <div class="input-group">
                                <input type="text" name="no_resi" class="form-control"
                                       placeholder="Update nomor resi"
                                       value="<?= htmlspecialchars($pesanan->no_resi ?? '') ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="submit">Update</button>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <p class="mb-1"><strong>Pengantar:</strong> <?= htmlspecialchars($pesanan->nama_pengantar ?? '-') ?></p>
                        <p class="mb-1"><strong>No. HP:</strong> <?= htmlspecialchars($pesanan->no_hp_pengantar ?? '-') ?></p>
                        <p class="mb-0"><strong>Estimasi:</strong> <?= htmlspecialchars($pesanan->estimasi_pengiriman ?? '-') ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div><!-- /col-lg-5 -->

    </div><!-- /row -->
</div>

<script>
function toggleMetode(val) {
    document.getElementById('sectionJasa').style.display    = val === 'jasa'    ? 'block' : 'none';
    document.getElementById('sectionSendiri').style.display = val === 'sendiri' ? 'block' : 'none';
    document.getElementById('btnKirim').disabled = false;
}

// Restore state on load
var checkedRadio = document.querySelector('input[name="metode_kirim_umkm"]:checked');
if (checkedRadio) toggleMetode(checkedRadio.value);
</script>
