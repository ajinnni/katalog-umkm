<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-xl-5 col-lg-6 col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header text-center py-4" style="background:#1cc88a">
                    <h4 class="text-white font-weight-bold mb-0">
                        <i class="fab fa-whatsapp mr-2"></i> Verifikasi OTP
                    </h4>
                    <p class="text-white-50 mb-0 small">Masukkan kode yang dikirim ke WhatsApp kamu</p>
                </div>
                <div class="card-body p-4">

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('info')): ?>
                        <div class="alert alert-info alert-dismissible fade show">
                            <?= $this->session->flashdata('info') ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mb-4">
                        <div style="font-size:60px; color:#1cc88a">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <p class="text-muted">Kode OTP berlaku selama <strong>5 menit</strong></p>
                    </div>

                    <form action="<?= base_url('index.php/verifikasi-otp') ?>" method="POST">
                        <div class="form-group">
                            <label class="font-weight-bold">Kode OTP (6 digit)</label>
                            <input type="text" name="otp" class="form-control form-control-lg text-center"
                                placeholder="_ _ _ _ _ _" maxlength="6"
                                style="font-size:28px; letter-spacing:8px; font-weight:bold"
                                required autofocus autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-success btn-lg btn-block font-weight-bold">
                            <i class="fas fa-check-circle mr-2"></i> Verifikasi
                        </button>
                    </form>

                    <hr>
                    <div class="text-center">
                        <a href="<?= base_url('index.php/login') ?>" class="text-muted small">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>