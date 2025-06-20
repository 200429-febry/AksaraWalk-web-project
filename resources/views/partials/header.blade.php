<header class="header">
    <img src="{{ asset('logo.png') }}" alt="Logo" width="37">
    <div class="nav">
        <a href="{{ url('/') }}" class="logo">𝐀𝐤𝐬𝐚𝐫𝐚𝐖𝐚𝐥𝐤</a>
        <nav>
            <ul>
                <li><a href="{{ url('/') }}">𝑯𝒐𝒎𝒆</a></li>
                <li><a href="#arrival">𝑵𝒆𝒘 𝑨𝒓𝒓𝒊𝒗𝒂𝒍𝒔</a></li>
                <li><a href="#best-collection">𝑩𝒆𝒔𝒕 𝑪𝒐𝒍𝒍𝒆𝒄𝒕𝒊𝒐𝒏</a></li>
                <li><a href="#about">𝑨𝒃𝒐𝒖𝒕</a></li>
            </ul>
            <div class="nav-icon">
                <span class="wishlist-btn"><i class="fa-solid fa-heart"></i></span>
                <span class="cart-btn"><i class="fa-solid fa-cart-shopping"></i></span>
                <span class="cart-count">0</span>
            </div>
        </nav>
        <i class="fa-solid fa-bars burger_icon"></i>
    </div>
</header>

<div class="popup wishlist-popup">
    <div class="popup-content">
        <span class="close-popup">&times;</span>
        <h3>Wishlist Anda</h3>
        <div class="wishlist-items">
            </div>
    </div>
</div>

<div class="popup cart-popup">
    <div class="popup-content">
        <span class="close-popup">&times;</span>
        <h3>Keranjang Belanja</h3>
        <div class="cart-items">
            </div>
        <div class="cart-total">
            <p>Total: <span class="total-price">Rp0</span></p>
            <button class="checkout-btn">Checkout</button>
        </div>
    </div>
</div>

<div class="modal-3d" id="shoe3dModal">
  <div class="modal-3d-content">
    <span class="close-3d">&times;</span>
    <h3 id="shoe3dTitle">3D</h3>
    <div id="shoe3dViewer"></div>
    <div class="shoe-details">
      <p id="shoe3dDescription"></p>
      <p id="shoe3dPrice" class="show-price"></p>
      <button class="btn add-to-cart-3d">ADD TO CART</button>
    </div>
  </div>
</div>