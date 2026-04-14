<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-xl-5 col-lg-6 col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header text-center py-4" style="background:#1cc88a">
                    <h4 class="text-white font-weight-bold mb-0">
                        <i class="fab fa-whatsapp mr-2"></i> Verifikasi WhatsApp
                    </h4>
                    <p class="text-white-50 mb-0 small">Masukkan nomor WhatsApp kamu</p>
                </div>
                <div class="card-body p-4">

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mb-4">
                        <div style="font-size:50px;color:#1cc88a"><i class="fab fa-whatsapp"></i></div>
                        <p class="text-muted mt-2">
                            Kode OTP akan dikirim ke nomor WhatsApp kamu untuk memverifikasi akun.
                        </p>
                    </div>

                    <form action="<?= base_url('index.php/kirim-otp') ?>" method="POST">
                        <div class="form-group">
                            <label class="font-weight-bold">Nomor WhatsApp</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fab fa-whatsapp text-success"></i></span>
                                </div>
                                <input type="text" name="no_wa" class="form-control form-control-lg"
                                       placeholder="08xxxxxxxxxx" required autofocus>
                            </div>
                            <small class="text-muted">Format: 08xxx atau +62xxx</small>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg btn-block font-weight-bold">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim OTP
                        </button>
                    </form>

                    <hr>
                    <div class="text-center">
                        <a href="<?= base_url('index.php/logout') ?>" class="text-muted small">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali / Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>