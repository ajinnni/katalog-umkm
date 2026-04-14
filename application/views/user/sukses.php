<section class="page-header">
    <div class="page-header-inner">
        <h2 class="page-title">Pesanan Berhasil!</h2>
    </div>
</section>

<section class="keranjang-section" style="text-align:center;max-width:600px;margin:auto;padding:60px 5%">
    <div style="font-size:72px;margin-bottom:16px">🎉</div>
    <h3 style="font-family:'Playfair Display',serif;font-size:1.8rem;margin-bottom:8px">Terima kasih!</h3>
    <p style="color:var(--mid);margin-bottom:40px">
        Pesanan kamu sudah kami catat. Silakan hubungi penjual via WhatsApp untuk konfirmasi.
    </p>

    <?php foreach ($wa_links as $wa): ?>
    <div style="background:var(--white);border-radius:var(--radius);padding:24px;margin-bottom:16px;box-shadow:var(--shadow);text-align:left">
        <p style="font-weight:600;margin-bottom:4px"><?= htmlspecialchars($wa['toko']) ?></p>
        <p style="font-size:.82rem;color:var(--mid);margin-bottom:16px">Kode: <?= $wa['kode'] ?></p>
        <a href="<?= $wa['link'] ?>" target="_blank" class="btn-wa"
           style="display:inline-flex;align-items:center;gap:10px;padding:12px 24px;
                  background:var(--wa);color:#fff;border-radius:var(--radius);
                  text-decoration:none;font-weight:600;font-size:.95rem">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.556 4.123 1.529 5.856L.057 23.885l6.154-1.612A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.894a9.869 9.869 0 01-5.032-1.378l-.361-.214-3.741.981.998-3.648-.235-.374A9.861 9.861 0 012.106 12C2.106 6.58 6.58 2.106 12 2.106S21.894 6.58 21.894 12 17.42 21.894 12 21.894z"/>
            </svg>
            Hubungi <?= htmlspecialchars($wa['toko']) ?>
        </a>
    </div>
    <?php endforeach; ?>

    <a href="<?= site_url('index.php/user') ?>" class="btn-outline" style="margin-top:24px;display:inline-block">
        Kembali Belanja
    </a>
</section>