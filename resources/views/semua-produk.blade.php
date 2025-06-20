@extends('layouts.app')

@section('content')

<main class="container" style="padding-top: 120px; padding-bottom: 60px;">
    
    <section class="collection" id="best-collection">
        <div class="section-heading">
            <div class="heading">
                <p class="sub-heading">Jelajahi Koleksi Kami</p>
                <h2 class="heading-two">Semua <span>Produk</span></h2>
            </div>
            <div class="btn-section">
                <button class="btn-col btn" data-btn="all">All</button>
                <button class="btn-col" data-btn="men">Men</button>
                <button class="btn-col" data-btn="women">Women</button>
                <button class="btn-col" data-btn="sports">Sports</button>
            </div>
        </div>

        <div class="grid-wrapper">
            
            {{-- Di sini Anda bisa memuat SEMUA produk dari database nantinya --}}
            {{-- Untuk saat ini, kita tampilkan beberapa contoh --}}
            <div class="col collection-item" data-item="men">
                <figure><img src="{{ asset('img/men/men1.png') }}" alt="Nike Obsidian"></figure>
                <div class="col-body"><h3 class="heading-three">Nike air max 2015 "dark obsidian"</h3><p class="sub-heading">nike</p><div class="col-footer"><p class="show-price">Rp2.099.000</p><button class="show-btn btn">buy</button></div></div>
            </div>
            <div class="col collection-item" data-item="sports">
                <figure><img src="{{ asset('img/sports/sports1.png') }}" alt="Nike Pegasus"></figure>
                <div class="col-body"><h3 class="heading-three">Nike Air Zoom Pegasus</h3><p class="sub-heading">nike</p><div class="col-footer"><p class="show-price">Rp1.799.000</p><button class="show-btn btn">buy</button></div></div>
            </div>
            <div class="col collection-item" data-item="women">
                <figure><img src="{{ asset('img/women/women1.png') }}" alt="Puma Burgundy"></figure>
                <div class="col-body"><h3 class="heading-three">Puma Basket Burgundy</h3><p class="sub-heading">puma</p><div class="col-footer"><p class="show-price">Rp1.500.000</p><button class="show-btn btn">buy</button></div></div>
            </div>
            <div class="col collection-item" data-item="men">
                <figure><img src="{{ asset('img/men/men2.png') }}" alt="Jordan Concord"></figure>
                <div class="col-body"><h3 class="heading-three">Nike Air Jordan 11 Concord</h3><p class="sub-heading">nike</p><div class="col-footer"><p class="show-price">Rp.4.398.000</p><button class="show-btn btn">buy</button></div></div>
            </div>
            <div class="col collection-item" data-item="women">
                <figure><img src="{{ asset('img/women/women4.png') }}" alt="Precise Flexnit"></figure>
                <div class="col-body"><h3 class="heading-three">Precise Flexnit Kurven</h3><p class="sub-heading">Precise</p><div class="col-footer"><p class="show-price">Rp450.000</p><button class="show-btn btn">buy</button></div></div>
            </div>
            {{-- Tambahkan produk lainnya di sini --}}

        </div>
    </section>
</main>

@endsection