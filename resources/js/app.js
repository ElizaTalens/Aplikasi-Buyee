import './bootstrap';

// =================================================
// BARU: Dots Indicator untuk “Top Electronics Brands”
// =================================================
document.addEventListener("DOMContentLoaded", () => {
  const row = document.getElementById("brandRow");
  const dotsWrap = document.getElementById("brandDots");
  if (!row || !dotsWrap) return;

  const cards = row.querySelectorAll("a[data-card]");
  if (!cards.length) return;

  // Bikin dots otomatis sesuai jumlah kartu
  cards.forEach((_, i) => {
    const dot = document.createElement("span");
    dot.className = "h-2 w-2 rounded-full bg-gray-300 transition-colors cursor-pointer";
    dot.dataset.index = i;
    dotsWrap.appendChild(dot);
  });

  const dots = dotsWrap.querySelectorAll("span");
  if (!dots.length) return;

  const gapPx = 20; // Sesuai dengan class 'gap-5'
  const cardWidth = cards[0].offsetWidth + gapPx;

  function updateDots() {
    const i = Math.floor((row.scrollLeft + cardWidth / 2) / cardWidth);
    dots.forEach((d, idx) => {
      d.classList.toggle("bg-pink-600", idx === i);
      d.classList.toggle("bg-gray-300", idx !== i);
    });
  }

  // Klik dot → pindah kartu
  dots.forEach(dot => {
    dot.addEventListener("click", () => {
      const idx = parseInt(dot.dataset.index);
      row.scrollTo({ left: idx * cardWidth, behavior: "smooth" });
    });
  });

  row.addEventListener("scroll", () => requestAnimationFrame(updateDots));
  updateDots(); // Panggil sekali saat inisialisasi
});


// =================================================
// Fungsionalitas Tab untuk Homepage (New Arrival & Bestseller)
// =================================================
const tabSection = document.querySelector('.tab-button');
if (tabSection) {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.dataset.tab;

            tabButtons.forEach(btn => {
                btn.classList.remove('text-gray-900', 'font-semibold', 'after:bg-gray-900', 'relative', 'after:absolute', 'after:inset-x-0', 'after:-bottom-[1px]', 'after:h-0.5');
                btn.classList.add('text-gray-500');
            });

            button.classList.add('text-gray-900', 'font-semibold', 'relative', 'after:absolute', 'after:inset-x-0', 'after:-bottom-[1px]', 'after:h-0.5', 'after:bg-gray-900');
            button.classList.remove('text-gray-500');

            tabContents.forEach(content => {
                content.style.display = 'none';
            });

            const targetContent = document.getElementById('tab-content-' + targetTab);
            if (targetContent) {
                targetContent.style.display = 'grid';
            }
        });
    });
}


// =================================================
// Browse by Category Scroller
// =================================================
function initCategoryScroller() {
  const track = document.getElementById('cat-track');
  const prev  = document.getElementById('cat-prev');
  const next  = document.getElementById('cat-next');

  if (!track) return;

  const step = () => Math.max(track.clientWidth * 0.9, 280);

  const updateButtons = () => {
    const max = track.scrollWidth - track.clientWidth - 2;
    if (prev) prev.disabled = track.scrollLeft <= 0;
    if (next) next.disabled = track.scrollLeft >= max;
  };

  next && next.addEventListener('click', () => {
    track.scrollBy({ left: step(), behavior: 'smooth' });
    setTimeout(updateButtons, 300);
  });

  prev && prev.addEventListener('click', () => {
    track.scrollBy({ left: -step(), behavior: 'smooth' });
    setTimeout(updateButtons, 300);
  });

  track.addEventListener('scroll', updateButtons);

  track.addEventListener('wheel', (e) => {
    if (Math.abs(e.deltaX) < Math.abs(e.deltaY)) {
      track.scrollLeft += e.deltaY;
      e.preventDefault();
    }
  }, { passive: false });

  updateButtons();
}
// Panggil fungsi scroller
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCategoryScroller);
} else {
  initCategoryScroller();
}


// =================================================
// Helper & Fungsionalitas Global (Wishlist, Cart, Toast)
// =================================================
const ls = {
  get(key, fallback) {
    try { return JSON.parse(localStorage.getItem(key)) ?? fallback; }
    catch { return fallback; }
  },
  set(key, val) { localStorage.setItem(key, JSON.stringify(val)); }
};

function updateCount(id, count) {
  const el = document.getElementById(id);
  if (!el) return;
  if (!count || count <= 0) { el.classList.add('hidden'); el.textContent = ''; return; }
  el.textContent = count;
  el.classList.remove('hidden');
}

function toast(msg, type = 'ok') {
  const t = document.createElement('div');
  t.textContent = msg;
  t.className =
    'fixed left-1/2 top-6 -translate-x-1/2 z-[9999] rounded-md px-4 py-2 text-sm font-semibold shadow ' +
    (type === 'ok' ? 'bg-black text-white' : 'bg-rose-600 text-white');
  document.body.appendChild(t);
  setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; }, 1600);
  setTimeout(() => t.remove(), 2000);
}

function upsert(list, item, keyFn) {
  const key = keyFn(item);
  const idx = list.findIndex(x => keyFn(x) === key);
  if (idx === -1) list.push(item);
  else {
    if ('qty' in list[idx]) list[idx].qty += (item.qty ?? 1);
  }
  return list;
}

function getVariantKey(x) {
  return `${x.sku}__${x.color ?? ''}__${x.size ?? ''}`;
}


// =================================================
// Inisialisasi & Event Listeners Utama
// =================================================
document.addEventListener('DOMContentLoaded', () => {
  // Inisialisasi jumlah wishlist & cart saat halaman dimuat
  updateCount('wishlistCount', (ls.get('wishlist', [])).length);
  updateCount('cartCount', (ls.get('cart', [])).reduce((a, b) => a + (b.qty ?? 1), 0));

  // --- Logic untuk Halaman Detail Produk ---
  const btnWish = document.getElementById('btnWishlist');
  const btnCart = document.getElementById('btnCart');
  const colorInput = document.getElementById('colorInput');
  const sizeInput  = document.getElementById('sizeInput');

  if (btnWish) {
    btnWish.addEventListener('click', () => {
      const item = {
        sku:   btnWish.dataset.sku,
        name:  btnWish.dataset.name,
        price: Number(btnWish.dataset.price || 0),
        image: btnWish.dataset.image,
      };
      const wish = ls.get('wishlist', []);
      const exists = wish.find(w => w.sku === item.sku);
      if (exists) {
        toast('Item sudah ada di wishlist!', 'warning');
        return;
      }
      wish.push(item);
      ls.set('wishlist', wish);
      updateCount('wishlistCount', wish.length);
      toast('Produkmu berhasil ditambahkan ke wishlist');
    });
  }

  if (btnCart) {
    btnCart.addEventListener('click', () => {
      const item = {
        sku:   btnCart.dataset.sku,
        name:  btnCart.dataset.name,
        price: Number(btnCart.dataset.price || 0),
        color: colorInput?.value || null,
        size:  sizeInput?.value || null,
        image: btnCart.dataset.image,
        qty:   1,
      };
      const cart = ls.get('cart', []);
      upsert(cart, item, getVariantKey);
      ls.set('cart', cart);
      const totalQty = cart.reduce((a, b) => a + (b.qty ?? 1), 0);
      updateCount('cartCount', totalQty);
      toast('Produkmu berhasil ditambahkan ke cart');
    });
  }

  // --- Logic untuk Tombol Wishlist Global (di semua halaman) ---
  if (!window.__wishlistClickBound) {
    window.__wishlistClickBound = true;
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.wishlist-btn') || e.target.closest('[aria-label="Like"]');
      if (!btn) return;
      e.preventDefault();
      e.stopPropagation();

      const card = btn.closest('a.group') || btn.closest('.group');
      let sku = btn.dataset.sku || card?.dataset.sku;
      if (!sku) {
        const href = card?.href || card?.querySelector('a')?.href;
        if (href) sku = href.split('/').filter(Boolean).pop();
      }
      if (!sku) return;

      const wish = ls.get('wishlist', []);
      const exists = wish.find(x => x.sku === sku);
      
      if (exists) {
        // Jika sudah ada, hapus dari wishlist
        const newWish = wish.filter(x => x.sku !== sku);
        ls.set('wishlist', newWish);
        updateCount('wishlistCount', newWish.length);
        toast('Item dihapus dari wishlist', 'ok');
        const icon = btn.querySelector('i');
        if (icon) {
          icon.classList.remove('fa-solid');
          icon.classList.add('fa-regular');
          btn.classList.remove('text-rose-500');
        }
      } else {
        // Jika belum ada, tambahkan ke wishlist
        const name  = btn.dataset.name || card?.dataset.name || card?.querySelector('h3')?.textContent.trim();
        const price = Number(btn.dataset.price || card?.dataset.price || (card?.querySelector('[data-price]')?.textContent.replace(/[^0-9]/g, '')) || 0);
        const image = btn.dataset.image || card?.dataset.image || card?.querySelector('img')?.src;
        
        wish.push({ sku, name, price, image });
        ls.set('wishlist', wish);
        updateCount('wishlistCount', wish.length);
        toast('Produk berhasil ditambahkan ke wishlist', 'ok');
        const icon = btn.querySelector('i');
        if (icon) {
          icon.classList.remove('fa-regular');
          icon.classList.add('fa-solid');
          btn.classList.add('text-rose-500');
        }
      }
    });
  }

  // Inisialisasi state tombol wishlist yang ada di halaman
  document.querySelectorAll('.wishlist-btn, [aria-label="Like"]').forEach(btn => {
      const card = btn.closest('a.group') || btn.closest('.group');
      let sku = btn.dataset.sku || card?.dataset.sku;
      if (!sku) {
          const href = card?.href || card?.querySelector('a')?.href;
          if (href) sku = href.split('/').filter(Boolean).pop();
      }
      if (!sku) return;
      
      const wish = ls.get('wishlist', []);
      const exists = wish.some(x => x.sku === sku);
      const icon = btn.querySelector('i');
      if (icon) {
          if (exists) {
              icon.classList.remove('fa-regular');
              icon.classList.add('fa-solid');
              btn.classList.add('text-rose-500');
          } else {
              icon.classList.remove('fa-solid');
              icon.classList.add('fa-regular');
              btn.classList.remove('text-rose-500');
          }
      }
  });


  // --- Logic untuk halaman lain (Cart, Catalog, Detail) ---
  if (document.querySelector('[data-cart]')) {
    // Fungsi untuk halaman Cart
    const cartRoot = document.querySelector('[data-cart]');
    const fmt = n => `$${Number(n).toFixed(0)}`;
    function recalc() {
      // ... (logika kalkulasi cart)
    }
    recalc();
  }

  if (document.getElementById('catalogHeading')) {
    // Fungsi untuk halaman Catalog
  }

  if (document.getElementById('colorGroup')) {
    // Fungsi untuk halaman Detail Produk (Pilihan Warna & Ukuran)
    initChoiceGroup({ group: '#colorGroup', /* ... */ });
    initChoiceGroup({ group: '#sizeGroup', /* ... */ });
  }

});