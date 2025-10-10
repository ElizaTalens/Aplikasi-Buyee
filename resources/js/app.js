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

function updateCount(id, count) {
  const el = document.getElementById(id);
  if (!el) return;
  if (!count) { el.classList.add('hidden'); el.textContent = ''; return; }
  el.textContent = count;
  el.classList.remove('hidden');
}

function toast(msg, type = 'ok') {
  // mini toast sederhana
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
  updateCount('wishCount', (ls.get('wish', [])).length);
  updateCount('cartCount', (ls.get('cart', [])).reduce((a, b) => a + (b.qty ?? 1), 0));

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
        color: colorInput?.value || null,
        size:  sizeInput?.value || null,
      };
      const wish = ls.get('wish', []);
      upsert(wish, item, getVariantKey);
      ls.set('wish', wish);
      updateCount('wishCount', wish.length);
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

// Cart
document.addEventListener('DOMContentLoaded', () => {
  const cartRoot = document.querySelector('[data-cart]');
  if (!cartRoot) return;

  const fmt = n => `$${Number(n).toFixed(0)}`;

  function recalc() {
    let subtotal = 0;
    cartRoot.querySelectorAll('[data-row]').forEach(row => {
      const price = Number(row.dataset.price || 0);
      const qty = Number(row.querySelector('[data-qty]')?.textContent || 0);
      subtotal += price * qty;
    });
    const delivery = subtotal ? 15 : 0; // sesuai mockup
    document.getElementById('subtotal').textContent = fmt(subtotal);
    document.getElementById('delivery').textContent = fmt(delivery);
    document.getElementById('grand').textContent = fmt(subtotal + delivery);
  }

  // Bind tombol
  cartRoot.querySelectorAll('[data-row]').forEach(row => {
    const qtyEl = row.querySelector('[data-qty]');
    const minus = row.querySelector('[data-minus]');
    const plus  = row.querySelector('[data-plus]');
    const del   = row.querySelector('[data-remove]');

    minus?.addEventListener('click', () => {
      let q = Number(qtyEl.textContent || 1);
      if (q > 1) { qtyEl.textContent = q - 1; recalc(); }
    });

    plus?.addEventListener('click', () => {
      let q = Number(qtyEl.textContent || 1);
      qtyEl.textContent = q + 1;
      recalc();
    });

    del?.addEventListener('click', () => {
      row.remove();
      recalc();
    });
  });

  recalc();
});


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
