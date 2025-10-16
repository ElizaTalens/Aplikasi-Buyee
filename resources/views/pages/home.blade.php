@extends('layouts.master')

@section('content')

    {{-- ======================================================= --}}
    {{-- |               BAGIAN 1: HERO BANNER (DARI master.blade)            | --}}
    {{-- ======================================================= --}}
    <section class="mx-auto max-w-7xl pt-10">
        @include('layouts.hero')
    </section>

    {{-- ======================================================= --}}
    {{-- |             BAGIAN 2: BROWSE BY CATEGORY            | --}}
    {{-- ======================================================= --}}
    <section class="my-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Pastikan file ini menerima $categories --}}
        @include('layouts.category-row', ['categories' => $categories])
    </section>

    {{-- ======================================================= --}}
    {{-- |           BAGIAN 3: PRODUCT GRID TABS (New Arrivals & Bestseller)        | --}}
    {{-- ======================================================= --}}
    <section class="my-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- KOREKSI: Panggil product grid tabs DAN BERI DATA $products --}}
        @include('layouts.product-grid-tabs', ['products' => $products])
    </section>

@endsection