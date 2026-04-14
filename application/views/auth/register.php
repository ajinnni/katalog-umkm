<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Verifikasi OTP</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:Segoe UI;background:linear-gradient(135deg,#1cc88a,#13855c);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{width:100%;max-width:420px;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 15px 35px rgba(0,0,0,.25)}
.header{background:#1cc88a;color:#fff;text-align:center;padding:28px 20px}
.header i.big{font-size:40px;margin-bottom:10px;display:block}
.header h2{margin:0;font-size:20px;font-weight:700}
.header p{margin:6px 0 0;font-size:13px;opacity:.85}
.body{padding:28px}
.alert{padding:11px 14px;border-radius:10px;margin-bottom:16px;font-size:13px;display:flex;align-items:flex-start;gap:8px}
.alert-danger{background:#fde8e8;color:#c0392b;border-left:4px solid #e74c3c}
.alert-warning{background:#fff8e1;color:#856404;border-left:4px solid #ffc107}
.alert-info{background:#e8f4fd;color:#1a6fa8;border-left:4px solid #3498db}

/* Kotak tampil OTP */
.otp-box{background:linear-gradient(135deg,#f0fff8,#e0faf0);border:2px dashed #1cc88a;border-radius:14px;padding:22px;text-align:center;margin-bottom:22px}
.otp-box-label{font-size:11px;color:#666;text-transform:uppercase;letter-spacing:1.5px;font-weight:700;margin-bottom:10px}
.otp-code{font-size:44px;font-weight:900;letter-spacing:14px;color:#13855c;font-family:monospace;line-height:1}
.otp-expire{font-size:12px;color:#888;margin-top:8px}
.otp-expire i{margin-right:4px}

/* Info nomor */
.wa-info{text-align:center;margin-bottom:20px;font-size:14px;color:#555}
.wa-info i{color:#1cc88a;margin-right:5px}
.wa-info strong{color:#13855c}

/* Input */
.otp-input{width:100%;padding:15px;font-size:34px;font-weight:900;letter-spacing:12px;text-align:center;border:2px solid #ddd;border-radius:12px;outline:none;transition:.2s;font-family:monospace;color:#333;background:#fafafa}
.otp-input:focus{border-color:#1cc88a;box-shadow:0 0 0 3px rgba(28,200,138,.15);background:#fff}
.hint{font-size:12px;color:#bbb;text-align:center;margin-top:7px}

/* Button */
.btn{width:100%;padding:13px;font-size:15px;font-weight:700;border:none;border-radius:11px;cursor:pointer;margin-top:14px;transition:.2s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn-success{background:#1cc88a;color:#fff}
.btn-success:hover{background:#17a673}

/* Footer */
.footer-links{text-align:center;margin-top:20px;font-size:13px;display:flex;align-items:center;justify-content:center;gap:10px}
.footer-links a{color:#1cc88a;text-decoration:none;font-weight:600}
.footer-links a:hover{text-decoration:underline}
.footer-links .sep{color:#ddd}
.footer-links .logout-link{color:#bbb;font-weight:400}
#resendCountdown{color:#aaa;font-size:13px}
#resendBtn{display:none}
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <i class="fas fa-shield-alt big"></i>
        <h2>Verifikasi Akun</h2>
        <p>Masukkan kode OTP untuk aktivasi</p>
    </div>
    <div class="body">

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= $this->session->flashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('info')): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <span><?= $this->session->flashdata('info') ?></span>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('warning')): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?= $this->session->flashdata('warning') ?></span>
            </div>
        <?php endif; ?>

        <?php
        $otp_tampil = $this->session->userdata('otp_plain');
        $no_wa      = $this->session->userdata('no_wa');
        $masked     = $no_wa ? substr($no_wa, 0, 5) . '****' . substr($no_wa, -4) : '-';
        ?>

        <?php if ($otp_tampil): ?>
        <div class="otp-box">
            <div class="otp-box-label"><i class="fas fa-key"></i> Kode OTP Kamu</div>
            <div class="otp-code"><?= $otp_tampil ?></div>
            <div class="otp-expire"><i class="fas fa-clock"></i> Berlaku 5 menit</div>
        </div>
        <?php endif; ?>

        <div class="wa-info">
            <i class="fab fa-whatsapp"></i>
            OTP juga dikirim ke <strong><?= $masked ?></strong>
        </div>

        <form action="<?= base_url('index.php/proses-otp') ?>" method="POST" id="formOtp">
            <input type="text" name="otp" id="otpInput" class="otp-input"
                   placeholder="------" maxlength="6"
                   required autofocus autocomplete="one-time-code"
                   inputmode="numeric" pattern="[0-9]{6}">
            <div class="hint"><i class="fas fa-magic"></i> Otomatis terkirim saat 6 digit terisi</div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check-circle"></i> Verifikasi Akun
            </button>
        </form>

        <div class="footer-links">
            <span id="resendCountdown">
                Kirim ulang dalam <strong id="countdown">60</strong>s
            </span>
            <a href="<?= base_url('index.php/resend-otp') ?>" id="resendBtn">
                <i class="fas fa-redo"></i> Kirim Ulang OTP
            </a>
            <span class="sep">|</span>
            <a href="<?= base_url('index.php/logout') ?>" class="logout-link">Logout</a>
        </div>
    </div>
</div>
<script>
document.getElementById('otpInput').addEventListener('input',function(){
    this.value=this.value.replace(/[^0-9]/g,'');
    if(this.value.length===6) document.getElementById('formOtp').submit();
});
(function(){
    let s=60;
    const cd=document.getElementById('countdown');
    const cdW=document.getElementById('resendCountdown');
    const btn=document.getElementById('resendBtn');
    const t=setInterval(()=>{
        s--;cd.textContent=s;
        if(s<=0){clearInterval(t);cdW.style.display='none';btn.style.display='inline'}
    },1000);
})();
</script>
</body>
</html>