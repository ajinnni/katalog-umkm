<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi OTP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1cc88a, #13855c);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,.25);
        }
        .header {
            background: #1cc88a;
            color: #fff;
            text-align: center;
            padding: 24px;
        }
        .header i { font-size: 40px; margin-bottom: 10px; display: block; }
        .header h2 { margin: 0; font-size: 20px; font-weight: 700; }
        .header p { margin: 5px 0 0; font-size: 13px; opacity: .85; }
        .body { padding: 28px; }

        /* Alert */
        .alert {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .alert-danger  { background: #fde8e8; color: #c0392b; border-left: 4px solid #e74c3c; }
        .alert-warning { background: #fff8e1; color: #856404; border-left: 4px solid #ffc107; }
        .alert-info    { background: #e8f4fd; color: #1a6fa8; border-left: 4px solid #3498db; }
        .alert-success { background: #e8fdf4; color: #1a7a4a; border-left: 4px solid #1cc88a; }

        /* OTP Box - kotak besar tampilkan kode */
        .otp-display {
            background: linear-gradient(135deg, #f8fff9, #e8fdf4);
            border: 2px dashed #1cc88a;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .otp-display label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
        }
        .otp-code {
            font-size: 42px;
            font-weight: 900;
            letter-spacing: 12px;
            color: #13855c;
            font-family: monospace;
            line-height: 1;
        }
        .otp-expire {
            font-size: 12px;
            color: #888;
            margin-top: 8px;
        }

        /* Nomor info */
        .wa-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #555;
        }
        .wa-info strong { color: #1cc88a; }

        /* Input OTP */
        .otp-input {
            width: 100%;
            padding: 14px;
            font-size: 32px;
            font-weight: 900;
            letter-spacing: 10px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 12px;
            outline: none;
            transition: .2s;
            font-family: monospace;
            color: #333;
        }
        .otp-input:focus { border-color: #1cc88a; box-shadow: 0 0 0 3px rgba(28,200,138,.15); }

        .hint { font-size: 12px; color: #aaa; text-align: center; margin-top: 6px; }

        /* Button */
        .btn {
            width: 100%;
            padding: 13px;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 14px;
            transition: .2s;
        }
        .btn-success { background: #1cc88a; color: #fff; }
        .btn-success:hover { background: #17a673; }

        /* Footer links */
        .footer-links {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
        }
        .footer-links a { color: #1cc88a; text-decoration: none; font-weight: 600; }
        .footer-links a.logout { color: #aaa; font-weight: 400; }
        .footer-links span { color: #ddd; margin: 0 8px; }

        /* Countdown */
        #resendCountdown { color: #aaa; font-size: 13px; }
        #resendBtn { display: none; }
    </style>
</head>
<body>
<div class="card">
    <div class="header">
        <i class="fas fa-shield-alt"></i>
        <h2>Verifikasi OTP</h2>
        <p>Masukkan kode untuk melanjutkan</p>
    </div>
    <div class="body">

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('info')): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <?= $this->session->flashdata('info') ?>
            </div>
        <?php endif; ?>

        <?php
        // Ambil OTP dari session untuk ditampilkan
        $otp_tampil = $this->session->userdata('otp_plain');
        $no_wa = $this->session->userdata('pending_no_wa');
        $masked = $no_wa ? substr($no_wa, 0, 5) . '****' . substr($no_wa, -4) : '-';
        ?>

        <?php if ($otp_tampil): ?>
        <div class="otp-display">
            <label><i class="fas fa-key"></i> Kode OTP kamu</label>
            <div class="otp-code"><?= $otp_tampil ?></div>
            <div class="otp-expire"><i class="fas fa-clock"></i> Berlaku 5 menit</div>
        </div>
        <?php endif; ?>

        <div class="wa-info">
            <i class="fab fa-whatsapp"></i>
            OTP dikirim ke <strong><?= $masked ?></strong>
        </div>

        <form action="<?= base_url('index.php/proses-otp') ?>" method="POST" id="formOtp">
            <input type="text" name="otp" id="otpInput" class="otp-input"
                   placeholder="------" maxlength="6"
                   required autofocus autocomplete="one-time-code"
                   inputmode="numeric" pattern="[0-9]{6}">
            <div class="hint"><i class="fas fa-magic"></i> Otomatis terkirim saat 6 digit terisi</div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check-circle"></i> Verifikasi Sekarang
            </button>
        </form>

        <div class="footer-links">
            <span id="resendCountdown">
                Kirim ulang dalam <strong id="countdown">60</strong> detik
            </span>
            <a href="<?= base_url('index.php/resend-otp') ?>" id="resendBtn">
                <i class="fas fa-redo"></i> Kirim ulang OTP
            </a>
            <span>|</span>
            <a href="<?= base_url('index.php/logout') ?>" class="logout">Logout</a>
        </div>
    </div>
</div>

<script>
// Auto submit
document.getElementById('otpInput').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value.length === 6) {
        document.getElementById('formOtp').submit();
    }
});

// Countdown 60 detik
(function() {
    let s = 60;
    const cd = document.getElementById('countdown');
    const cdWrap = document.getElementById('resendCountdown');
    const btn = document.getElementById('resendBtn');
    const timer = setInterval(() => {
        s--;
        cd.textContent = s;
        if (s <= 0) {
            clearInterval(timer);
            cdWrap.style.display = 'none';
            btn.style.display = 'inline';
        }
    }, 1000);
})();
</script>
</body>
</html>