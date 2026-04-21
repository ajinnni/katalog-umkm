/* ============================================
   TOKOKU — main.js
   ============================================ */

const BASE_URL = document.querySelector('meta[name="base-url"]')?.content ?? '';

/* ---- QTY BUTTONS (landing page) ---- */
document.querySelectorAll('.form-keranjang').forEach(form => {
    const minus = form.querySelector('.qty-btn.minus');
    const plus  = form.querySelector('.qty-btn.plus');
    const input = form.querySelector('.qty-input');

    minus?.addEventListener('click', () => {
        const v = parseInt(input.value);
        if (v > 1) input.value = v - 1;
    });

    plus?.addEventListener('click', () => {
        const max = parseInt(input.max) || 99;
        const v   = parseInt(input.value);
        if (v < max) input.value = v + 1;
    });
});

/* ---- AJAX Tambah Keranjang ---- */
document.querySelectorAll('.form-keranjang').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const data = new FormData(this);

        try {
            const res  = await fetch(this.action, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const json = await res.json();
            if (json.status === 'ok') {
                document.getElementById('cartCount').textContent = json.total;
                // Animasi badge
                const badge = document.getElementById('cartCount');
                badge.classList.add('bounce');
                setTimeout(() => badge.classList.remove('bounce'), 400);

                showToast('Produk ditambahkan ke keranjang!');
            }
        } catch (err) {
            form.submit(); // fallback
        }
    });
});

/* ---- QTY UPDATE (halaman keranjang) ---- */
document.querySelectorAll('.keranjang-row').forEach(row => {
    const id    = row.dataset.id;
    const minus = row.querySelector('.qty-btn.minus');
    const plus  = row.querySelector('.qty-btn.plus');
    const span  = row.querySelector('.qty-val');

    function updateQty(newQty) {
        fetch(`${BASE_URL}user/update_keranjang`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id_produk=${id}&qty=${newQty}`
        })
        .then(r => r.json())
        .then(json => {
            if (json.status === 'ok') {
                document.getElementById('cartCount').textContent = json.total_item;
                document.getElementById('grandTotal').textContent = 'Rp ' + json.grand_total.toLocaleString('id-ID');
                document.getElementById('totalItem').textContent = json.total_item + ' item';
                // update subtotal baris
                const hargaEl = row.querySelector('.item-harga');
                if (hargaEl) {
                    const harga = parseInt(hargaEl.textContent.replace(/\D/g,''));
                    const sub   = row.querySelector('.item-subtotal');
                    if (sub) sub.textContent = 'Rp ' + (harga * newQty).toLocaleString('id-ID');
                }

                if (newQty <= 0) row.remove();
            }
        })
        .catch(() => window.location.reload());
    }

    minus?.addEventListener('click', () => {
        let v = parseInt(span?.textContent || 1);
        v = Math.max(0, v - 1);
        if (span) span.textContent = v;
        updateQty(v);
    });

    plus?.addEventListener('click', () => {
        let v = parseInt(span?.textContent || 1);
        v++;
        if (span) span.textContent = v;
        updateQty(v);
    });
});

/* ---- TOAST ---- */
function showToast(msg) {
    let toast = document.getElementById('toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        toast.style.cssText = `
            position:fixed; bottom:28px; left:50%; transform:translateX(-50%) translateY(60px);
            background:#1c1610; color:#fff; padding:12px 24px; border-radius:12px;
            font-family:'DM Sans',sans-serif; font-size:.9rem; font-weight:500;
            z-index:9999; transition:transform .3s, opacity .3s; opacity:0;
            box-shadow:0 8px 24px rgba(0,0,0,.2);
        `;
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(60px)';
    }, 2500);
}

/* ---- Bounce animation CSS inject ---- */
const style = document.createElement('style');
style.textContent = `
    @keyframes bounce { 0%,100%{transform:scale(1)} 50%{transform:scale(1.4)} }
    .bounce { animation: bounce .4s ease; }
`;
document.head.appendChild(style);

/* ---- PROFILE DROPDOWN TOGGLE ---- */
const profileBtn  = document.getElementById('profileBtn');
const profileWrap = document.getElementById('profileWrap');

if (profileBtn && profileWrap) {
    profileBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        profileWrap.classList.toggle('open');
    }, true); // ← tambah true (capture phase)

    document.addEventListener('click', function(e) {
        if (profileWrap && !profileWrap.contains(e.target)) {
            profileWrap.classList.remove('open');
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') profileWrap.classList.remove('open');
    });
}