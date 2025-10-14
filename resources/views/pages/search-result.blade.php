{{-- resources/views/pages/search-results.blade.php --}}
@extends('layouts.master')

@section('title', 'Hasil Pencarian untuk "' . $query . '"')

@section('content')
<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
    <nav class="text-sm text-gray-500">
      <ol class="flex items-center gap-3">
        <li><a href="{{ route('home') }}" class="hover:text-gray-900">Home</a></li>
        <li class="text-gray-300">›</li>
        <li class="text-gray-900">Search</li>
      </ol>
    </nav>

    <div class="mt-4">
        <h1 class="text-3xl font-extrabold">Hasil pencarian untuk: "{{ $query }}"</h1>
    </div>
    
    <section class="mt-8">
        {{-- Gunakan @forelse untuk menangani kasus produk ditemukan atau tidak --}}
        <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            @forelse ($products as $product)
                {{-- Ini adalah card produk Anda, bisa di-include dari file partial --}}
                <div class="group rounded-xl border border-gray-200 bg-white p-3 hover:shadow-card transition relative">
                    <a href="{{ route('product.detail', $product->slug) }}" class="block">
                        <div class="aspect-[4/5] overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
                            <img src="{{ isset($product->images[0]) ? asset('storage/' . $product->images[0]) : asset('images/placeholder.jpg') }}" class="h-full w-full object-cover" alt="{{ $product->name }}">
                        </div>
                        <div class="mt-3">
                            <p class="text-[13px] font-semibold text-gray-800">{{ $product->name }}</p>
                            <p class="text-[13px] font-extrabold">Rp{{ number_format($product->price) }}</p>
                        </div>
                    </a>
                </div>
            @empty
                {{-- Tampilan jika produk tidak ditemukan --}}
                <div class="col-span-full text-center py-16">
                                        <h2 class="text-2xl font-semibold text-gray-700">Oops! Produk tidak ditemukan.</h2>
                    <p class="text-gray-500 mt-2">Tidak ada produk yang cocok dengan kata kunci "{{ $query }}".</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </section>
</main>
@endsection