<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register UMKM</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{box-sizing:border-box}
body{margin:0;font-family:Segoe UI;background:linear-gradient(135deg,#4e73df,#224abe);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{width:100%;max-width:460px;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 15px 35px rgba(0,0,0,.25)}
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
.progress{height:5px;background:#eee;border-radius:10px;overflow:hidden;margin-top:6px}
.bar{height:100%;width:0%;transition:.3s;border-radius:10px}
.role{display:flex;gap:12px}
.role-item{flex:1}
.role-item input{display:none}
.role-label{display:block;padding:12px;text-align:center;border:1.5px solid #ddd;border-radius:10px;cursor:pointer;transition:.2s;font-size:13px;font-weight:500}
.role-label i{display:block;font-size:18px;margin-bottom:5px;color:#aaa}
.role-item input:checked+.role-label{border-color:#4e73df;background:rgba(78,115,223,.08)}
.role-item input:checked+.role-label i{color:#4e73df}
.btn{width:100%;padding:13px;border:none;border-radius:10px;background:#1cc88a;color:#fff;font-weight:700;cursor:pointer;font-size:14px;margin-top:4px;transition:.2s}
.btn:hover{background:#17a673}
.footer{text-align:center;margin-top:16px;font-size:13px;color:#666}
.footer a{color:#4e73df;font-weight:700;text-decoration:none}
.alert{padding:11px 14px;border-radius:8px;margin-bottom:16px;font-size:13px;border-left:4px solid #e74c3c;background:#fde8e8;color:#c0392b;display:flex;gap:8px;align-items:center}
small{font-size:12px;color:#999}
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <i class="fas fa-store"></i>
        <h2>UMKM Catalog</h2>
        <p>Buat akun baru</p>
    </div>
    <div class="body">

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert">
                <i class="fas fa-exclamation-circle"></i>
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('index.php/proses-register') ?>" method="POST">
            <div class="group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="input" placeholder="Nama lengkap kamu" required>
            </div>
            <div class="group">
                <label>Nomor WhatsApp</label>
                <input type="text" name="no_wa" class="input" placeholder="08xxxxxxxxxx" required>
                <small>Digunakan untuk login & verifikasi OTP</small>
            </div>
            <div class="group">
                <label>Password</label>
                <div class="pw-box">
                    <input type="password" id="pw" name="password" class="input"
                           placeholder="Min 8 karakter" minlength="8" required>
                    <span class="eye" onclick="togglePw()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
                <div class="progress"><div id="bar" class="bar"></div></div>
                <small id="strength"></small>
            </div>
            <div class="group">
                <label>Daftar sebagai</label>
                <div class="role">
                    <div class="role-item">
                        <input type="radio" id="r_user" name="role" value="user" checked>
                        <label class="role-label" for="r_user">
                            <i class="fas fa-user"></i> Pembeli
                        </label>
                    </div>
                    <div class="role-item">
                        <input type="radio" id="r_umkm" name="role" value="umkm">
                        <label class="role-label" for="r_umkm">
                            <i class="fas fa-store"></i> Pemilik UMKM
                        </label>
                    </div>
                </div>
            </div>
            <button class="btn">
                <i class="fas fa-user-plus"></i> Daftar & Verifikasi WA
            </button>
        </form>

        <div class="footer">
            Sudah punya akun?
            <a href="<?= base_url('index.php/login') ?>">Login di sini</a>
        </div>
    </div>
</div>
<script>
function togglePw(){
    const i=document.getElementById('pw'),ic=document.getElementById('eyeIcon');
    if(i.type==='password'){i.type='text';ic.classList.replace('fa-eye','fa-eye-slash')}
    else{i.type='password';ic.classList.replace('fa-eye-slash','fa-eye')}
}
document.getElementById('pw').addEventListener('input',function(){
    const v=this.value,bar=document.getElementById('bar'),txt=document.getElementById('strength');
    let s=0;
    if(v.length>=8)s++;if(/[A-Z]/.test(v))s++;if(/[0-9]/.test(v))s++;if(/[^A-Za-z0-9]/.test(v))s++;
    const l=[['25%','#e74a3b','Lemah'],['50%','#f6c23e','Cukup'],['75%','#36b9cc','Kuat'],['100%','#1cc88a','Sangat Kuat']];
    const d=l[Math.max(0,s-1)];
    bar.style.width=v?d[0]:'0%';bar.style.background=v?d[1]:'#eee';txt.innerText=v?d[2]:'';
});
</script>
</body>
</html>