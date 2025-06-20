// Menunggu seluruh konten halaman HTML dimuat sebelum menjalankan JavaScript
document.addEventListener('DOMContentLoaded', function () {

    // ===================================================================
    // BAGIAN 1: PENGATURAN UI DASAR (NAVIGASI, FILTER, VIDEO)
    // ===================================================================

    // --- Logika untuk Navigasi Mobile (Burger Menu) ---
    const burgerIcon = document.querySelector(".burger_icon");
    if (burgerIcon) {
        burgerIcon.addEventListener("click", () => {
            const navUl = document.querySelector("header .nav nav ul");
            if (navUl) navUl.classList.toggle("active");
            burgerIcon.classList.toggle("fa-bars");
            burgerIcon.classList.toggle("fa-xmark");
        });
    }

    // --- Logika untuk Filter Kategori Produk ---
    const filterButtons = document.querySelectorAll(".btn-col");
    const productItems = document.querySelectorAll(".collection-item");
    if (filterButtons.length > 0 && productItems.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener("click", (e) => {
                filterButtons.forEach(btn => btn.classList.remove("btn"));
                e.currentTarget.classList.add("btn");
                const filterValue = e.currentTarget.getAttribute("data-btn");
                productItems.forEach(item => {
                    const itemCategory = item.getAttribute("data-item");
                    item.style.display = (filterValue === "all" || itemCategory === filterValue) ? 'block' : 'none';
                });
            });
        });
    }

    // --- Logika untuk Tombol Video ---
    const videoOverlay = document.querySelector('.video-overlay');
    if (videoOverlay) {
        const videoBtn = document.querySelector('.floating-video-btn');
        const closeVideo = document.querySelector('.close-video');
        if (videoBtn) {
            videoBtn.addEventListener('click', () => videoOverlay.classList.add('active'));
        }
        if (closeVideo) {
            closeVideo.addEventListener('click', () => {
                const video = videoOverlay.querySelector('video');
                if (video) video.pause();
                videoOverlay.classList.remove('active');
            });
        }
    }

    // ===================================================================
    // BAGIAN 2: LOGIKA KERANJANG BELANJA, FAVORIT & PEMBAYARAN
    // ===================================================================

    let cart = JSON.parse(localStorage.getItem('aksarawalk_cart')) || [];
    let wishlist = JSON.parse(localStorage.getItem('aksarawalk_wishlist')) || [];

    const elements = {
        cartIcon: document.querySelector('.cart-btn'),
        wishlistIcon: document.querySelector('.wishlist-btn'),
        cartCount: document.querySelector('.cart-count'),
        cartPopup: document.querySelector('.cart-popup'),
        wishlistPopup: document.querySelector('.wishlist-popup'),
        paymentModal: document.querySelector('.payment-modal'),
        allCloseButtons: document.querySelectorAll('.close-popup'),
        cartItemsContainer: document.querySelector('.cart-items'),
        wishlistItemsContainer: document.querySelector('.wishlist-items'),
        cartTotalElement: document.querySelector('.total-price'),
        allProductCards: document.querySelectorAll('.arrival .col, .collection-item'),
        checkoutButton: document.querySelector('.checkout-btn'),
        processPaymentBtn: document.querySelector('#process-payment-btn'),
        paymentOptions: document.querySelectorAll('.payment-option')
    };

    let selectedPaymentMethod = null;

    // --- Definisi Semua Fungsi ---
    const updateCartCounter = () => {
        if (!elements.cartCount) return;
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        elements.cartCount.textContent = totalItems;
        elements.cartCount.style.display = totalItems > 0 ? 'flex' : 'none';
    };

    const renderCartItems = () => {
        if (!elements.cartItemsContainer) return;
        elements.cartItemsContainer.innerHTML = cart.length === 0 ? '<p>Keranjang Anda kosong.</p>' :
            cart.map(item => `
                <div class="cart-item" data-id="${item.id}">
                    <img src="${item.image}" alt="${item.name}">
                    <div class="item-details"><h4>${item.name}</h4><p>${item.price} x ${item.quantity}</p></div>
                    <span class="remove-item" title="Hapus item">&times;</span>
                </div>`).join('');
        updateCartTotal();
    };
    
    const updateCartTotal = () => {
        if (!elements.cartTotalElement) return;
        const total = cart.reduce((sum, item) => {
            const priceString = String(item.price || '0');
            const priceNumber = parseInt(priceString.replace(/[^0-9]/g, ''));
            return sum + (priceNumber * item.quantity);
        }, 0);
        elements.cartTotalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
    };

    const renderWishlistItems = () => {
        if (!elements.wishlistItemsContainer) return;
        elements.wishlistItemsContainer.innerHTML = wishlist.length === 0 ? '<p>Koleksi favorit Anda kosong.</p>' :
            wishlist.map(item => `
                <div class="wishlist-item" data-id="${item.id}">
                    <img src="${item.image}" alt="${item.name}">
                    <div class="item-details"><h4>${item.name}</h4><p>${item.price}</p></div>
                    <span class="remove-item" title="Hapus item">&times;</span>
                </div>`).join('');
    };
    
    const updateHeartIcons = () => {
        if (elements.allProductCards.length === 0) return;
        elements.allProductCards.forEach(card => {
            const productId = card.querySelector('h3')?.textContent.trim();
            const heartIcon = card.querySelector('.heart-icon i');
            if (productId && heartIcon) {
                const isInWishlist = wishlist.some(item => item.id === productId);
                heartIcon.classList.toggle('fa-solid', isInWishlist);
                heartIcon.classList.toggle('fa-regular', !isInWishlist);
                heartIcon.style.color = isInWishlist ? '#ff6b6b' : '';
            }
        });
    };

    // --- Pendaftaran Semua Event Listener ---

    if (elements.allProductCards.length > 0) {
        elements.allProductCards.forEach(card => {
            const productData = {
                id: card.querySelector('h3')?.textContent.trim(),
                name: card.querySelector('h3')?.textContent.trim(),
                price: card.querySelector('.show-price')?.textContent.trim(),
                image: card.querySelector('img')?.src
            };
            if (!productData.id || !productData.price) return;

            if (!card.querySelector('.heart-icon')) {
                const heartIconContainer = document.createElement('span');
                heartIconContainer.className = 'heart-icon';
                heartIconContainer.innerHTML = `<i class="fa-regular fa-heart"></i>`;
                card.querySelector('.col-body')?.prepend(heartIconContainer);
                
                heartIconContainer.addEventListener('click', e => {
                    e.stopPropagation();
                    const index = wishlist.findIndex(item => item.id === productData.id);
                    if (index > -1) wishlist.splice(index, 1);
                    else wishlist.push(productData);
                    localStorage.setItem('aksarawalk_wishlist', JSON.stringify(wishlist));
                    renderWishlistItems();
                    updateHeartIcons();
                });
            }

            const buyButton = card.querySelector('.show-btn');
            if (buyButton) {
                buyButton.addEventListener('click', e => {
                    e.stopPropagation();
                    const existingItem = cart.find(item => item.id === productData.id);
                    if (existingItem) existingItem.quantity++;
                    else cart.push({ ...productData, quantity: 1 });
                    localStorage.setItem('aksarawalk_cart', JSON.stringify(cart));
                    updateCartCounter();
                    renderCartItems();
                    if (elements.cartPopup) elements.cartPopup.style.display = 'flex';
                });
            }
        });
    }

    if (elements.cartIcon) elements.cartIcon.addEventListener('click', () => { if(elements.cartPopup) elements.cartPopup.style.display = 'flex'; });
    if (elements.wishlistIcon) elements.wishlistIcon.addEventListener('click', () => { if(elements.wishlistPopup) elements.wishlistPopup.style.display = 'flex'; });
    if (elements.allCloseButtons.length > 0) elements.allCloseButtons.forEach(btn => btn.addEventListener('click', e => { e.target.closest('.popup').style.display = 'none'; }));

    document.body.addEventListener('click', e => {
        if (e.target.classList.contains('remove-item')) {
            const itemElement = e.target.closest('.cart-item, .wishlist-item');
            if (!itemElement) return;
            const itemId = itemElement.dataset.id;
            if (itemElement.classList.contains('cart-item')) {
                cart = cart.filter(item => item.id !== itemId);
                localStorage.setItem('aksarawalk_cart', JSON.stringify(cart));
                renderCartItems();
                updateCartCounter();
            } else if (itemElement.classList.contains('wishlist-item')) {
                wishlist = wishlist.filter(item => item.id !== itemId);
                localStorage.setItem('aksarawalk_wishlist', JSON.stringify(wishlist));
                renderWishlistItems();
                updateHeartIcons();
            }
        }
    });
    
    if (elements.checkoutButton) {
        elements.checkoutButton.addEventListener('click', e => {
            e.stopPropagation();
            if (cart.length > 0) {
                if (elements.cartPopup) elements.cartPopup.style.display = 'none';
                if (elements.paymentModal) elements.paymentModal.style.display = 'flex';
            } else {
                alert('Keranjang Anda kosong!');
            }
        });
    }

    if (elements.paymentOptions.length > 0) {
        elements.paymentOptions.forEach(option => {
            option.addEventListener('click', () => {
                elements.paymentOptions.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
                selectedPaymentMethod = option.dataset.method;
                if(elements.processPaymentBtn) elements.processPaymentBtn.disabled = false;
            });
        });
    }

    if (elements.processPaymentBtn) {
        elements.processPaymentBtn.addEventListener('click', () => {
            if (!selectedPaymentMethod) {
                alert('Silakan pilih metode pembayaran terlebih dahulu.');
                return;
            }
            alert(`Memproses pembayaran dengan ${selectedPaymentMethod}...\n(Ini adalah demo, tidak ada transaksi nyata)`);
            if (elements.paymentModal) elements.paymentModal.style.display = 'none';
        });
    }

    // --- Inisialisasi Saat Halaman Dimuat ---
    updateCartCounter();
    renderCartItems();
    renderWishlistItems();
    updateHeartIcons();
});

// ===================================================================
// BAGIAN BARU: LOGIKA UNTUK LIVE COMMENT FEED
// ===================================================================

// Pastikan kode ini berjalan setelah semua event listener lain terpasang
document.addEventListener('DOMContentLoaded', function () {

 // --- Daftar Nama & Komentar Random (Diperbanyak dan Internasional) ---
    const randomUsers = [
        "Rina", "Budi", "Joko", "Siti", "Ahmad", "Dewi", "Eko", "Putri", "Bayu", "Lina",
        "John", "Sarah", "Mike", "Emily", "David", "Jessica", "Chris", "Olivia", "Tom", "Sophia",
        "Chen", "Maria", "Carlos", "Lena", "Hiroshi", "Priya", "Omar", "Aisha", "Liam", "Mia"
    ]; //

    const randomComments = [
        "Sepatu yang ini keren banget!", //
        "Ada ukuran 42 gak?", //
        "Koleksi barunya mantap 👍", //
        "Akhirnya nemu toko yang lengkap.", //
        "Proses checkoutnya gampang.", //
        "Suka sama desainnya, minimalis.", //
        "Wishlist dulu ah...", //
        "Pengirimannya cepat gak ya?", //
        "Ada diskon tambahan?", //
        "Warnanya cuma ini aja?", //
        "Amazing shoes! Love the design.",
        "Do they ship worldwide?",
        "Such a great collection!",
        "Finally found a store with everything.",
        "Smooth checkout process.",
        "The minimalist design is perfect.",
        "Adding this to my wishlist!",
        "Is shipping fast?",
        "Any extra discounts available?",
        "Are there other colors?",
        "These sneakers are a must-have!",
        "Excellent quality, highly recommend.",
        "Looking forward to the new arrivals!",
        "Their customer service is top-notch.",
        "Perfect fit and really comfortable.",
        "Thinking about getting two pairs!",
        "Great value for money.","Can I get a personalized recommendation?",
        "Is there a loyalty program?",
        "Love the vibe of this store."
    ];

    const commentFeed = document.getElementById('live-comment-feed');

    function createRandomComment() {
        // Jangan tampilkan jika elemennya tidak ada di halaman
        if (!commentFeed) return;

        // Ambil nama dan komentar secara acak
        const user = randomUsers[Math.floor(Math.random() * randomUsers.length)];
        const text = randomComments[Math.floor(Math.random() * randomComments.length)];

        // Buat elemen HTML untuk komentar baru
        const commentBubble = document.createElement('div');
        commentBubble.className = 'comment-bubble';
        commentBubble.innerHTML = `<span class="username">${user}:</span> <span class="text">${text}</span>`;

        // Tambahkan komentar ke dalam feed
        commentFeed.appendChild(commentBubble);

        // Hapus komentar setelah animasinya selesai (8 detik) agar tidak menumpuk
        setTimeout(() => {
            commentBubble.remove();
        }, 8000);
    }

    // Jalankan fungsi createRandomComment setiap beberapa detik
    // Angka 4000 berarti 4 detik. Anda bisa mengubahnya.
    setInterval(createRandomComment, 4000);

});
// ===================================================================
// BAGIAN KHUSUS LOGIKA UNTUK AISARA 2.5 CHATBOT (TERMASUK DRAG-AND-DROP)
// ===================================================================

document.addEventListener('DOMContentLoaded', function () {
    const aisaraChatbot = document.getElementById('aisaraChatbot');
    const openChatbotBtn = document.getElementById('openChatbotBtn');
    const closeChatbotBtn = document.getElementById('closeChatbot');
    const chatbotMessages = document.getElementById('chatbotMessages');
    const chatInput = document.getElementById('chatInput');
    const sendMessageBtn = document.getElementById('sendMessageBtn');
    const chatbotHeader = document.querySelector('.chatbot-header'); // Ambil header untuk event drag

    // Variabel untuk drag-and-drop
    let isDragging = false;
    let offsetX, offsetY; // Offset mouse dari sudut kiri atas chatbot
    let initialX, initialY; // Posisi awal chatbot

    if (aisaraChatbot && openChatbotBtn && closeChatbotBtn && chatbotMessages && chatInput && sendMessageBtn && chatbotHeader) {
        // Event listener untuk membuka chatbot
        openChatbotBtn.addEventListener('click', () => {
            aisaraChatbot.style.display = 'flex';
            openChatbotBtn.style.display = 'none';
        });

        // Event listener untuk menutup chatbot
        closeChatbotBtn.addEventListener('click', () => {
            aisaraChatbot.style.display = 'none';
            openChatbotBtn.style.display = 'flex';
        });

        // --- Logika Drag-and-Drop ---
        chatbotHeader.addEventListener('mousedown', (e) => {
            isDragging = true;
            chatbotHeader.classList.add('dragging'); // Tambahkan kelas grabbing kursor
            // Dapatkan offset mouse relatif terhadap elemen chatbot
            offsetX = e.clientX - aisaraChatbot.getBoundingClientRect().left;
            offsetY = e.clientY - aisaraChatbot.getBoundingClientRect().top;

            // Dapatkan posisi awal chatbot (relative to viewport)
            initialX = aisaraChatbot.getBoundingClientRect().left;
            initialY = aisaraChatbot.getBoundingClientRect().top;

            // Pastikan chatbot menggunakan 'absolute' atau 'fixed' untuk bisa diubah 'left'/'top'
            aisaraChatbot.style.position = 'fixed'; // Pastikan posisinya fixed
            aisaraChatbot.style.cursor = 'grabbing'; // Ubah kursor saat menyeret
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;

            // Hitung posisi baru berdasarkan posisi mouse saat ini dan offset awal
            let newX = e.clientX - offsetX;
            let newY = e.clientY - offsetY;

            // Batasi agar tidak keluar dari viewport
            const maxX = window.innerWidth - aisaraChatbot.offsetWidth;
            const maxY = window.innerHeight - aisaraChatbot.offsetHeight;

            newX = Math.max(0, Math.min(newX, maxX));
            newY = Math.max(0, Math.min(newY, maxY));

            aisaraChatbot.style.left = newX + 'px';
            aisaraChatbot.style.top = newY + 'px';
            aisaraChatbot.style.bottom = 'auto'; // Pastikan bottom tidak mempengaruhi top
            aisaraChatbot.style.right = 'auto'; // Pastikan right tidak mempengaruhi left
        });

        document.addEventListener('mouseup', () => {
            if (isDragging) {
                isDragging = false;
                chatbotHeader.classList.remove('dragging'); // Hapus kelas grabbing kursor
                aisaraChatbot.style.cursor = 'grab'; // Kembalikan kursor ke grab
            }
        });

        // --- Fungsi Chatbot (tetap sama) ---
        const addMessage = (text, sender) => {
            const messageBubble = document.createElement('div');
            messageBubble.classList.add('message-bubble', sender);
            messageBubble.textContent = text;
            chatbotMessages.appendChild(messageBubble);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        };

        const sendMessage = async () => {
            const message = chatInput.value.trim();
            if (message === '') return;

            addMessage(message, 'user');
            chatInput.value = '';

            const typingIndicator = document.createElement('div');
            typingIndicator.classList.add('message-bubble', 'bot', 'typing-indicator');
            typingIndicator.textContent = 'AISARA 2.5 sedang mengetik...';
            chatbotMessages.appendChild(typingIndicator);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;

            try {
                const response = await fetch('ai_chat_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();

                typingIndicator.remove();

                if (response.ok && data.status === 'success') {
                    addMessage(data.reply, 'bot');
                } else {
                    addMessage('Maaf, saya tidak bisa memproses permintaan Anda saat ini. ' + (data.error || ''), 'bot');
                    console.error('AI Service Error:', data.error);
                }
            } catch (error) {
                typingIndicator.remove();
                addMessage('Terjadi kesalahan koneksi ke AISARA 2.5. Mohon coba lagi.', 'bot');
                console.error('Fetch Error:', error);
            }
        };

        sendMessageBtn.addEventListener('click', sendMessage);
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        const styleSheet = document.createElement("style");
        styleSheet.type = "text/css";
        styleSheet.innerText = `
            .typing-indicator {
                font-style: italic;
                color: #aaa;
                animation: pulse-typing 1.5s infinite;
            }
            @keyframes pulse-typing {
                0% { opacity: 0.5; }
                50% { opacity: 1; }
                100% { opacity: 0.5; }
            }
        `;
        document.head.appendChild(styleSheet);

    } else {
        console.warn('AISARA 2.5 chatbot elements not found. Make sure HTML elements exist.');
    }
});
// ===================================================================
// BAGIAN BARU: KONTROL GESTUR TANGAN
// ===================================================================

document.addEventListener('DOMContentLoaded', function () {
    const enableGestureBtn = document.getElementById('enableGestureBtn');
    const webcam = document.getElementById('webcam');
    const gestureCanvas = document.getElementById('gestureCanvas');
    const ctx = gestureCanvas.getContext('2d');

    let model = null;
    let videoStream = null;
    let gestureActive = false;

    // --- Fungsi Inisialisasi Kamera dan Model ---
    const initGestureControl = async () => {
        if (gestureActive) return;

        // Minta izin kamera
        try {
            videoStream = await navigator.mediaDevices.getUserMedia({ video: true });
            webcam.srcObject = videoStream;
            webcam.onloadedmetadata = () => {
                webcam.play();
                gestureCanvas.width = webcam.videoWidth;
                gestureCanvas.height = webcam.videoHeight;
                // Opsional: tampilkan video/canvas untuk debugging
                // webcam.style.display = 'block';
                // gestureCanvas.style.display = 'block';
            };
        } catch (err) {
            console.error("Error accessing camera: ", err);
            alert("Gagal mengakses kamera. Pastikan izin kamera diberikan.");
            return;
        }

        // Muat model Handpose
        try {
            model = await handpose.load();
            console.log("Handpose model loaded.");
            gestureActive = true;
            detectGestures(); // Mulai deteksi gestur
            alert("Kontrol gestur diaktifkan! Coba gerakan tangan Anda.");
        } catch (err) {
            console.error("Error loading Handpose model: ", err);
            alert("Gagal memuat model gestur. Coba lagi.");
            stopGestureControl();
        }
    };

    // --- Fungsi Deteksi Gestur ---
    const detectGestures = async () => {
        if (!gestureActive || !model || !webcam.videoWidth) {
            requestAnimationFrame(detectGestures); // Lanjutkan loop
            return;
        }

        ctx.clearRect(0, 0, gestureCanvas.width, gestureCanvas.height);
        ctx.drawImage(webcam, 0, 0, gestureCanvas.width, gestureCanvas.height); // Gambar frame kamera ke canvas

        const predictions = await model.estimateHands(webcam);

        if (predictions.length > 0) {
            for (let i = 0; i < predictions.length; i++) {
                const hand = predictions[i];
                // predictions[i].landmarks adalah array 21 titik (x,y,z) untuk setiap jari dan telapak tangan.
                // Anda perlu menganalisis landmarks ini untuk menentukan gestur.

                drawHand(hand.landmarks, ctx); // Fungsi helper untuk menggambar landmarks

                // --- Logika Deteksi Gestur Sederhana ---
                const thumbTip = hand.landmarks[4];
                const indexFingerTip = hand.landmarks[8];
                const middleFingerTip = hand.landmarks[12];
                const ringFingerTip = hand.landmarks[16];
                const pinkyTip = hand.landmarks[20];
                const wrist = hand.landmarks[0]; // Pergelangan tangan

                // Contoh Gestur: Scroll ke bawah (buka telapak tangan ke bawah)
                // Ini sangat rudimenter, perlu algoritma yang lebih baik.
                // Logika ini mungkin tidak akurat dan perlu penyesuaian/pelatihan lebih lanjut.
                const isHandOpen = (indexFingerTip[1] < wrist[1] && middleFingerTip[1] < wrist[1] && ringFingerTip[1] < wrist[1] && pinkyTip[1] < wrist[1]);
                const isThumbUp = (thumbTip[1] < wrist[1] && thumbTip[0] > wrist[0]); // Jempol ke atas

                if (isHandOpen) {
                    // Jika telapak tangan terbuka, coba scroll ke bawah
                    window.scrollBy(0, 50); // Scroll 50px ke bawah
                    console.log("Scrolling down!");
                }
                // Anda bisa menambahkan gestur lain, misalnya:
                // if (isThumbUp) {
                //     window.scrollBy(0, -50); // Scroll 50px ke atas
                //     console.log("Scrolling up!");
                // }

                // Jika Anda ingin mengontrol kursor virtual (bukan kursor sistem)
                // Anda bisa membuat elemen div kustom dan memindahkannya berdasarkan posisi jari.
            }
        }

        requestAnimationFrame(detectGestures); // Lanjutkan loop deteksi
    };

    // --- Fungsi Helper untuk Menggambar Tangan (Opsional) ---
    const drawHand = (landmarks, ctx) => {
        // Ini adalah implementasi dasar untuk menggambar titik dan garis.
        // Untuk menggambar yang lebih baik, gunakan @mediapipe/drawing_utils
        const fingerJoints = [
            [0, 1, 2, 3, 4],     // Thumb
            [0, 5, 6, 7, 8],     // Index finger
            [0, 9, 10, 11, 12],  // Middle finger
            [0, 13, 14, 15, 16], // Ring finger
            [0, 17, 18, 19, 20]  // Pinky finger
        ];

        // Gambar koneksi
        for (let i = 0; i < fingerJoints.length; i++) {
            const path = new Path2D();
            path.moveTo(landmarks[fingerJoints[i][0]][0], landmarks[fingerJoints[i][0]][1]);
            for (let j = 1; j < fingerJoints[i].length; j++) {
                path.lineTo(landmarks[fingerJoints[i][j]][0], landmarks[fingerJoints[i][j]][1]);
            }
            ctx.strokeStyle = 'rgba(255, 255, 0, 0.7)'; // Kuning
            ctx.lineWidth = 2;
            ctx.stroke(path);
        }

        // Gambar titik
        for (let i = 0; i < landmarks.length; i++) {
            ctx.beginPath();
            ctx.arc(landmarks[i][0], landmarks[i][1], 5, 0, 2 * Math.PI); // Radius 5px
            ctx.fillStyle = 'rgba(255, 0, 255, 0.9)'; // Magenta
            ctx.fill();
        }
    };


    // --- Fungsi untuk Menghentikan Kontrol Gestur ---
    const stopGestureControl = () => {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            webcam.srcObject = null;
        }
        gestureActive = false;
        model = null; // Hapus model untuk membebaskan memori
        // Opsional: sembunyikan video/canvas
        // webcam.style.display = 'none';
        // gestureCanvas.style.display = 'none';
        console.log("Kontrol gestur dihentikan.");
    };

    // --- Event Listener Tombol Enable Gesture ---
    if (enableGestureBtn) {
        enableGestureBtn.addEventListener('click', () => {
            if (!gestureActive) {
                initGestureControl();
            } else {
                stopGestureControl();
            }
        });
    }
});

// ===================================================================
// BAGIAN BARU: KONTROL GESTUR TANGAN DENGAN WEBCAM
// ===================================================================

// Pastikan kode ini di dalam document.addEventListener('DOMContentLoaded', function () { ... });
    const enableGestureBtn = document.getElementById('enableGestureBtn');
    const webcam = document.getElementById('webcam');
    const gestureCanvas = document.getElementById('gestureCanvas');
    const ctx = gestureCanvas.getContext('2d');

    let model = null;
    let videoStream = null;
    let gestureActive = false;
    let handsDetector = null; // Menggunakan hand-pose-detection API

    // --- Fungsi Inisialisasi Kamera dan Model ---
    const initGestureControl = async () => {
        if (gestureActive) return;

        // Minta izin kamera
        try {
            videoStream = await navigator.mediaDevices.getUserMedia({ video: true });
            webcam.srcObject = videoStream;
            await new Promise((resolve) => {
                webcam.onloadedmetadata = () => {
                    webcam.play();
                    gestureCanvas.width = webcam.videoWidth;
                    gestureCanvas.height = webcam.videoHeight;
                    // Opsional: tampilkan video/canvas untuk debugging
                    // webcam.style.display = 'block';
                    // gestureCanvas.style.display = 'block';
                    resolve();
                };
            });
        } catch (err) {
            console.error("Error accessing camera: ", err);
            alert("Gagal mengakses kamera. Pastikan izin kamera diberikan dan browser Anda mendukungnya.");
            return;
        }

        // Muat model Handpose Detection
        try {
            const detectorConfig = {
                runtime: 'mediapipe', // Menggunakan backend MediaPipe untuk performa
                modelType: 'full', // 'lite' atau 'full'
                solutionPath: 'https://cdn.jsdelivr.net/npm/@mediapipe/hands' // Path ke paket MediaPipe Hands
            };
            handsDetector = await handPoseDetection.createDetector(handPoseDetection.SupportedModels.MediaPipeHands, detectorConfig);
            console.log("MediaPipeHands detector loaded.");
            
            gestureActive = true;
            alert("Kontrol gestur diaktifkan! Arahkan tangan Anda ke kamera.");
            detectGesturesLoop(); // Mulai loop deteksi gestur
        } catch (err) {
            console.error("Error loading Handpose detector: ", err);
            alert("Gagal memuat model deteksi gestur. Coba lagi atau periksa koneksi internet.");
            stopGestureControl();
        }
    };

    // --- Fungsi Deteksi Gestur dalam Loop ---
    const detectGesturesLoop = async () => {
        if (!gestureActive) return;

        ctx.clearRect(0, 0, gestureCanvas.width, gestureCanvas.height);
        ctx.drawImage(webcam, 0, 0, gestureCanvas.width, gestureCanvas.height); // Gambar frame kamera ke canvas

        const estimationConfig = {flipHorizontal: false}; // Jangan flip horizontal jika Anda membalik video di CSS
        const hands = await handsDetector.estimateHands(webcam, estimationConfig);

        if (hands.length > 0) {
            for (let i = 0; i < hands.length; i++) {
                const hand = hands[i];
                // Menggambar landmarks tangan
                if (hand.keypoints3D) { // Gunakan keypoints3D jika tersedia
                    drawConnectors(ctx, hand.keypoints3D, Hands.HAND_CONNECTIONS, {color: '#00FF00', lineWidth: 5});
                    drawLandmarks(ctx, hand.keypoints3D, {color: '#FF0000', lineWidth: 2});
                } else if (hand.keypoints) { // Fallback ke keypoints 2D
                    drawConnectors(ctx, hand.keypoints, Hands.HAND_CONNECTIONS, {color: '#00FF00', lineWidth: 5});
                    drawLandmarks(ctx, hand.keypoints, {color: '#FF0000', lineWidth: 2});
                }

                // --- Logika Deteksi Gestur Sederhana (Contoh: Scroll Vertikal) ---
                // Ini sangat dasar dan butuh penyempurnaan
                // Hand.keypoints: array 21 titik, setiap titik punya x, y, z
                // Index titik: 0=wrist, 4=thumb tip, 8=index tip, 12=middle tip, 16=ring tip, 20=pinky tip

                // Contoh sederhana: Scroll ke bawah jika jari telunjuk di bawah pergelangan tangan, scroll ke atas jika di atas
                const wristY = hand.keypoints[0].y;
                const indexFingerTipY = hand.keypoints[8].y;
                const middleFingerTipY = hand.keypoints[12].y;
                const thumbTipX = hand.keypoints[4].x;
                const indexFingerTipX = hand.keypoints[8].x;

                // Gestur "Telapak Tangan Terbuka" (rudimenter) untuk scroll
                // Cek apakah jari-jari utama terbuka (y tip jari > y bagian bawah jari)
                const isIndexOpen = hand.keypoints[8].y < hand.keypoints[6].y;
                const isMiddleOpen = hand.keypoints[12].y < hand.keypoints[10].y;
                const isRingOpen = hand.keypoints[16].y < hand.keypoints[14].y;
                const isPinkyOpen = hand.keypoints[20].y < hand.keypoints[18].y;

                const isHandOpenGesture = isIndexOpen && isMiddleOpen && isRingOpen && isPinkyOpen;
                
                // Gestur Gulir Vertikal
                // Jika tangan terbuka, tentukan arah gulir berdasarkan pergerakan
                if (isHandOpenGesture) {
                    // Gunakan posisi pergelangan tangan untuk deteksi gerakan vertikal
                    // Anda perlu melacak perubahan posisi wrist dari frame ke frame
                    // Untuk demo sederhana, kita bisa membuat asumsi:
                    const sensitivity = 5; // Semakin kecil, semakin sensitif
                    if (wristY < gestureCanvas.height / 3) { // Jika tangan di bagian atas layar
                        window.scrollBy(0, -sensitivity); // Scroll ke atas
                        console.log("Scrolling up...");
                    } else if (wristY > gestureCanvas.height * 2 / 3) { // Jika tangan di bagian bawah layar
                        window.scrollBy(0, sensitivity); // Scroll ke bawah
                        console.log("Scrolling down...");
                    }
                }

                // Gestur Klik (contoh: ibu jari dan telunjuk bersentuhan)
                // Ini juga sangat rudimenter dan butuh algoritma yang lebih robust
                const thumbIndexDistance = Math.sqrt(
                    Math.pow(thumbTipX - indexFingerTipX, 2) + 
                    Math.pow(hand.keypoints[4].y - hand.keypoints[8].y, 2)
                );
                const clickThreshold = 30; // Sesuaikan threshold ini
                if (thumbIndexDistance < clickThreshold && !window.hasClicked) {
                    console.log("Click gesture detected!");
                    // Simulasi klik pada elemen di tengah layar atau elemen terdekat
                    // Ini tidak akan menggerakkan kursor sistem
                    const targetElement = document.elementFromPoint(window.innerWidth / 2, window.innerHeight / 2);
                    if (targetElement) {
                        targetElement.click(); // Memicu event klik pada elemen DOM
                        window.hasClicked = true; // Flag untuk menghindari multiple clicks
                        setTimeout(() => window.hasClicked = false, 500); // Reset flag setelah 0.5 detik
                    }
                }
            }
        }

        requestAnimationFrame(detectGesturesLoop); // Lanjutkan loop deteksi
    };

    // --- Fungsi untuk Menghentikan Kontrol Gestur ---
    const stopGestureControl = () => {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            webcam.srcObject = null;
        }
        gestureActive = false;
        handsDetector = null; // Hapus detector untuk membebaskan memori
        // Opsional: sembunyikan video/canvas
        // webcam.style.display = 'none';
        // gestureCanvas.style.display = 'none';
        console.log("Kontrol gestur dihentikan.");
    };

    // --- Event Listener Tombol Enable Gesture ---
    if (enableGestureBtn) {
        enableGestureBtn.addEventListener('click', () => {
            if (!gestureActive) {
                initGestureControl();
            } else {
                stopGestureControl();
            }
        });
    }

// Akhir dari document.addEventListener('DOMContentLoaded', function () { ... });

// ===================================================================
// BAGIAN KONTROL SUARA DENGAN WEBSPEECH API (MODIFIKASI UNTUK KLIK ELEMEN)
// ===================================================================

// Pastikan kode ini berada di dalam document.addEventListener('DOMContentLoaded', function () { ... });
    const enableVoiceBtn = document.getElementById('enableVoiceBtn');
    let recognition = null; // Variabel untuk SpeechRecognition

    // --- Inisialisasi Web Speech API ---
    const initVoiceControl = () => {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            alert("Browser Anda tidak mendukung Web Speech API. Silakan gunakan Chrome terbaru.");
            enableVoiceBtn.style.display = 'none'; // Sembunyikan tombol jika tidak didukung
            return;
        }

        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        recognition = new SpeechRecognition();
        recognition.continuous = true; // Terus-menerus mendengarkan
        recognition.interimResults = false; // Hanya berikan hasil final
        recognition.lang = 'id-ID'; // Bahasa yang diharapkan (misal: Indonesia)
        // recognition.lang = 'en-US'; // Atau bahasa Inggris

        recognition.onstart = () => {
            console.log('Voice recognition started...');
            enableVoiceBtn.classList.add('active');
            enableVoiceBtn.title = 'Kontrol Suara Aktif (Klik untuk Nonaktifkan)';
        };

        recognition.onresult = (event) => {
            const last = event.results.length - 1;
            const command = event.results[last][0].transcript.toLowerCase().trim();
            console.log('Voice Command:', command);
            processVoiceCommand(command);
        };

        recognition.onerror = (event) => {
            console.error('Voice recognition error:', event.error);
            if (event.error === 'no-speech' || event.error === 'not-allowed') {
                alert("Tidak ada suara terdeteksi atau izin mikrofon ditolak. Pastikan Anda berbicara dengan jelas dan memberikan izin mikrofon.");
            }
            stopVoiceControl();
        };

        recognition.onend = () => {
            console.log('Voice recognition ended. Restarting...');
            if (enableVoiceBtn.classList.contains('active')) { // Hanya restart jika masih aktif
                recognition.start();
            }
        };

        // Mulai mendengarkan
        recognition.start();
    };

    // --- Proses Perintah Suara ---
    const processVoiceCommand = (command) => {
        const normalizedCommand = command.toLowerCase().replace(/\s+/g, ' ').trim();

        // 1. Perintah Navigasi/Scroll Umum
        if (normalizedCommand.includes("gulir bawah") || normalizedCommand.includes("scroll down") || normalizedCommand.includes("bawah")) {
            window.scrollBy(0, 200);
            console.log("Scrolling down by voice command.");
        } else if (normalizedCommand.includes("gulir atas") || normalizedCommand.includes("scroll up") || normalizedCommand.includes("atas")) {
            window.scrollBy(0, -200);
            console.log("Scrolling up by voice command.");
        } else if (normalizedCommand.includes("buka obrolan") || normalizedCommand.includes("open chat") || normalizedCommand.includes("aisara")) {
            if (aisaraChatbot.style.display === 'none' || aisaraChatbot.style.display === '') {
                openChatbotBtn.click();
                console.log("Opening chatbot by voice command.");
            }
        } else if (normalizedCommand.includes("tutup obrolan") || normalizedCommand.includes("close chat")) {
            if (aisaraChatbot.style.display === 'flex') {
                closeChatbotBtn.click();
                console.log("Closing chatbot by voice command.");
            }
        } else if (normalizedCommand.includes("tonton video")) { // Perintah baru untuk membuka video overlay
            const videoBtn = document.querySelector('.floating-video-btn');
            if (videoBtn) videoBtn.click();
            console.log("Opening video overlay by voice command.");
        } else if (normalizedCommand.includes("tutup video")) { // Perintah baru untuk menutup video overlay
            const videoOverlay = document.querySelector('.video-overlay');
            const closeVideoBtn = document.querySelector('.close-video');
            if (videoOverlay && videoOverlay.classList.contains('active') && closeVideoBtn) {
                closeVideoBtn.click();
            }
            console.log("Closing video overlay by voice command.");
        }

        // 2. Perintah "Klik" yang Lebih Cerdas
        // Contoh: "klik home", "klik about", "klik beli", "klik sign in"
        if (normalizedCommand.startsWith("klik ")) {
            const targetText = normalizedCommand.substring(5).trim(); // Ambil teks setelah "klik "
            let clickedElement = null;

            // Pencarian berdasarkan teks pada link (a), tombol (button), input submit, atau heading
            const elementsToSearch = document.querySelectorAll('a, button, input[type="submit"], h1, h2, h3, h4, h5, h6, span, p');

            for (const el of elementsToSearch) {
                const elText = el.textContent.toLowerCase().trim();
                const elValue = el.value ? el.value.toLowerCase().trim() : ''; // Untuk input submit

                // Cek apakah teks elemen persis sama atau mengandung targetText
                if (elText === targetText || elValue === targetText ||
                    elText.includes(targetText) || elValue.includes(targetText)) {
                    
                    // Prioritaskan elemen yang visible dan interaktif
                    const style = window.getComputedStyle(el);
                    const isVisible = style.display !== 'none' && style.visibility !== 'hidden' && style.opacity !== '0';
                    const isInteractive = el.tagName === 'A' || el.tagName === 'BUTTON' || el.type === 'submit' || el.onclick;

                    if (isVisible && isInteractive) { // Hanya klik jika visible dan interaktif
                        el.click();
                        clickedElement = el;
                        console.log(`Clicked element by voice: ${el.tagName} with text "${elText}"`);
                        break; // Klik yang pertama ditemukan dan keluar
                    }
                }
            }

            if (!clickedElement) {
                console.log(`No clickable element found for voice command "klik ${targetText}"`);
                // Anda bisa tambahkan umpan balik suara, "Maaf, tidak menemukan elemen itu."
            }
        } else {
            console.log("Unrecognized general voice command:", normalizedCommand);
        }
    };

    // --- Fungsi untuk Menghentikan Kontrol Suara (tetap sama) ---
    const stopVoiceControl = () => {
        if (recognition) {
            recognition.stop();
            recognition = null;
        }
        enableVoiceBtn.classList.remove('active');
        enableVoiceBtn.title = 'Aktifkan Kontrol Suara';
        console.log('Voice recognition stopped.');
    };

    // --- Event Listener Tombol Enable Voice (tetap sama) ---
    if (enableVoiceBtn) {
        enableVoiceBtn.addEventListener('click', () => {
            if (!enableVoiceBtn.classList.contains('active')) { // Jika tidak aktif
                initVoiceControl();
            } else { // Jika sudah aktif
                stopVoiceControl();
            }
        });
    }

// Akhir dari document.addEventListener('DOMContentLoaded', function () { ... });

// script.js - Penambahan Efek Futuristik

// Smooth Scroll (jika ada anchor link)
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// AOS - Animate On Scroll (Pastikan library AOS sudah di-link di header.php)
AOS.init({
    duration: 1000,
    easing: 'ease-out-quad',
    once: true, // Animasi hanya terjadi sekali saat scroll masuk viewport
});

// Efek Partikel Latar Belakang (Opsional, butuh library seperti Particle.js atau kustom)
// Ini adalah konsep, implementasi penuh akan membutuhkan file JS terpisah atau library.
// Contoh: Menggambar partikel kecil yang bergerak di latar belakang section tertentu.
function createParticleEffect(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    // Ini adalah contoh sangat sederhana,
    // gunakan library seperti particles.js untuk efek yang lebih baik
    for (let i = 0; i < 50; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        particle.style.width = `${Math.random() * 3 + 1}px`;
        particle.style.height = particle.style.width;
        particle.style.backgroundColor = `rgba(0, 240, 255, ${Math.random() * 0.5 + 0.2})`;
        particle.style.position = 'absolute';
        particle.style.left = `${Math.random() * 100}%`;
        particle.style.top = `${Math.random() * 100}%`;
        particle.style.opacity = 0; // Mulai dari transparan
        particle.style.animation = `particleFadeInMove ${Math.random() * 5 + 3}s linear infinite alternate`;
        container.appendChild(particle);
    }
}

// Tambahkan CSS untuk partikel ini di style.css
/*
.particle {
    border-radius: 50%;
    filter: blur(1px); // Efek blur
    z-index: -1;
}

@keyframes particleFadeInMove {
    0% { opacity: 0; transform: translate(0, 0); }
    20% { opacity: 1; }
    80% { opacity: 1; }
    100% { opacity: 0; transform: translate(calc(var(--rand-x) * 100px), calc(var(--rand-y) * 100px)); }
}
*/
// (Untuk implementasi di atas, Anda perlu menghasilkan --rand-x dan --rand-y secara acak di JS)


// Menambahkan Haptic Feedback pada interaksi penting (Mobile Only)
function applyHapticFeedback(elementSelector) {
    const elements = document.querySelectorAll(elementSelector);
    elements.forEach(el => {
        el.addEventListener('click', () => {
            if ('vibrate' in navigator) {
                // Pola getaran pendek saat diklik
                navigator.vibrate(50);
            }
        });
    });
}

// Contoh: Terapkan haptic feedback pada tombol beli dan wishlist
applyHapticFeedback('.btn-buy');
applyHapticFeedback('.product-item .fa-heart'); // Jika ikon wishlist memiliki kelas ini

// Implementasi efek hover untuk 3D Model Viewer Button
// Ini akan membuat pop-up model 3D terasa lebih futuristik
document.querySelectorAll('.btn-3d-model').forEach(button => {
    button.addEventListener('mouseenter', () => {
        // Tambahkan kelas untuk animasi glow atau efek lain
        button.classList.add('glowing');
    });
    button.addEventListener('mouseleave', () => {
        button.classList.remove('glowing');
    });
});

// Pastikan CSS untuk '.glowing' juga ditambahkan di style.css
/*
.btn-3d-model.glowing {
    box-shadow: 0 0 15px var(--accent-neon-blue), 0 0 25px var(--accent-neon-purple);
    transform: scale(1.05);
}
*/

// public/script.js

document.addEventListener('DOMContentLoaded', function () {
    // ... (Your existing JavaScript code) ...

    // ===================================================================
    // FLOATING DASHBOARD STATISTIK (DIPERBARUI UNTUK SCROLL-TRIGGERED)
    // ===================================================================

    const floatingDashboard = document.getElementById('floatingDashboard');
    const toggleDashboardBtn = document.getElementById('toggleDashboardBtn');
    const refreshDashboardBtn = document.getElementById('refreshDashboardBtn');
    const statCustomers = document.getElementById('statCustomers');
    const statProductsSold = document.getElementById('statProductsSold');
    const statAvgRating = document.getElementById('statAvgRating');
    const statTotalVisits = document.getElementById('statTotalVisits');

    let isDashboardOpen = false; // Track if the dashboard is explicitly open/closed by button

    // Function to fetch and update statistics
    const fetchAndDisplayStats = async () => {
        try {
            const response = await fetch('get_stats.php'); // Your new PHP endpoint
            const data = await response.json();

            if (response.ok) {
                statCustomers.textContent = data.num_customers.toLocaleString('id-ID');
                statProductsSold.textContent = data.total_products_sold.toLocaleString('id-ID');
                statAvgRating.textContent = data.average_rating > 0 ? `${data.average_rating.toFixed(2)} / 5` : 'N/A';
                statTotalVisits.textContent = data.num_visits.toLocaleString('id-ID');
            } else {
                console.error('Failed to fetch dashboard stats:', data.error || 'Unknown error');
                statCustomers.textContent = 'Error';
                statProductsSold.textContent = 'Error';
                statAvgRating.textContent = 'Error';
                statTotalVisits.textContent = 'Error';
            }
        } catch (error) {
            console.error('Error fetching dashboard stats:', error);
            statCustomers.textContent = 'N/A';
            statProductsSold.textContent = 'N/A';
            statAvgRating.textContent = 'N/A';
            statTotalVisits.textContent = 'N/A';
        }
    };

    if (floatingDashboard && toggleDashboardBtn && refreshDashboardBtn &&
        statCustomers && statProductsSold && statAvgRating && statTotalVisits) {

        // Scroll event listener for showing/hiding dashboard
        let lastScrollTop = 0;
        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > lastScrollTop && scrollTop > 200) { // Scrolling down and past 200px
                if (!isDashboardOpen) { // Only show if not explicitly opened by button
                    floatingDashboard.classList.add('active');
                    fetchAndDisplayStats(); // Fetch data when it appears due to scroll
                }
            } else if (scrollTop <= 200 && !isDashboardOpen) { // Scrolling up to top or near top
                floatingDashboard.classList.remove('active');
            }
            lastScrollTop = scrollTop;
        });

        // Toggle dashboard visibility by button click
        toggleDashboardBtn.addEventListener('click', () => {
            isDashboardOpen = !floatingDashboard.classList.contains('active'); // Update state
            floatingDashboard.classList.toggle('active');
            if (floatingDashboard.classList.contains('active')) {
                fetchAndDisplayStats(); // Fetch data when opened
            }
        });

        // Refresh stats button
        refreshDashboardBtn.addEventListener('click', fetchAndDisplayStats);

    } else {
        console.warn('Floating dashboard elements not found. Make sure HTML elements with correct IDs exist.');
    }

    // ... (Rest of your existing JavaScript code) ...
});

document.addEventListener('DOMContentLoaded', function () {
    const elements = {
        cartIcon: document.getElementById('cart-icon'),
        cartModal: document.getElementById('cartModal'),
        closeButton: document.querySelector('.close-button'),
        cartItemsContainer: document.getElementById('cartItems'),
        cartTotalSpan: document.getElementById('cartTotal'),
        checkoutButton: document.getElementById('checkoutButton'),
        cartCount: document.getElementById('cart-count'),
        paymentModal: document.getElementById('paymentModal'),
        closePaymentButton: document.querySelector('.close-button-payment'),
        processPaymentBtn: document.getElementById('processPaymentBtn'),
        productGrid: document.querySelector('.product-grid'),
        chatbotIcon: document.getElementById('chatbot-icon'),
        chatbotContainer: document.getElementById('chatbot-container'),
        closeChatbot: document.getElementById('close-chatbot'),
        chatbotMessages: document.getElementById('chatbot-messages'),
        chatInput: document.getElementById('chat-input'),
        sendChatBtn: document.getElementById('send-chat-btn')
    };

    let cart = JSON.parse(localStorage.getItem('aksarawalk_cart')) || [];
    let selectedPaymentMethod = null;

    // --- Utility Functions ---
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }

    function updateCartCounter() {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        elements.cartCount.textContent = totalItems;
    }

    function calculateCartTotal() {
        return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    }

    function renderCartItems() {
        elements.cartItemsContainer.innerHTML = '';
        if (cart.length === 0) {
            elements.cartItemsContainer.innerHTML = '<p>Keranjang Anda kosong.</p>';
            elements.checkoutButton.disabled = true;
        } else {
            cart.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.classList.add('cart-item');
                itemElement.innerHTML = `
                    <img src="${item.image}" alt="${item.name}">
                    <div class="item-details">
                        <h4>${item.name}</h4>
                        <p>${formatRupiah(item.price)} x ${item.quantity}</p>
                        <p>Total: ${formatRupiah(item.price * item.quantity)}</p>
                    </div>
                    <button class="remove-from-cart-btn" data-id="${item.id}">Hapus</button>
                `;
                elements.cartItemsContainer.appendChild(itemElement);
            });
            elements.checkoutButton.disabled = false;
        }
        elements.cartTotalSpan.textContent = formatRupiah(calculateCartTotal());
    }

    function addProductToCart(productId, productName, productPrice, productImage) {
        const existingItem = cart.find(item => item.id === productId);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage,
                quantity: 1
            });
        }
        localStorage.setItem('aksarawalk_cart', JSON.stringify(cart));
        updateCartCounter();
        alert(`${productName} telah ditambahkan ke keranjang!`);
    }

    function removeProductFromCart(productId) {
        const initialLength = cart.length;
        cart = cart.filter(item => item.id !== productId);
        if (cart.length < initialLength) {
            localStorage.setItem('aksarawalk_cart', JSON.stringify(cart));
            updateCartCounter();
            renderCartItems();
            alert('Produk dihapus dari keranjang.');
        }
    }

    // --- Event Listeners ---

    // Add to Cart from Product Grid (handles both index.php and semua-produk.php)
    if (elements.productGrid) {
        elements.productGrid.addEventListener('click', function (event) {
            if (event.target.classList.contains('add-to-cart-btn')) {
                const button = event.target;
                const productId = button.dataset.id;
                const productName = button.dataset.name;
                const productPrice = parseFloat(button.dataset.price);
                const productImage = button.dataset.image;
                addProductToCart(productId, productName, productPrice, productImage);
            }
        });
    }

    // Cart Icon Click
    if (elements.cartIcon) {
        elements.cartIcon.addEventListener('click', () => {
            elements.cartModal.style.display = 'block';
            renderCartItems();
        });
    }

    // Close Cart Modal
    if (elements.closeButton) {
        elements.closeButton.addEventListener('click', () => {
            elements.cartModal.style.display = 'none';
        });
    }

    // Close Payment Modal
    if (elements.closePaymentButton) {
        elements.closePaymentButton.addEventListener('click', () => {
            elements.paymentModal.style.display = 'none';
        });
    }

    // Click outside modal to close
    window.addEventListener('click', (event) => {
        if (event.target === elements.cartModal) {
            elements.cartModal.style.display = 'none';
        }
        if (event.target === elements.paymentModal) {
            elements.paymentModal.style.display = 'none';
        }
    });

    // Remove from Cart Button within Modal (delegated)
    elements.cartItemsContainer.addEventListener('click', (event) => {
        if (event.target.classList.contains('remove-from-cart-btn')) {
            const productId = event.target.dataset.id;
            removeProductFromCart(productId);
        }
    });

    // Checkout Button
    if (elements.checkoutButton) {
        elements.checkoutButton.addEventListener('click', () => {
            elements.cartModal.style.display = 'none'; // Close cart modal
            elements.paymentModal.style.display = 'block'; // Open payment modal
            // Reset payment method selection
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => radio.checked = false);
            selectedPaymentMethod = null;
        });
    }

    // Payment method selection
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', (event) => {
            selectedPaymentMethod = event.target.value;
            console.log('Selected payment method:', selectedPaymentMethod);
        });
    });

    // Process Payment Button (calls new API)
    if (elements.processPaymentBtn) {
        elements.processPaymentBtn.addEventListener('click', async () => {
            if (!selectedPaymentMethod) {
                alert('Silakan pilih metode pembayaran terlebih dahulu.');
                return;
            }
            
            if (cart.length === 0) {
                alert('Keranjang Anda kosong!');
                return;
            }

            // Simulate loading
            alert(`Memproses pembayaran dengan ${selectedPaymentMethod}...\n(Ini adalah demo, transaksi nyata akan diproses di backend)`);

            try {
                const response = await fetch('api/create_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ cart_items: cart })
                });

                const result = await response.json();

                if (response.ok) {
                    alert(`Pesanan Anda (${result.order_id}) berhasil dibuat! Terima kasih.`);
                    cart = []; // Clear cart after successful order
                    localStorage.setItem('aksarawalk_cart', JSON.stringify(cart));
                    updateCartCounter();
                    renderCartItems();
                    if (elements.paymentModal) elements.paymentModal.style.display = 'none';
                } else {
                    alert(`Gagal membuat pesanan: ${result.error || 'Terjadi kesalahan.'}`);
                }
            } catch (error) {
                console.error('Error during checkout:', error);
                alert('Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
            }
        });
    }

    // --- Chatbot Functionality ---

    // Chatbot Icon Click
    if (elements.chatbotIcon) {
        elements.chatbotIcon.addEventListener('click', () => {
            elements.chatbotContainer.style.display = 'flex';
            elements.chatbotMessages.scrollTop = elements.chatbotMessages.scrollHeight; // Scroll to bottom on open
        });
    }

    // Close Chatbot
    if (elements.closeChatbot) {
        elements.closeChatbot.addEventListener('click', () => {
            elements.chatbotContainer.style.display = 'none';
        });
    }

    function addMessage(message, sender) {
        const messageBubble = document.createElement('div');
        messageBubble.classList.add('message-bubble', sender);
        messageBubble.textContent = message;
        elements.chatbotMessages.appendChild(messageBubble);
        elements.chatbotMessages.scrollTop = elements.chatbotMessages.scrollHeight;
    }

    const sendMessage = async () => {
        const message = elements.chatInput.value.trim();
        if (message === '') return;

        addMessage(message, 'user');
        elements.chatInput.value = '';

        const typingIndicator = document.createElement('div');
        typingIndicator.classList.add('message-bubble', 'bot', 'typing-indicator');
        typingIndicator.textContent = 'AISARA 2.5 sedang mengetik...';
        elements.chatbotMessages.appendChild(typingIndicator);
        elements.chatbotMessages.scrollTop = elements.chatbotMessages.scrollHeight;

        try {
            const response = await fetch('ai_chat_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                // history is now handled by the backend, so we don't send it from here
                body: JSON.stringify({ message: message }) 
            });

            const data = await response.json();

            typingIndicator.remove(); // Remove typing indicator once response is received

            if (response.ok && data.response) { // Changed from data.status === 'success' to data.response
                addMessage(data.response, 'bot'); // Use data.response directly
            } else {
                addMessage('Maaf, saya tidak bisa memproses permintaan Anda saat ini. ' + (data.error || ''), 'bot');
                console.error('AI Service Error:', data.error);
            }
        } catch (error) {
            typingIndicator.remove(); // Also remove on error
            addMessage('Terjadi kesalahan koneksi ke AISARA 2.5. Mohon coba lagi.', 'bot');
            console.error('Fetch Error:', error);
        }
    };

    // Send message on button click
    if (elements.sendChatBtn) {
        elements.sendChatBtn.addEventListener('click', sendMessage);
    }

    // Send message on Enter key press
    if (elements.chatInput) {
        elements.chatInput.addEventListener('keypress', (event) => {
            if (event.key === 'Enter') {
                sendMessage();
            }
        });
    }

    // Initial load: update cart counter
    updateCartCounter();
});

// Inside document.addEventListener('DOMContentLoaded', function () { ... });

    let appliedDiscount = null; // To store applied discount details

    // ... (existing elements, cart, etc.) ...

    const elements = {
        // ... (existing elements) ...
        discountCodeInput: document.getElementById('discountCodeInput'),
        applyDiscountBtn: document.getElementById('applyDiscountBtn'),
        discountMessage: document.getElementById('discountMessage'),
        cartTotalSpan: document.getElementById('cartTotal'), // Ensure this is accessible
        // ...
    };

    // ... (existing functions) ...

    function renderCartItems() {
        // ... (existing cart item rendering) ...
        elements.cartTotalSpan.textContent = formatRupiah(calculateCartTotal());
        // Update discount display if any
        if (appliedDiscount) {
            elements.discountMessage.textContent = `Diskon diterapkan: ${appliedDiscount.code} (-${formatRupiah(appliedDiscount.amount)})`;
            elements.discountMessage.style.color = 'lightgreen';
        } else {
            elements.discountMessage.textContent = '';
        }
    }

    // Apply Discount Button
    if (elements.applyDiscountBtn) {
        elements.applyDiscountBtn.addEventListener('click', async () => {
            const discountCode = elements.discountCodeInput.value.trim();
            if (discountCode === '') {
                elements.discountMessage.textContent = 'Masukkan kode diskon.';
                elements.discountMessage.style.color = 'red';
                return;
            }

            const currentTotal = calculateCartTotal();
            if (currentTotal === 0) {
                elements.discountMessage.textContent = 'Keranjang kosong, tidak bisa menerapkan diskon.';
                elements.discountMessage.style.color = 'red';
                return;
            }

            try {
                const response = await fetch(`api/apply_discount.php?code=${encodeURIComponent(discountCode)}&total=${currentTotal}`);
                const result = await response.json();

                if (response.ok) {
                    appliedDiscount = result.discount;
                    alert(`Diskon "${appliedDiscount.code}" berhasil diterapkan! Anda menghemat ${formatRupiah(appliedDiscount.amount)}.`);
                    renderCartItems(); // Re-render cart to show new total if logic was client-side or just to update message
                } else {
                    appliedDiscount = null;
                    elements.discountMessage.textContent = result.error || 'Gagal menerapkan diskon.';
                    elements.discountMessage.style.color = 'red';
                    console.error('Apply Discount Error:', result.error);
                }
            } catch (error) {
                console.error('Error applying discount:', error);
                elements.discountMessage.textContent = 'Terjadi kesalahan saat memeriksa diskon.';
                elements.discountMessage.style.color = 'red';
            }
        });
    }

    // Process Payment Button (send applied discount to backend)
    if (elements.processPaymentBtn) {
        elements.processPaymentBtn.addEventListener('click', async () => {
            // ... (existing validation) ...

            try {
                const payload = {
                    cart_items: cart,
                    discount_code: appliedDiscount ? appliedDiscount.code : null, // Send the applied code
                    // You might also send the amount saved and discount ID, but backend should re-validate
                };

                const response = await fetch('api/create_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (response.ok) {
                    alert(`Pesanan Anda (${result.order_id}) berhasil dibuat! Terima kasih. Total dibayar: ${formatRupiah(result.final_total_amount)}`);
                    cart = [];
                    localStorage.setItem('aksarawalk_cart', JSON.stringify(cart));
                    updateCartCounter();
                    renderCartItems();
                    if (elements.paymentModal) elements.paymentModal.style.display = 'none';
                    appliedDiscount = null; // Clear applied discount
                } else {
                    alert(`Gagal membuat pesanan: ${result.error || 'Terjadi kesalahan.'}`);
                }
            } catch (error) {
                console.error('Error during checkout:', error);
                alert('Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
            }
        });
    }

    // ... (rest of script.js) ...