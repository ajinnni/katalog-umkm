<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login UMKM</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{box-sizing:border-box}
body{margin:0;font-family:Segoe UI;background:linear-gradient(135deg,#4e73df,#224abe);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{width:100%;max-width:420px;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 15px 35px rgba(0,0,0,.25)}
.header{background:#4e73df;color:#fff;text-align:center;padding:24px}
.header i{font-size:36px;margin-bottom:8px;display:block}
.header h2{margin:0;font-size:20px}
.header p{margin:5px 0 0;font-size:13px;opacity:.85}
.body{padding:26px}
.group{margin-bottom:16px}
label{display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:#333}
.input{width:100%;padding:12px 14px;border:1.5px solid #ddd;border-radius:10px;outline:none;transition:.2s;font-size:14px;font-family:Segoe UI}
.input:focus{border-color:#4e73df;box-shadow:0 0 0 3px rgba(78,115,223,.15)}
.pw-box{position:relative}
.eye{position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#999}
.btn{width:100%;padding:13px;background:#4e73df;border:none;color:#fff;font-weight:700;border-radius:10px;cursor:pointer;font-size:14px;margin-top:4px;transition:.2s}
.btn:hover{background:#2e59d9}
.footer{text-align:center;margin-top:16px;font-size:13px;color:#666}
.footer a{color:#4e73df;font-weight:700;text-decoration:none}
.alert{padding:11px 14px;border-radius:8px;margin-bottom:16px;font-size:13px;border-left:4px solid #e74c3c;background:#fde8e8;color:#c0392b;display:flex;gap:8px;align-items:center}
.alert-success{background:#e8fdf4;color:#1a7a4a;border-color:#1cc88a}
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <i class="fas fa-store"></i>
        <h2>UMKM Catalog</h2>
        <p>Masuk ke akun kamu</p>
    </div>
    <div class="body">

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert">
                <i class="fas fa-exclamation-circle"></i>
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('index.php/proses-login') ?>" method="POST">
            <div class="group">
                <label>Nomor WhatsApp</label>
                <input type="text" name="no_wa" class="input"
                       placeholder="08xxxxxxxxxx" required autofocus>
            </div>
            <div class="group">
                <label>Password</label>
                <div class="pw-box">
                    <input type="password" id="pw" name="password"
                           class="input" placeholder="Password kamu" required>
                    <span class="eye" onclick="togglePw()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>
            <button class="btn">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </button>
        </form>

        <div class="footer">
            Belum punya akun?
            <a href="<?= base_url('index.php/register') ?>">Daftar di sini</a>
        </div>
    </div>
</div>
<script>
function togglePw(){
    const i=document.getElementById('pw'),ic=document.getElementById('eyeIcon');
    if(i.type==='password'){i.type='text';ic.classList.replace('fa-eye','fa-eye-slash')}
    else{i.type='password';ic.classList.replace('fa-eye-slash','fa-eye')}
}
</script>
</body>
</html>