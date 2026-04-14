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
.header{background:#4e73df;color:#fff;text-align:center;padding:22px}
.header h2{margin:0;font-size:20px}
.header p{margin:5px 0 0;font-size:13px;opacity:.85}
.body{padding:25px}
.group{margin-bottom:16px}
label{display:block;font-size:13px;font-weight:600;margin-bottom:6px}
.input{width:100%;padding:12px 14px;border:1px solid #ddd;border-radius:10px;outline:none;transition:.2s;font-size:14px}
.input:focus{border-color:#4e73df;box-shadow:0 0 0 3px rgba(78,115,223,.15)}
.password-box{position:relative}
.eye{position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#666}
.btn{width:100%;padding:12px;background:#4e73df;border:none;color:#fff;font-weight:bold;border-radius:10px;cursor:pointer;transition:.2s;font-size:14px;margin-top:4px}
.btn:hover{background:#2e59d9}
.footer{text-align:center;margin-top:16px;font-size:13px}
.footer a{color:#4e73df;font-weight:bold;text-decoration:none}
.alert{padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:13px;border-left:4px solid #e74c3c;background:#fde8e8;color:#c0392b}
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h2><i class="fa fa-store"></i> UMKM Catalog</h2>
        <p>Masuk ke akun kamu</p>
    </div>
    <div class="body">

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert">
                <i class="fa fa-exclamation-circle"></i>
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('index.php/proses-login') ?>" method="POST">
            <div class="group">
                <label>No WhatsApp / Username</label>
                <input type="text" name="identifier" class="input"
                       placeholder="08xxx atau username" required autofocus>
            </div>
            <div class="group">
                <label>Password</label>
                <div class="password-box">
                    <input type="password" id="login_password" name="password"
                           class="input" placeholder="Password kamu" required>
                    <span class="eye" onclick="toggleLogin()">
                        <i class="fa fa-eye" id="eyeLogin"></i>
                    </span>
                </div>
            </div>
            <button class="btn">
                <i class="fa fa-sign-in-alt"></i> Masuk
            </button>
        </form>

        <div class="footer">
            Belum punya akun?
            <a href="<?= base_url('index.php/register') ?>">Daftar di sini</a>
        </div>
    </div>
</div>
<script>
function toggleLogin(){
    const i=document.getElementById("login_password");
    const icon=document.getElementById("eyeLogin");
    if(i.type==="password"){i.type="text";icon.classList.replace("fa-eye","fa-eye-slash")}
    else{i.type="password";icon.classList.replace("fa-eye-slash","fa-eye")}
}
</script>
</body>
</html>