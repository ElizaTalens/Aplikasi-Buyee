@extends('layouts.master')

@section('title', 'Wishlist Saya — Buyee')

@section('content')
<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
    {{-- Breadcrumbs --}}
    <nav class="text-sm text-gray-500">
      <ol class="flex items-center gap-3">
        <li><a href="{{ route('home') }}" class="hover:text-gray-900">Beranda</a></li>
        <li class="text-gray-300">›</li>
        <li class="text-gray-900">Wishlist</li>
      </ol>
    </nav>

    <h1 class="mt-3 text-[44px] font-extrabold tracking-tight">Wishlist Saya</h1>

    <div class="mt-6 grid grid-cols-12 gap-8" data-wishlist>
      {{-- LEFT: items --}}
      <section class="col-span-12 lg:col-span-8">
        <div class="rounded-3xl bg-white ring-1 ring-gray-200 overflow-hidden">

          @forelse($wishlistItems as $index => $item)
            <div class="flex items-center gap-6 p-6"
                 data-wishlist-item
                 data-wishlist-id="{{ $item->id }}"
                 data-product-id="{{ $item->product->id }}">
              <div class="h-28 w-28 shrink-0 overflow-hidden rounded-xl ring-1 ring-gray-200 bg-gray-50 grid place-items-center">
                <img src="{{ isset($item->product->images[0]) ? asset('storage/' . $item->product->images[0]) : asset('images/placeholder.jpg') }}" 
                     alt="{{ $item->product->name }}" 
                     class="h-full w-full object-cover">
              </div>

              <div class="min-w-0 flex-1">
                <h3 class="text-xl font-extrabold">{{ $item->product->name }}</h3>
                <div class="mt-1 text-sm text-gray-600">
                  Category: {{ $item->product->category->name ?? 'Uncategorized' }}
                </div>
                <div class="mt-2 text-sm text-gray-500">
                  {{ Str::limit($item->product->description, 100) }}
                </div>
                <div class="mt-3 text-2xl font-extrabold">Rp{{ number_format($item->product->price) }}</div>
              </div>

              <div class="flex flex-col gap-3">
                <button 
                  onclick="moveToCart({{ $item->product->id }}, {{ $item->id }})"
                  class="inline-flex items-center justify-center rounded-full bg-black px-4 py-2 text-white font-semibold hover:bg-gray-900 transition">
                  Pindahkan ke Keranjang
                </button>
                
                <button 
                  onclick="removeWishlistItem({{ $item->id }})"
                  class="inline-flex items-center justify-center rounded-full border border-gray-300 px-4 py-2 text-gray-700 font-semibold hover:bg-gray-50 transition">
                  Hapus
                </button>
              </div>
            </div>

            @if(!$loop->last)
              <hr class="border-gray-200">
            @endif
          @empty
            {{-- Row Placeholder (jika wishlist kosong) --}}
            <div class="p-12 text-center text-gray-500" data-empty-state>
              <i class="fa-regular fa-heart fa-3x mb-4"></i>
              <p class="text-xl font-semibold">Wishlist kamu masih kosong.</p>
              <p class="text-sm mt-2">Tambahkan produk yang kamu sukai dari halaman katalog.</p>
            </div>
          @endforelse

        </div>
      </section>

      {{-- RIGHT: Action Summary --}}
      <aside class="col-span-12 lg:col-span-4">
        <div class="rounded-3xl bg-white ring-1 ring-gray-200 p-6 sticky top-6">
          <h3 class="text-2xl font-extrabold">Aksi Wishlist</h3>

          <div class="mt-6 text-lg font-semibold text-gray-700">
            Total <span id="itemCount">{{ $wishlistItems->count() }}</span> item di wishlist
          </div>

          <button
            class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-black px-6 py-4 text-white font-semibold hover:bg-gray-900 transition" 
            onclick="moveAllToCart()" 
            data-move-all-to-cart>
            Pindahkan Semua ke Keranjang
          </button>
          
          <button
            class="mt-4 inline-flex w-full items-center justify-center rounded-full border border-gray-300 px-6 py-4 text-gray-700 font-semibold hover:bg-gray-50 transition" 
            onclick="clearWishlist()" 
            data-clear-wishlist>
            Hapus Semua Item
          </button>
        </div>
      </aside>
    </div>
  </main>
@endsection

@push('scripts')
    <script>
    const csrfToken = document.querySelector("meta[name='csrf-token']")?.getAttribute('content') || '';
    const wishlistListEl = document.querySelector('[data-wishlist] .rounded-3xl');
    const wishlistRoutes = {
      cartStore: "{{ route('cart.store') }}",
      destroy: "{{ route('wishlist.destroy', ['id' => '__ID__']) }}",
      clear: "{{ route('wishlist.clear') }}"
    };
    const emptyStateTemplate = `
      <div class="p-12 text-center text-gray-500" data-empty-state>
        <i class="fa-regular fa-heart fa-3x mb-4"></i>
        <p class="text-xl font-semibold">Wishlist kamu masih kosong.</p>
        <p class="text-sm mt-2">Tambahkan produk yang kamu sukai dari halaman katalog.</p>
      </div>
    `;

    function toast(msg, type = 'ok') {
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

    function buildDestroyUrl(wishlistId) {
      return wishlistRoutes.destroy.replace('__ID__', wishlistId);
    }

    function updateItemCountDisplay(count) {
      const itemCountEl = document.getElementById('itemCount');
      if (itemCountEl) {
        itemCountEl.textContent = Number(count) || 0;
      }
    }

    function ensureEmptyState() {
      if (!wishlistListEl) return;
      if (wishlistListEl.querySelector('[data-wishlist-item]')) return;
      if (wishlistListEl.querySelector('[data-empty-state]')) return;
      wishlistListEl.innerHTML = emptyStateTemplate.trim();
    }

    function removeWishlistDomEntry(wishlistId) {
      const itemElement = document.querySelector(`[data-wishlist-id="${wishlistId}"]`);
      if (itemElement) {
        itemElement.remove();
      }
      ensureEmptyState();
    }

    async function addProductToCart(productId, quantity = 1) {
      const response = await fetch(wishlistRoutes.cartStore, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          product_id: productId,
          quantity: quantity
        })
      });

      const data = await response.json().catch(() => ({}));
      if (!response.ok) {
        throw new Error(data.message || 'Gagal menambahkan produk ke keranjang.');
      }

      return data;
    }

    async function deleteWishlistEntry(wishlistId) {
      const response = await fetch(buildDestroyUrl(wishlistId), {
        method: 'DELETE',
        credentials: 'same-origin',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        }
      });

      const data = await response.json().catch(() => ({}));
      if (!response.ok) {
        throw new Error(data.message || 'Gagal menghapus produk dari wishlist.');
      }

      return data;
    }

    async function moveToCart(productId, wishlistId) {
      try {
        await addProductToCart(productId, 1);
        const removal = await deleteWishlistEntry(wishlistId);

        removeWishlistDomEntry(wishlistId);
        updateItemCountDisplay(removal.count ?? 0);

        if (typeof updateCartCountFromDB === 'function') {
          updateCartCountFromDB();
        }
        if (typeof updateWishlistCountFromDB === 'function') {
          updateWishlistCountFromDB();
        }

        toast('Produk berhasil dipindahkan ke keranjang!', 'ok');
      } catch (error) {
        console.error('moveToCart error:', error);
        toast(error.message || 'Terjadi kesalahan saat memindahkan produk ke keranjang', 'error');
      }
    }

    async function removeWishlistItem(wishlistId) {
      try {
        const removal = await deleteWishlistEntry(wishlistId);

        removeWishlistDomEntry(wishlistId);
        updateItemCountDisplay(removal.count ?? 0);

        if (typeof updateWishlistCountFromDB === 'function') {
          updateWishlistCountFromDB();
        }

        toast(removal.message || 'Produk dihapus dari wishlist!', 'ok');
      } catch (error) {
        console.error('removeWishlistItem error:', error);
        toast(error.message || 'Terjadi kesalahan saat menghapus produk dari wishlist', 'error');
      }
    }

    async function moveAllToCart() {
      const entries = Array.from(document.querySelectorAll('[data-wishlist-item]'));
      if (!entries.length) {
        toast('Tidak ada item di wishlist untuk dipindahkan', 'error');
        return;
      }

      if (!confirm('Apakah Anda yakin ingin memindahkan semua item ke keranjang?')) {
        return;
      }

      let successCount = 0;
      let failCount = 0;

      for (const entry of entries) {
        const productId = Number(entry.dataset.productId);
        const wishlistId = entry.dataset.wishlistId;

        try {
          await addProductToCart(productId, 1);
          const removal = await deleteWishlistEntry(wishlistId);

          removeWishlistDomEntry(wishlistId);
          updateItemCountDisplay(removal.count ?? 0);
          successCount++;
        } catch (error) {
          console.error(`moveAllToCart error for product ${entry.dataset.productId}:`, error);
          failCount++;
        }
      }

      if (successCount) {
        toast(`${successCount} item berhasil dipindahkan ke keranjang!`, 'ok');
      }
      if (failCount) {
        setTimeout(() => toast(`${failCount} item gagal dipindahkan.`, 'error'), successCount ? 1800 : 0);
      }

      if (typeof updateCartCountFromDB === 'function') {
        updateCartCountFromDB();
      }
      if (typeof updateWishlistCountFromDB === 'function') {
        updateWishlistCountFromDB();
      }

      ensureEmptyState();
    }

    async function clearWishlist() {
      if (!confirm('Apakah Anda yakin ingin menghapus semua item dari wishlist?')) {
        return;
      }

      try {
        const response = await fetch(wishlistRoutes.clear, {
          method: 'DELETE',
          credentials: 'same-origin',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          }
        });

        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
          throw new Error(data.message || 'Gagal menghapus semua item dari wishlist.');
        }

        if (wishlistListEl) {
          wishlistListEl.innerHTML = emptyStateTemplate.trim();
        }

        updateItemCountDisplay(0);

        if (typeof updateWishlistCountFromDB === 'function') {
          updateWishlistCountFromDB();
        }
        if (typeof updateCartCountFromDB === 'function') {
          updateCartCountFromDB();
        }

        toast(data.message || 'Semua item berhasil dihapus dari wishlist!', 'ok');
      } catch (error) {
        console.error('clearWishlist error:', error);
        toast(error.message || 'Terjadi kesalahan saat menghapus semua item', 'error');
      }
    }
  </script>
@endpush
