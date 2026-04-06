<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-xl-5 col-lg-6 col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header text-center py-4" style="background:#4e73df">
                    <h4 class="text-white font-weight-bold mb-0">
                        <i class="fas fa-store mr-2"></i> UMKM Catalog
                    </h4>
                    <p class="text-white-50 mb-0 small">Buat akun baru</p>
                </div>
                <div class="card-body p-4">

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('register') ?>" method="POST">
                        <div class="form-group">
                            <label class="font-weight-bold">Nama Lengkap</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="nama" class="form-control form-control-lg"
                                    placeholder="Nama lengkap kamu" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Nomor WhatsApp</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fab fa-whatsapp text-success"></i></span>
                                </div>
                                <input type="text" name="no_wa" class="form-control form-control-lg"
                                    placeholder="Contoh: 08123456789" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Daftar sebagai</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="role" id="role_user" value="user"
                                            class="custom-control-input" checked>
                                        <label class="custom-control-label" for="role_user">
                                            <i class="fas fa-user mr-1"></i> Pembeli
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="role" id="role_umkm" value="umkm"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="role_umkm">
                                            <i class="fas fa-store mr-1"></i> Pemilik UMKM
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg btn-block font-weight-bold">
                            <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                        </button>
                    </form>

                    <hr>
                    <div class="text-center">
                        <span class="text-muted">Sudah punya akun?</span>
                        <a href="<?= base_url('login') ?>" class="font-weight-bold ml-1">Login di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>