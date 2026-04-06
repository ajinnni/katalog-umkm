<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-xl-5 col-lg-6 col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header text-center py-4" style="background:#4e73df">
                    <h4 class="text-white font-weight-bold mb-0">
                        <i class="fas fa-store mr-2"></i> UMKM Catalog
                    </h4>
                    <p class="text-white-50 mb-0 small">Masuk ke akun kamu</p>
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

                    <form action="<?= base_url('index.php/login') ?>" method="POST">
                        <div class="form-group">
                            <label class="font-weight-bold">Nomor WhatsApp</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fab fa-whatsapp text-success"></i></span>
                                </div>
                                <input type="text" name="no_wa" class="form-control form-control-lg"
                                    placeholder="Contoh: 08123456789" required autofocus>
                            </div>
                            <small class="text-muted">Kode OTP akan dikirim ke WhatsApp kamu</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block font-weight-bold">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim OTP
                        </button>
                    </form>

                    <hr>
                    <div class="text-center">
                        <span class="text-muted">Belum punya akun?</span>
                        <a href="<?= base_url('index.php/register') ?>" class="font-weight-bold ml-1">Daftar di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>