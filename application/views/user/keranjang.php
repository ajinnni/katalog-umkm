<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.keranjang-wrap {
    max-width: 1000px;
    margin: 0 auto;
    padding: 32px 16px 60px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    color: #8B6F47;
    text-decoration: none;
    margin-bottom: 16px;
    font-weight: 600;
}

.keranjang-title {
    font-size: 1.8rem;
    color: #2c1a0e;
    margin-bottom: 20px;
}

/* CARD */
.cart-card {
    background: #fff;
    border-radius: 16px;
    padding: 18px;
    margin-bottom: 14px;
    border: 1px solid #e8ddd0;
    display: flex;
    align-items: center;
    gap: 14px;
}

.cart-img {
    width: 70px;
    height: 70px;
    border-radius: 10px;
    object-fit: cover;
    background: #f0e8dc;
}

.cart-info {
    flex: 1;
}

.cart-name {
    font-weight: 600;
    color: #2c1a0e;
}

.cart-price {
    font-size: 0.85rem;
    color: #7a6652;
}

/* QTY */
.qty-box {
    display: flex;
    align-items: center;
    gap: 6px;
}

.qty-btn {
    width: 28px;
    height: 28px;
    border: none;
    background: #f0e8dc;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
}

.qty-val {
    min-width: 20px;
    text-align: center;
}

/* HAPUS */
.btn-hapus {
    color: #c0392b;
    font-size: 0.8rem;
    text-decoration: none;
}

/* TOTAL */
.summary-box {
    background: #2c1a0e;
    color: #fff;
    padding: 20px;
    border-radius: 16px;
    margin-top: 20px;
}

.btn-checkout {
    width: 100%;
    margin-top: 15px;
    padding: 14px;
    background: #8B6F47;
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: bold;
    cursor: pointer;
}
</style>

<div class="keranjang-wrap">

<a href="<?= site_url('user') ?>" class="back-link">← Kembali Belanja</a>

<h2 class="keranjang-title">Keranjang Belanja</h2>

<?php if (empty($keranjang)): ?>

    <p>Keranjang kosong.</p>

<?php else: ?>

    <?php foreach ($keranjang as $id => $item): ?>
    <div class="cart-card">

        <?php if (!empty($item['foto'])): ?>
            <img src="<?= base_url('uploads/produk/'.$item['foto']) ?>" class="cart-img">
        <?php else: ?>
            <div class="cart-img"></div>
        <?php endif; ?>

        <div class="cart-info">
            <div class="cart-name"><?= $item['nama'] ?></div>
            <div class="cart-price">Rp <?= number_format($item['harga']) ?></div>
        </div>

        <div class="qty-box">
            <button class="qty-btn" onclick="updateQty('<?= $id ?>','kurang')">-</button>
            <div class="qty-val"><?= $item['qty'] ?></div>
            <button class="qty-btn" onclick="updateQty('<?= $id ?>','tambah')">+</button>
        </div>

        <a href="<?= site_url('user/hapus_keranjang/'.$id) ?>" class="btn-hapus">Hapus</a>
    </div>
    <?php endforeach; ?>

    <div class="summary-box">
        <strong>Total: Rp <?= number_format($grand_total) ?></strong>

        <form action="<?= site_url('order/checkout') ?>" method="GET">
            <button class="btn-checkout">Checkout</button>
        </form>
    </div>

<?php endif; ?>

</div>

<script>
function updateQty(id, aksi) {
    fetch('<?= site_url('user/update_keranjang') ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id=${id}&aksi=${aksi}&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>`
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) location.reload();
    });
}
</script>