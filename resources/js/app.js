// Dots indicator untuk “Top Electronics Brands”
document.addEventListener("DOMContentLoaded", () => {
  const row = document.getElementById("brandRow");
  const dotsWrap = document.getElementById("brandDots");
  if (!row || !dotsWrap) return;

  const cards = row.querySelectorAll("a[data-card]");
  if (!cards.length) return;

  // bikin dots otomatis sesuai jumlah kartu
  cards.forEach((_, i) => {
    const dot = document.createElement("span");
    dot.className = "h-2 w-2 rounded-full bg-gray-300 transition-colors cursor-pointer";
    dot.dataset.index = i;
    dotsWrap.appendChild(dot);
  });

// Wishlist functionality - Global scope
let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');

// Update wishlist count in navbar
function updateWishlistCount() {
  console.log('Updating wishlist count, current wishlist:', wishlist);
  const countEl = document.querySelector('#wishlistCount');
  console.log('Counter element found:', countEl);
  
  if (countEl) {
    const count = wishlist.length;
    console.log('Wishlist count:', count);
    
    if (count > 0) {
      countEl.textContent = count;
      countEl.classList.remove('hidden');
      console.log('Counter shown with value:', count);
    } else {
      countEl.textContent = '';
      countEl.classList.add('hidden');
      console.log('Counter hidden');
    }
  } else {
    console.error('Wishlist counter element not found!');
  }
}

// Add to wishlist function
function addToWishlist(item) {
  console.log('addToWishlist called with:', item);
  
  // Check if item already exists
  const exists = wishlist.find(w => w.sku === item.sku);
  if (exists) {
    toast('Item sudah ada di wishlist!', 'warning');
    return;
  }
  
  wishlist.push({
    sku: item.sku,
    name: item.name,
    price: parseFloat(item.price),
    image: item.image,
    addedAt: new Date().toISOString()
  });
  
  localStorage.setItem('wishlist', JSON.stringify(wishlist));
  console.log('Wishlist after adding:', wishlist);
  
  updateWishlistCount();
  toast('Item berhasil ditambahkan ke wishlist!', 'ok');
  
  // Update button state
  updateWishlistButtonState(item.sku, true);
}

// Remove from wishlist function
function removeFromWishlist(sku) {
  wishlist = wishlist.filter(item => item.sku !== sku);
  localStorage.setItem('wishlist', JSON.stringify(wishlist));
  updateWishlistCount();
  toast('Item dihapus dari wishlist!', 'ok');
  
  // Update button state
  updateWishlistButtonState(sku, false);
}

// Update wishlist button state
function updateWishlistButtonState(sku, isInWishlist) {
  const buttons = document.querySelectorAll(`[data-sku="${sku}"]`);
  buttons.forEach(btn => {
    const icon = btn.querySelector('i');
    if (icon) {
      if (isInWishlist) {
        icon.className = 'fa-solid fa-heart mr-2';
        btn.classList.add('text-red-500');
        btn.title = 'Remove from Wishlist';
      } else {
        icon.className = 'fa-regular fa-heart mr-2';
        btn.classList.remove('text-red-500');
        btn.title = 'Add to Wishlist';
      }
    }
  });
}

// Initialize wishlist buttons
function initWishlistButtons() {
  document.querySelectorAll('.wishlist-btn, #btnWishlist, [aria-label="Like"]').forEach(btn => {
    // Ambil SKU dari data-attr atau derive dari link
    let sku = btn.dataset.sku;
    if (!sku) {
      const card = btn.closest('a.group') || btn.closest('.group');
      const href = card?.href || card?.querySelector('a')?.href;
      if (href) {
        try {
          const url = new URL(href, window.location.origin);
          sku = url.pathname.split('/').filter(Boolean).pop();
        } catch {}
      }
    }
    if (!sku) return; // tanpa SKU, abaikan
    const isInWishlist = wishlist.some(item => item.sku === sku);
    updateWishlistButtonState(sku, isInWishlist);
  });
}

// Wishlist functionality initialization
document.addEventListener('DOMContentLoaded', () => {
  console.log('Wishlist functionality loaded');
  
  // Handle wishlist button clicks (exclude #btnWishlist in product detail page)
  document.addEventListener('click', (e) => {
    // Ignore .wishlist-btn — handled by DB-based toggle
    const clickedBtn = e.target.closest('#btnWishlist') || e.target.closest('[aria-label="Like"]');
    
    // Skip if it's #btnWishlist in product detail page (has onclick attribute)
    if (clickedBtn && clickedBtn.id === 'btnWishlist' && clickedBtn.hasAttribute('onclick')) {
      return; // Let the onclick attribute handle it
    }
    
    if (clickedBtn) {
      e.preventDefault();
      e.stopPropagation();

      console.log('Wishlist button clicked');

      const btn = clickedBtn;
      // Coba ambil dari data attribute
      let sku = btn.dataset.sku;
      let name = btn.dataset.name;
      let price = btn.dataset.price;
      let image = btn.dataset.image;

      // Jika tidak ada, derive dari card
      if (!sku || !name || !price || !image) {
        const card = btn.closest('a.group') || btn.closest('.group');
        const href = card?.href || card?.querySelector('a')?.href;
        if (href && !sku) {
          try {
            const url = new URL(href, window.location.origin);
            sku = url.pathname.split('/').filter(Boolean).pop();
          } catch {}
        }
        const imgEl = card?.querySelector('img');
        if (imgEl && !image) image = imgEl.src;

        // ambil teks nama dan harga
        const pEls = card ? Array.from(card.querySelectorAll('p')) : [];
        if (!name) {
          // coba gabungkan brand + produk
          const brand = pEls[0]?.textContent?.trim();
          const prod  = pEls[1]?.textContent?.trim();
          name = [brand, prod].filter(Boolean).join(' ');
        }
        if (!price) {
          const priceEl = pEls.find(p => /Rp\s*[0-9.]+/i.test(p.textContent || ''));
          const raw = priceEl?.textContent || '';
          const num = (raw.match(/[0-9.]+/g) || []).join('');
          price = num ? Number(num.replace(/\./g, '')) : 0;
        }
      }

      console.log('Button data:', { sku, name, price, image });

      const isInWishlist = wishlist.some(item => item.sku === sku);

      if (isInWishlist) {
        console.log('Removing from wishlist');
        removeFromWishlist(sku);
      } else {
        console.log('Adding to wishlist');
        addToWishlist({ sku, name, price, image });
      }
    }
  });
  
  // Initialize on page load
  updateWishlistCount();
  initWishlistButtons();
  
  // Make functions globally available for wishlist page
  window.wishlistManager = {
    getWishlist: () => wishlist,
    removeFromWishlist,
    updateWishlistCount,
    addToCart: (item) => {
      // Get current cart
      let cart = JSON.parse(localStorage.getItem('cart') || '[]');
      
      // Add item to cart
      const cartItem = {
        sku: item.sku,
        name: item.name,
        price: item.price,
        image: item.image,
        quantity: 1,
        color: 'default',
        size: 'default'
      };
      
      cart = upsert(cart, cartItem, getVariantKey);
      localStorage.setItem('cart', JSON.stringify(cart));
      updateCount('cartCount', cart.reduce((sum, x) => sum + x.quantity, 0));
      
      toast('Item berhasil ditambahkan ke keranjang!', 'ok');
    }
  };
});
  const dots = dotsWrap.querySelectorAll("span");

  const gapPx = 20; // gap-5
  const cardWidth = cards[0].offsetWidth + gapPx;

  function updateDots() {
    const i = Math.floor((row.scrollLeft + cardWidth/2) / cardWidth);
    dots.forEach((d, idx) => {
      d.classList.toggle("bg-pink-600", idx === i);
      d.classList.toggle("bg-gray-300", idx !== i);
    });
  }

  // klik dot → pindah kartu
  dots.forEach(dot => {
    dot.addEventListener("click", () => {
      const idx = parseInt(dot.dataset.index);
      row.scrollTo({ left: idx * cardWidth, behavior: "smooth" });
    });
  });

  row.addEventListener("scroll", () => requestAnimationFrame(updateDots));
  updateDots();
});


// Browse by Category scroller


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

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCategoryScroller);
} else {
  initCategoryScroller();
}

// Detail Products
document.addEventListener('DOMContentLoaded', () => {
  // COLORS
  initChoiceGroup({
    group: '#colorGroup',
    itemSelector: '[data-color]',
    selectedClasses: ['ring-2', 'ring-black', 'ring-offset-2'],
    unselectedClasses: ['ring-1', 'ring-gray-300', 'ring-offset-0'],
    input: '#colorInput',
    getValue: el => el.dataset.color,
  });

  // SIZES
  initChoiceGroup({
    group: '#sizeGroup',
    itemSelector: '[data-size]',
    selectedClasses: ['bg-black', 'text-white', 'font-semibold'],
    unselectedClasses: ['bg-gray-100', 'text-gray-700'],
    input: '#sizeInput',
    getValue: el => el.dataset.size,
  });
});

function initChoiceGroup({
  group, itemSelector, selectedClasses, unselectedClasses, input, getValue
}) {
  const root = document.querySelector(group);
  if (!root) return;

  const items = Array.from(root.querySelectorAll(itemSelector));
  if (!items.length) return;

  const hidden = input ? document.querySelector(input) : null;

  const select = (target) => {
    items.forEach(btn => {
      const active = btn === target;

      // aria
      btn.setAttribute('aria-checked', String(active));

      // reset -> unselected
      unselectedClasses.forEach(c => btn.classList.add(c));
      selectedClasses.forEach(c => btn.classList.remove(c));

      // apply selected
      if (active) {
        unselectedClasses.forEach(c => btn.classList.remove(c));
        selectedClasses.forEach(c => btn.classList.add(c));
      }
    });

    if (hidden) {
      hidden.value = getValue(target);
      // trigger event kalau nanti mau dipakai backend/livewire/alpine
      hidden.dispatchEvent(new Event('change', { bubbles: true }));
    }
  };

  // initial (yang aria-checked="true" atau fallback ke pertama)
  const initial = items.find(i => i.getAttribute('aria-checked') === 'true') || items[0];
  select(initial);

  // click
  items.forEach(btn => btn.addEventListener('click', () => select(btn)));

  // keyboard: ← → / A D / Space / Enter
  root.addEventListener('keydown', (e) => {
    const idx = items.findIndex(i => i.getAttribute('aria-checked') === 'true');
    let next = idx;

    const key = e.key.toLowerCase();
    if (key === 'arrowright' || key === 'd') next = (idx + 1) % items.length;
    if (key === 'arrowleft'  || key === 'a') next = (idx - 1 + items.length) % items.length;
    if (key === ' ' || key === 'enter') {
      e.preventDefault();
      select(items[idx]);
      return;
    }

    if (next !== idx) {
      e.preventDefault();
      select(items[next]);
      items[next].focus();
    }
  });
}

// Wishlist & Cart 
const ls = {
  get(key, fallback) {
    try { return JSON.parse(localStorage.getItem(key)) ?? fallback; }
    catch { return fallback; }
  },
  set(key, val) { localStorage.setItem(key, JSON.stringify(val)); }
};

function updateCount(id, count, options = {}) {
  const { force = false } = options;

  let el = document.getElementById(id);

  // Fallback untuk badge cart tamu
  if (!el && id === 'cartCount') {
    el = document.getElementById('cartCountGuest');
  }

  if (!el) {
    return null;
  }

  if (!force && el.dataset.sync === 'server') {
    return null;
  }

  const numericCount = Number(count) || 0;
  if (numericCount > 0) {
    el.textContent = numericCount;
    el.classList.remove('hidden');
  } else {
    el.textContent = '0';
    el.classList.add('hidden');
  }

  return el;
}

if (typeof window !== 'undefined') {
  window.updateCount = (id, count, options) => updateCount(id, count, options);
}

function toast(msg, type = 'ok') {
  // mini toast sederhana
  document.querySelectorAll('.app-toast').forEach(el => el.remove());

  const t = document.createElement('div');
  t.textContent = msg;
  t.className =
    'app-toast fixed left-1/2 top-6 -translate-x-1/2 z-[9999] rounded-md px-4 py-2 text-sm font-semibold shadow ' +
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
    // untuk cart: tambah qty; wishlist: biarkan 1
    if ('qty' in list[idx]) list[idx].qty += (item.qty ?? 1);
  }
  return list;
}

function getVariantKey(x) {
  return `${x.sku}__${x.color ?? ''}__${x.size ?? ''}`;
}

document.addEventListener('DOMContentLoaded', () => {
  // init badge on load
  updateCount('wishlistCount', (ls.get('wishlist', [])).length);
  updateCount('cartCount', (ls.get('cart', [])).reduce((a, b) => a + (b.qty ?? 1), 0));

  const btnWish = document.getElementById('btnWishlist');
  const btnCart = document.getElementById('btnCart');

  const colorInput = document.getElementById('colorInput');
  const sizeInput  = document.getElementById('sizeInput');

  // Wishlist functionality is handled by onclick in product-details.blade.php
  // Removed duplicate event listener to prevent double notifications

  if (btnCart && btnCart.dataset.localCart === 'true') {
    btnCart.addEventListener('click', () => {
      const item = {
        sku:   btnCart.dataset.sku,
        name:  btnCart.dataset.name,
        price: Number(btnCart.dataset.price || 0),
        color: colorInput?.value || null,
        size:  sizeInput?.value || null,
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
});

// Cart functionality is now handled in cart.blade.php


// Catalog
document.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(location.search);
  const group = (params.get('group') || 'all').toLowerCase();

  // Update heading/breadcrumb text
  const mapTitle = {
    all: 'All Products',
    women: 'Women Fashion',
    men: 'Men Fashion',
    kids: 'Kids Fashion',
    accessories: 'Accessories'
  };
  const heading = document.getElementById('catalogHeading');
  const crumb   = document.getElementById('catalogCrumb');
  if (heading) heading.textContent = mapTitle[group] ?? 'Catalog';
  if (crumb)   crumb.textContent   = mapTitle[group] ?? 'Catalog';

  // Show the correct filter section
  const showAll = group === 'all';
  document.querySelectorAll('[data-group="all"]').forEach(el => el.hidden = !showAll);
  document.querySelectorAll('[data-group="children"]').forEach(el => el.hidden = showAll);

  // If we are in a child group (women/men/kids/accessories), populate subcategories accordingly
  if (!showAll) {
    const subcatContainer = document.getElementById('subcatContainer');
    if (subcatContainer) {
      const subcatsByGroup = {
        women:       ['T-shirts', 'Shirts', 'Hoodie', 'Dress', 'Skirts'],
        men:         ['T-shirts', 'Shirts', 'Hoodie', 'Pants', 'Outerwear'],
        kids:        ['T-shirts', 'Shirts', 'Hoodie', 'Shorts', 'Sets'],
        accessories: ['Bags', 'Belts', 'Hats', 'Jewelry']
      };
      const items = subcatsByGroup[group] || ['T-shirts','Shirts','Hoodie'];
      subcatContainer.innerHTML = items.map(label => `
        <label class="flex items-center gap-2 text-sm">
          <input type="checkbox" class="rounded border-gray-300 text-black focus:ring-black">
          <span>${label}</span>
        </label>
      `).join('');
    }
  }

  // Price live labels
  const min = document.getElementById('priceMin');
  const max = document.getElementById('priceMax');
  const minVal = document.getElementById('minVal');
  const maxVal = document.getElementById('maxVal');
  const syncRange = () => {
    if (min && max && minVal && maxVal) {
      if (+min.value > +max.value) max.value = min.value;
      minVal.textContent = min.value;
      maxVal.textContent = max.value;
    }
  };
  min?.addEventListener('input', syncRange);
  max?.addEventListener('input', syncRange);
  syncRange();

  // Cosmetic toggles for colors & sizes (visual pressed state)
  document.querySelectorAll('#colorDots button').forEach(b => {
    b.addEventListener('click', () => b.toggleAttribute('aria-pressed'));
  });
  document.querySelectorAll('#sizePills button').forEach(b => {
    b.addEventListener('click', () => {
      const pressed = b.getAttribute('aria-pressed') === 'true';
      b.setAttribute('aria-pressed', String(!pressed));
      b.classList.toggle('bg-black', !pressed);
      b.classList.toggle('text-white', !pressed);
      b.classList.toggle('font-semibold', !pressed);
      b.classList.toggle('bg-gray-100', pressed);
      b.classList.toggle('text-gray-700', pressed);
    });
  });

  // Reset buttons
  document.getElementById('resetAll')?.addEventListener('click', () => {
    location.href = "{{ route('catalog', ['group' => 'all']) }}";
  });
  document.getElementById('resetChildren')?.addEventListener('click', () => {
    // reset simple UI bits
    document.querySelectorAll('#subcatContainer input[type="checkbox"]').forEach(cb => cb.checked = false);
    document.querySelectorAll('#colorDots button[aria-pressed="true"]').forEach(b => b.click());
    document.querySelectorAll('#sizePills button[aria-pressed="true"]').forEach(b => b.click());
    if (min && max) { min.value = 60; max.value = 160; syncRange(); }
  });

  // Apply Filter (no backend yet — just a placeholder)
  document.getElementById('applyFilter')?.addEventListener('click', () => {
    alert('Filter applied (UI only for now).');
  });
});

// Database-based counter functions
function updateCartCountFromDB() {
  console.log('🛒 Updating cart count from database...');
  
  const cartCountEl = document.getElementById('cartCount');
  if (!cartCountEl) {
    console.error('🛒 Cart counter element not found!');
    return;
  }

  // Set default state: hidden dan kosong
  cartCountEl.classList.add('hidden');
  cartCountEl.textContent = '0';

  // Jika di halaman cart, prioritaskan hitung manual dari DOM
  if (window.location.pathname === '/cart') {
    const cartRows = document.querySelectorAll('[data-row]');
    let manualCount = cartRows.length; // Hitung jumlah item, bukan total quantity
    
    console.log('🛒 Manual count from cart page DOM:', manualCount);
    
    if (manualCount > 0) {
      cartCountEl.textContent = manualCount;
      cartCountEl.classList.remove('hidden');
      console.log('🛒 Cart counter updated from DOM with manual count:', manualCount);
    } else {
      cartCountEl.textContent = '0';
      cartCountEl.classList.add('hidden');
      console.log('🛒 Cart is empty, counter hidden');
    }
    return; // Keluar dari fungsi, tidak perlu fetch API
  }
  
  // Untuk halaman lain, gunakan nilai server yang sudah di-render (dari ViewServiceProvider)
  // Tapi reset dulu ke state default, baru cek nilai server
  const serverCartCount = cartCountEl.getAttribute('data-server-count') || cartCountEl.textContent.trim();
  
  if (serverCartCount && serverCartCount !== '0' && serverCartCount !== '') {
    const serverCount = parseInt(serverCartCount);
    if (!isNaN(serverCount) && serverCount > 0) {
      cartCountEl.textContent = serverCount;
      cartCountEl.classList.remove('hidden');
      console.log('🛒 Using server-provided cart count:', serverCount);
      return; // Gunakan nilai server, tidak perlu API call
    }
  }
  
  // Jika tidak ada nilai server yang valid, coba API sebagai fallback
  console.log('🛒 No valid server count, trying API...');
  
  const url = '/api/v1/cart/count';
  const fallbackUrl = '/cart/count';
  
  const makeRequest = (u) => fetch(u, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
      'Accept': 'application/json'
    },
    credentials: 'same-origin',
    cache: 'no-store'
  });
  
  makeRequest(url)
    .then(res => {
      if (res.ok) {
        return res.json();
      } else if (res.status === 302 || res.status === 401) {
        // Jika tidak terautentikasi, gunakan fallback web route
        return makeRequest(fallbackUrl).then(fallbackRes => {
          if (fallbackRes.ok) {
            return fallbackRes.json();
          }
          throw new Error('Both API endpoints failed');
        });
      } else {
        throw new Error('API v1 failed');
      }
    })
    .then(data => {
      console.log('🛒 Cart count data received from API:', data);
      const count = data.count ?? 0;
      console.log('🛒 Updating cart counter with API count:', count);
      
      if (count > 0) {
        cartCountEl.textContent = count;
        cartCountEl.classList.remove('hidden');
        console.log('🛒 Cart counter shown with value:', count);
      } else {
        cartCountEl.textContent = '0';
        cartCountEl.classList.add('hidden');
        console.log('🛒 Cart counter hidden (API count is 0)');
      }
    })
    .catch(err => {
      console.warn('🛒 API cart count failed, keeping default hidden state:', err.message);
      // Jika API gagal, tetap gunakan state default (hidden)
      cartCountEl.textContent = '0';
      cartCountEl.classList.add('hidden');
      console.log('🛒 Cart counter kept hidden due to API failure');
    });
}

function updateWishlistCountFromDB() {
  console.log('💖 Updating wishlist count from database...');
  
  const url = '/api/v1/wishlist/count';
  const fallbackUrl = '/wishlist/count';
  
  const makeRequest = (u) => fetch(u, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    },
    credentials: 'include',
    cache: 'no-store'
  });
  
  makeRequest(url)
    .then(res => res.ok ? res.json() : Promise.reject(new Error('API v1 failed')))
    .catch(() => makeRequest(fallbackUrl).then(r => r.json()))
    .then(data => {
      console.log('💖 Wishlist count data received:', data);
      const wishlistCountEl = document.getElementById('wishlistCount');
      if (wishlistCountEl) {
        if ((data.count ?? 0) > 0) {
          wishlistCountEl.textContent = data.count;
          wishlistCountEl.classList.remove('hidden');
        } else {
          wishlistCountEl.textContent = '';
          wishlistCountEl.classList.add('hidden');
        }
      }
    })
    .catch(err => {
      console.warn('💖 Wishlist count fetch failed:', err.message);
    });
}

// Make functions globally accessible
window.updateCartCountFromDB = updateCartCountFromDB;
window.updateWishlistCountFromDB = updateWishlistCountFromDB;

// Improved auth detection function
function isUserAuthenticated() {
  // Check for multiple auth indicators
  const authIndicators = [
    document.querySelector('form[action*="logout"]'),
    document.querySelector('a[href*="profile"]'),
    document.querySelector('.user-menu'),
    document.querySelector('#cartCount'), // Cart counter only exists for auth users
    document.querySelector('a[href*="orders"]')
  ];
  
  return authIndicators.some(element => element !== null);
}

// Initialize counters from database on page load
document.addEventListener('DOMContentLoaded', () => {
  console.log('🚀 DOM loaded, initializing counters...');
  
  // Always try to update counters - the API will handle auth check
  updateCartCountFromDB();
  updateWishlistCountFromDB();
  
  // Also set up a periodic update every 30 seconds for real-time sync
  setInterval(() => {
    if (isUserAuthenticated()) {
      updateCartCountFromDB();
      updateWishlistCountFromDB();
    }
  }, 30000);
});

// ===== Wishlist (DB-based) =====
function setWishlistButtonState(btn, inWishlist) {
  const icon = btn?.querySelector('i');
  if (!icon) return;
  if (inWishlist) {
    icon.className = 'fa-solid fa-heart';
    btn.classList.add('bg-black/20');
    btn.title = 'Remove from Wishlist';
  } else {
    icon.className = 'fa-regular fa-heart';
    btn.classList.remove('bg-black/20');
    btn.title = 'Add to Wishlist';
  }
}

async function toggleWishlist(productId, btn) {
  try {
    const res = await fetch('/api/wishlist/toggle', {
      method: 'POST',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      },
      body: JSON.stringify({ product_id: productId })
    });

    const data = await res.json().catch(() => ({}));
    if (res.ok) {
      const added = data.action === 'added';
      setWishlistButtonState(btn, added);
      updateWishlistCountFromDB();
      try { toast(added ? 'Produk ditambahkan ke wishlist!' : 'Produk dihapus dari wishlist!', 'ok'); } catch {}
    } else {
      try { toast(data.message || 'Gagal memperbarui wishlist', 'error'); } catch {}
    }
  } catch (e) {
    console.error('toggleWishlist error', e);
    try { toast('Terjadi kesalahan saat memperbarui wishlist', 'error'); } catch {}
  }
}

function initWishlistButtons() {
  const buttons = document.querySelectorAll('.wishlist-btn[data-product-id]');
  if (!buttons.length) return;
  
  // Check if user is authenticated before making API calls
  const authElements = document.querySelector('form[action*="logout"]') || 
                      document.querySelector('a[href*="profile"]') ||
                      document.querySelector('.user-menu');
  
  if (!authElements) {
    // User not authenticated, skip wishlist check
    return;
  }
  
  buttons.forEach(btn => {
    const pid = Number(btn.getAttribute('data-product-id'));
    fetch('/api/wishlist/check', {
      method: 'POST',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      },
      body: JSON.stringify({ product_id: pid })
    })
    .then(r => {
      if (!r.ok) {
        throw new Error(`HTTP error! status: ${r.status}`);
      }
      return r.json();
    })
    .then(d => { 
      if (d?.in_wishlist) setWishlistButtonState(btn, true); 
    })
    .catch(error => {
      console.log('Wishlist check skipped (user not authenticated or error occurred)');
    });
  });
}

// expose globally for inline onclick usage on cards
window.toggleWishlist = toggleWishlist;

// initialize wishlist button states for authenticated users
document.addEventListener('DOMContentLoaded', () => {
  initWishlistButtons();
});

// handle clicks on wishlist buttons on cards/detail
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.wishlist-btn[data-product-id]');
  if (!btn) return;
  const pid = Number(btn.getAttribute('data-product-id'));
  if (!pid) return;
  toggleWishlist(pid, btn);
});

// Provide a global fallback for showMessage to prevent ReferenceError on pages that call it
if (typeof window !== 'undefined' && typeof window.showMessage !== 'function') {
  window.showMessage = function (message, type = 'info') {
    try {
      // Simple non-blocking notification using console; can be replaced by a toast library
      const prefix = type === 'error' ? '❌' : type === 'success' ? '✅' : type === 'warning' ? '⚠️' : 'ℹ️';
      console.log(`${prefix} ${message}`);
    } catch (e) {
      // Fallback to alert if console fails for any reason
      alert(message);
    }
  };
}
