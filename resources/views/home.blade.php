@extends('layouts.app')

@section('content')

<main class="hero container">
    <div class="clr-one"></div>
    <div class="clr-two"></div>
    <div class="wrapper">
        <div class="col col-text" data-aos="fade-right">
            <h1 class="heading-one">𝐛𝐞𝐬𝐭 𝐢𝐧 𝐬𝐭𝐲𝐥𝐞 <br> 𝐜𝐨𝐥𝐥𝐞𝐜𝐭𝐢𝐨𝐧 <br> <span>𝐟𝐨𝐫 𝐲𝐨𝐮</span></h1>
            <p class="sub-text">Kami mengoleksi sepatu dengan tren masa kini, kami ingin memberikan yang terbaik di dalam dunia fashion, <br> dengan memilih sepatu dengan desain "TIMELESS".</p>
            <button id="preOrderBtn" class="btn btn-hero" onclick="location.href='{{ url('/register') }}';">Pre-Order Now</button>
        </div>
        <div class="col col-img">
            <figure data-aos="fade-left">
                <img src="{{ asset('img/hero.png') }}" alt="nike-shoe">
            </figure>
            <div class="hero-img-off" data-aos="zoom-in-up">
                <h3>Get Up to 40% OFF</h3>
                <p>Diskon berlaku untuk pelanggan yang sudah membeli lebih dari 5 sepatu Nike edisi terbatas.</p>
            </div>
        </div>
    </div>
</main>

<div class="subscribe-notification">
    <div class="notification-content">
        <span class="highlight">Dimas</span> baru saja mensubscribe
    </div>
</div>
<div class="video-overlay">
    <div class="video-container">
        <span class="close-video">&times;</span>
        <video controls autoplay muted loop>
            <source src="{{ asset('img/video-promo.mp4') }}" type="video/mp4">
            Browser Anda tidak mendukung tag video.
        </video>
        <div class="video-caption">
            <h3></h3>
            <button id="discoverBtn" class="btn btn-hero" onclick="location.href='{{ url('/discover') }}';">
                Discover Now
            </button>
        </div>
    </div>
</div>
<button class="floating-video-btn" title="Tonton Video">
    <i class="fa-solid fa-play"></i>
</button>

<section class="arrival container" id="arrival">
    <div class="section-heading" data-aos="zoom-in-up">
        <div class="heading">
            <p class="sub-heading">𝐎𝐮𝐫 𝐧𝐞𝐰 𝐜𝐨𝐥𝐥𝐞𝐜𝐭𝐢𝐨𝐧</p>
            <h2 class="heading-two">𝐧𝐞𝐰 <span>𝐚𝐫𝐫𝐢𝐯𝐚𝐥𝐬</span></h2>
        </div>
        <button class="btn">see all</button>
    </div>
    <div class="wrapper">
        <div class="col" data-aos="zoom-in-up">
            <figure>
                <img src="{{ asset('img/na1.png') }}" alt="nike-shoe">
            </figure>
            <div class="col-body">
                <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.9</span></p>
                <h3 class="heading-three">nike air max 94</h3>
                <p class="sub-heading">air max</p>
                <div class="col-footer">
                    <p class="show-price">Rp3.299.000</p>
                    <button class="show-btn btn">buy</button>
                </div>
            </div>
        </div>

        <div class="col" data-aos="zoom-in-up">
            <figure>
                <img src="{{ asset('img/na2.png') }}" alt="nike-shoe">
            </figure>
            <div class="col-body">
                <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.5</span></p>
                <h3 class="heading-three">nike air huarache run ultra</h3>
                <p class="sub-heading">air gray</p>
                <div class="col-footer">
                    <p class="show-price">Rp2.799.000</p>
                    <button class="show-btn btn">buy</button>
                </div>
            </div>
        </div>

        <div class="col" data-aos="zoom-in-up">
            <figure>
                <img src="{{ asset('img/na3.png') }}" alt="nike-shoe">
            </figure>
            <div class="col-body">
                <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.2</span></p>
                <h3 class="heading-three">nike air jordan 1 mid retro reverse new love</h3>
                <p class="sub-heading">air basket</p>
                <div class="col-footer">
                    <p class="show-price">Rp2.612.187</p>
                    <button class="show-btn btn">buy</button>
                </div>
            </div>
        </div>

        <div class="col" data-aos="zoom-in-up">
            <figure>
                <img src="{{ asset('img/na4.png') }}" alt="nike-shoe">
            </figure>
            <div class="col-body">
                <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
                <h3 class="heading-three">adidas ZX FLUX Multicolor</h3>
                <p class="sub-heading">adidas</p>
                <div class="col-footer">
                    <p class="show-price">Rp2.099.000</p>
                    <button class="show-btn btn">buy</button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about container" id="about">
    <div class="clr-one"></div>
    <div class="clr-two"></div>
    <div class="wrapper">
        <div class="col-img col">
            <div class="get-off" data-aos="zoom-in">
                <h4>Big Sean "Rapper"</h4>
                <p class="off-text">i like wearing a shoes that comfortable,HEAT UP!.</p>
            </div>
            <figure data-aos="fade-left">
                <img src="{{ asset('img/about6.png') }}" alt="about-img">
            </figure>
        </div>
        <div class="col-text col" data-aos="fade-right">
            <p class="sub-heading">𝐚𝐛𝐨𝐮𝐭 𝐮𝐬</p>
            <h2 class="heading-two">
              ​𝒘𝒆 𝒑𝒓𝒐𝒗𝒊𝒅𝒆 𝒉𝒊𝒈𝒉​ <br />
              𝒒𝒖𝒂𝒍𝒊𝒕𝒚 <span> 𝒄𝒐𝒍𝒍𝒆𝒄𝒕𝒊𝒐𝒏 </span>
            </h2>
            <p class="about-text">
              "Kami menyediakan sepatu berkualitas tinggi dengan desain modern dan kenyamanan maksimal.
              Temukan koleksi terbaru kami yang cocok untuk aktivitas sehari-hari hingga olahraga!"
            </p>
            <p class="extra-text" style="display: none;">
              Koleksi kami mencakup berbagai gaya dan ukuran, dirancang untuk memberikan performa terbaik.
              Baik untuk keperluan fashion sehari-hari atau aktivitas luar ruangan.
            </p>
            <button class="btn read-more-btn">read more</button>
        </div>
    </div>
</section>

<section class="collection container" id="best-collection">
    <div class="section-heading">
        <div class="heading">
            <p class="sub-heading">𝐛𝐞𝐬𝐭 𝐜𝐨𝐥𝐥𝐞𝐜𝐭𝐢𝐨𝐧</p>
            <h2 class="heading-two">𝐨𝐮𝐫 𝐧𝐞𝐰 𝐜𝐨𝐥𝐥𝐞𝐜𝐭𝐢𝐨𝐧</h2>
        </div>
        <div class="btn-section">
            <button class="btn-col btn" data-btn="all">all</button>
            <button class="btn-col" data-btn="men">men</button>
            <button class="btn-col" data-btn="women">women</button>
            <button class="btn-col" data-btn="sports">sports</button>
        </div>
    </div>
    <div class="grid-wrapper">
        <div class="col collection-item" data-item="men" data-aos="zoom-in-up">
            <figure><img src="{{ asset('img/men/men1.png') }}" alt=""></figure>
            </div>
        </div>
</section>

<section class="testimonial container" data-aos="zoom-in-up">
    </section>

<section id="about-founder-ceo">
    </section>

<div class="floating-elements"></div>

@endsection