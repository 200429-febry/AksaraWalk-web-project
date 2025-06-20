<section class="subscribe container">
    <div class="wrapper">
        <h2 class="heading-two">𝑺𝒖𝒃𝒔𝒄𝒓𝒊𝒃𝒆 𝒇𝒐𝒓 𝑵𝒆𝒘𝒔 𝒂𝒏𝒅 𝑳𝒂𝒕𝒆𝒔𝒕 𝑼𝒑𝒅𝒂𝒕𝒆𝒔</h2>
        <form id="subscribeForm">
            <input type="email" id="emailInput" class="email" placeholder="example@gmail.com" required>
            <button type="submit" class="btn">Subscribe</button>
        </form>
        <p id="message" style="color: white; margin-top: 10px;"></p>
    </div>
</section>

<div class="social-floating-left">
    <section class="guide-section">
        <div class="guide-container">
            <h3 class="guide-title">Butuh Bantuan?</h3>
            <a href="{{ asset('panduan.pdf') }}" class="guide-btn" download>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="guide-icon">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
                <span>Panduan</span>
            </a>
            <p class="guide-desc">Petunjuk lengkap mengenai sepatu</p>
        </div>
    </section>
    <div class="wa-btn-container">
        <a href="https://wa.me/62895333630670" class="wa-btn" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
            </a>
    </div>
    <div class="ig-btn-container">
        <a href="https://instagram.com/aksarawalk" class="ig-btn" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
            </a>
    </div>
</div>

<footer class="container">
    <div class="wrapper">
        <div class="col" data-aos="zoom-in">
            <a href="{{ url('/') }}" class="logo">𝐀𝐤𝐬𝐚𝐫𝐚𝐖𝐚𝐥𝐤</a>
            <p class="about-website">AksaraWalk adalah platform fashion terbaru... (dan seterusnya)</p>
        </div>
        <div class="col" data-aos="zoom-in">
            <h4>quick links</h4>
            <a href="{{ url('/') }}">𝑯𝒐𝒎𝒆</a>
            <a href="#about">𝑨𝒃𝒐𝒖𝒕</a>
            <a href="#arrival">𝒏𝒆𝒘 𝒂𝒓𝒓𝒊𝒗𝒂𝒍</a>
            <a href="#best-collection">𝒃𝒆𝒔𝒕 𝒄𝒐𝒍𝒍𝒆𝒄𝒕𝒊𝒐𝒏</a>
        </div>
        <div class="col" data-aos="zoom-in">
            <h4>contact us</h4>
            <p>Aksarawalk363@gmail.com</p>
            <p>+62895333630670</p>
        </div>
    </div>
</footer>