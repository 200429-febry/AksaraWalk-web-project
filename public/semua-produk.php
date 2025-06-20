<?php 
    // Memanggil bagian header halaman
    include 'header.php'; 
?>

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

        <div class="grid-wrapper" id="product-grid">
            <p style="color: white; text-align: center; width: 100%;">Memuat produk...</p>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productGrid = document.getElementById('product-grid');
    const filterButtons = document.querySelectorAll(".btn-col");
    let allProducts = []; // To store all fetched products

    // Function to format Rupiah
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }

    // Function to render products
    function renderProducts(productsToRender) {
        productGrid.innerHTML = ''; // Clear existing products
        if (productsToRender.length === 0) {
            productGrid.innerHTML = '<p style="color: white; text-align: center; width: 100%;">Tidak ada produk ditemukan dalam kategori ini.</p>';
            return;
        }

        productsToRender.forEach(product => {
            const productCard = document.createElement('div');
            productCard.classList.add('col', 'collection-item');
            productCard.setAttribute('data-item', product.category.toLowerCase());
            
            // Add a simple star rating (can be dynamic if you have review data)
            const ratingHtml = `
                <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
            `;

            // Create options for sizes
            let sizeOptionsHtml = '';
            if (product.sizes && product.sizes.length > 0) {
                sizeOptionsHtml = `<select class="product-size-select">`;
                product.sizes.forEach(size => {
                    sizeOptionsHtml += `<option value="${size}">Size ${size}</option>`;
                });
                sizeOptionsHtml += `</select>`;
            } else {
                sizeOptionsHtml = `<p class="no-size-available" style="color: #ccc; font-size: 0.8em; margin-top: 5px;">Ukuran tidak tersedia</p>`;
            }

            productCard.innerHTML = `
                <figure><img src="${product.image}" alt="${product.name}"></figure>
                <div class="col-body">
                    ${ratingHtml}
                    <h3 class="heading-three">${product.name}</h3>
                    <p class="sub-heading">${product.category}</p>
                    <div class="size-selection" style="margin-bottom: 10px;">
                        ${sizeOptionsHtml}
                    </div>
                    <div class="col-footer">
                        <p class="show-price">${formatRupiah(product.price)}</p>
                        <button class="show-btn btn add-to-cart-btn" 
                                data-id="${product.id}" 
                                data-name="${product.name}" 
                                data-price="${product.price}" 
                                data-image="${product.image}">buy</button>
                    </div>
                </div>
            `;
            productGrid.appendChild(productCard);
        });
        // Re-run the 3D viewer button attachment for newly rendered products
        attach3DViewButtons(); 
        // Re-run heart icon attachment for newly rendered products
        attachHeartIcons();
        // Re-attach cart button event listeners with size selection
        attachCartButtons();
    }

    // Function to attach 3D view buttons (copied from index.php script)
    function attach3DViewButtons() {
        document.querySelectorAll('.collection-item, .arrival .col').forEach(card => {
            // Check if the 3D button already exists to prevent duplicates
            if (card.querySelector('.view-3d-btn')) return; 

            const buyBtn = card.querySelector('.show-btn');
            if (!buyBtn) return; // Ensure buy button exists

            const newBtn = buyBtn.cloneNode(true);
            newBtn.textContent = '3D';
            newBtn.classList.add('view-3d-btn');
            newBtn.style.marginTop = '10px'; // Add some spacing
            
            // Insert after the buy button
            if (buyBtn.parentNode) {
                buyBtn.parentNode.insertBefore(newBtn, buyBtn.nextSibling);
            }

            newBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const productName = card.querySelector('.heading-three').textContent.trim();
                const productPrice = card.querySelector('.show-price').textContent.trim();
                const productImg = card.querySelector('img').src;

                // Ensure modelMap is accessible or defined here if not global
                const modelMap = {
                    "nike air max 94": "models/nike_air_max_94.fbx",
                    "nike air huarache run ultra": "models/nike_air_huarache.fbx",
                    "nike air jordan 1 mid retro reverse new love": "models/air_jordan.fbx",
                    "adidas zx flux multicolor": "models/adidas_zx.fbx",
                    "nike air max 2015 \"dark obsidian\"": "models/nike_air_max_2015_dark_obsidian.fbx",
                    "nike air zoom pegasus": "models/nike_air_zoom_pegasus.fbx",
                    "puma basket burgundy": "models/puma_basket_burgundy.fbx",
                    "nike air jordan 11 concord": "models/nike_air_jordan_11_concord.fbx",
                    "nike air force 1": "models/nike_air_force_1.fbx",
                    "keds triumph white metallic": "models/keds_triumph_white_metallic.fbx",
                    "nike air pro max": "models/nike_air_pro_max.fbx",
                    "precise flexnit kurven": "models/precise_flexnit_kurven.fbx",
                    "nike air jordan 11 \"banned\"": "models/nike_air_jordan_11_banned.fbx",
                    "nike kyrie 2 \"inferno\"": "models/nike_kyrie_2_inferno.fbah",
                    "nike blazer mid 77": "models/nike_blazer_mid_77.fbx",
                    "nike air max 1 \"infrared\"": "models/nike_air_max_1_infrared.fbx",
                    "hive running tilt": "models/hive_running_tilt.fbx",
                    "nike golf \"green lunar\"": "models/nike_golf_green_lunar.fbx",
                    "nike roshe one": "models/nike_roshe_one.fbx",
                    "converse chuck taylor 2018": "models/converse_chuck_taylor_2018.fbx"
                };

                // Assuming open3DViewer, init3DScene, load3DModel, animate are defined in global script.js or header.php
                // For proper modularity, these functions should be made globally accessible or passed.
                if (typeof open3DViewer !== 'undefined') {
                    open3DViewer(
                        modelMap[productName.toLowerCase()] || "models/default_shoe.fbx", 
                        productName, 
                        productPrice, 
                        productImg
                    );
                } else {
                    console.error("open3DViewer function not found. Ensure script.js is loaded correctly.");
                    alert("Fitur 3D tidak tersedia. Terjadi kesalahan.");
                }
            });
        });
    }

    // Function to attach heart icons (wishlist) - adapted from script.js
    function attachHeartIcons() {
        let wishlist = JSON.parse(localStorage.getItem('aksarawalk_wishlist')) || [];
        document.querySelectorAll('.collection-item, .arrival .col').forEach(card => {
            const productData = {
                id: card.querySelector('.add-to-cart-btn')?.dataset.id, // Use data-id from buy button
                name: card.querySelector('h3')?.textContent.trim(),
                price: card.querySelector('.show-price')?.textContent.trim(),
                image: card.querySelector('img')?.src
            };
            if (!productData.id || !productData.price) return;

            // Prevent adding multiple heart icons
            if (card.querySelector('.heart-icon')) return;

            const heartIconContainer = document.createElement('span');
            heartIconContainer.className = 'heart-icon';
            heartIconContainer.innerHTML = `<i class="fa-regular fa-heart"></i>`;
            card.querySelector('.col-body')?.prepend(heartIconContainer);
            
            const heartIcon = heartIconContainer.querySelector('i');
            const isInWishlist = wishlist.some(item => item.id === productData.id);
            heartIcon.classList.toggle('fa-solid', isInWishlist);
            heartIcon.classList.toggle('fa-regular', !isInWishlist);
            heartIcon.style.color = isInWishlist ? '#ff6b6b' : '';

            heartIconContainer.addEventListener('click', e => {
                e.stopPropagation();
                const index = wishlist.findIndex(item => item.id === productData.id);
                if (index > -1) {
                    wishlist.splice(index, 1);
                } else {
                    wishlist.push(productData);
                }
                localStorage.setItem('aksarawalk_wishlist', JSON.stringify(wishlist));
                
                // Update the icon for this specific card immediately
                const updatedIsInWishlist = wishlist.some(item => item.id === productData.id);
                heartIcon.classList.toggle('fa-solid', updatedIsInWishlist);
                heartIcon.classList.toggle('fa-regular', !updatedIsInWishlist);
                heartIcon.style.color = updatedIsInWishlist ? '#ff6b6b' : '';

                // You might also want to re-render the wishlist popup if it's open
                if (typeof renderWishlistItems !== 'undefined') {
                    renderWishlistItems();
                }
            });
        });
    }

    // Function to attach cart button event listeners (including size)
    function attachCartButtons() {
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            if (button.dataset.listenerAttached) return; // Prevent multiple listeners

            button.addEventListener('click', e => {
                e.stopPropagation();
                const productCard = e.target.closest('.collection-item');
                if (!productCard) return;

                const selectedSizeElement = productCard.querySelector('.product-size-select');
                const selectedSize = selectedSizeElement ? selectedSizeElement.value : 'N/A'; // Get selected size, default to N/A

                let cart = JSON.parse(localStorage.getItem('aksarawalk_cart')) || [];
                const productId = e.target.dataset.id;
                const productName = e.target.dataset.name;
                const productPrice = parseFloat(e.target.dataset.price); // Ensure price is a number
                const productImage = e.target.dataset.image;

                // Create a unique ID for cart item considering size
                const cartItemId = `${productId}-${selectedSize}`; 

                const existingItem = cart.find(item => item.cartId === cartItemId);

                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    cart.push({ 
                        cartId: cartItemId, // Unique ID for this specific size
                        id: productId,
                        name: productName,
                        price: productPrice,
                        image: productImage,
                        size: selectedSize, // Add selected size to cart item
                        quantity: 1 
                    });
                }
                localStorage.setItem('aksarawalk_cart', JSON.stringify(cart));
                if (typeof updateCartCounter !== 'undefined') updateCartCounter();
                if (typeof renderCartItems !== 'undefined') renderCartItems();
                const cartPopup = document.querySelector('.cart-popup');
                if (cartPopup) cartPopup.style.display = 'flex';
            });
            button.dataset.listenerAttached = true; // Mark listener as attached
        });
    }


    // Fetch products from JSON
    async function fetchProducts() {
        try {
            const response = await fetch('api/products.json'); // Adjust path if necessary
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            allProducts = await response.json();
            renderProducts(allProducts); // Render all products initially
            console.log("Products loaded successfully:", allProducts);
        } catch (error) {
            console.error('Error fetching products:', error);
            productGrid.innerHTML = '<p style="color: red; text-align: center; width: 100%;">Gagal memuat produk. Silakan coba lagi nanti.</p>';
        }
    }

    // Filter products when filter buttons are clicked
    filterButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            filterButtons.forEach(btn => btn.classList.remove("btn"));
            e.currentTarget.classList.add("btn");
            const filterValue = e.currentTarget.getAttribute("data-btn").toLowerCase();
            
            let filteredProducts = [];
            if (filterValue === "all") {
                filteredProducts = allProducts;
            } else {
                filteredProducts = allProducts.filter(product => product.category.toLowerCase() === filterValue);
            }
            renderProducts(filteredProducts);
        });
    });

    // Initial fetch of products when the page loads
    fetchProducts();
});
</script>

<?php 
    // Memanggil bagian footer halaman
    include 'footer.php'; 
?>