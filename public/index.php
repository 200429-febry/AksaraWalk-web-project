<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="img/logo2.jpg" href="logo2.jpg">
   <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
   <title>AksaraWalk_Collection</title>
   
   <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
   
   <script src="https://unpkg.com/three@0.128.0/examples/js/libs/fflate.min.js"></script>
   
   <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/FBXLoader.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/RGBELoader.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/pmrem/PMREMGenerator.js"></script>
   
   <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
   <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/handpose"></script>
   <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands"></script>
   <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils"></script>

</head>


<body>
 
    </script>
   <div class="loader-container">
   <div class="loader">
     <img src="loading.gif" alt="Loading..." class="loader-gif">
     <p>Please Wait...</p>
   </div>
 </div>

 <style>
   /* Loading Screen Styles */
   .loader-container {
     position: fixed;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     background-color: #00000000;
     display: flex;
     justify-content: center;
     align-items: center;
     flex-direction: column;
     z-index: 9999;
     transition: opacity 0.5s ease-out;
   }

   .loader {
     text-align: center;
   }

   .loader-gif {
     width: 80px;
     height: 80px;
     margin-bottom: 15px;
   }

   /* Hide after load */
   .loaded .loader-container {
     opacity: 0;
     pointer-events: none;
   }
 </style>

 <script>
   // Wait for everything to load
   window.addEventListener('load', function() {
     // Add slight delay for better UX
     setTimeout(function() {
       document.body.classList.add('loaded');

       // Remove loader from DOM after animation completes
       setTimeout(function() {
         const loader = document.querySelector('.loader-container');
         if (loader) loader.remove();
       }, 500); // Matches CSS transition time
     }, 1000); // Minimum show time
   });
 </script>
   <header class="header">
      <img src="logo.png" alt="Logo" width="37">
      <div class="nav">
         <a href="index.html" class="logo">𝐀𝐤𝐬𝐚𝐫𝐚𝐖𝐚𝐥𝐤</a>
         <nav>
            <ul>
               <li><a href="index.html">𝑯𝒐𝒎𝒆</a></li>
               <li><a href="#arrival">𝑵𝒆𝒘 𝑨𝒓𝒓𝒊𝒗𝒂𝒍𝒔</a></li>
               <li><a href="#best-collection">𝑩𝒆𝒔𝒕 𝑪𝒐𝒍𝒍𝒆𝒄𝒕𝒊𝒐𝒏</a></li>
               <li><a href="#about">𝑨𝒃𝒐𝒖𝒕</a></li>
            </ul>
            <div class="nav-icon">
               <span class="wishlist-btn"><i class="fa-solid fa-heart"></i></span>
               <span class="cart-btn"><i class="fa-solid fa-cart-shopping"></i></span>
               <span class="cart-count">0</span> </div>
         </nav>
         <i class="fa-solid fa-bars burger_icon"></i>
      </div>
   </header>
  <div id="loading-indicator" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); z-index:10000;">
   <div style="color:white; background:rgba(0,0,0,0.7); padding:20px; border-radius:10px;">
     <i class="fas fa-spinner fa-spin"></i> Memuat model 3D...
   </div>
 </div>

 <div id="error-message" style="display:none; position:fixed; top:20px; left:50%; transform:translateX(-50%); background:#ff4444; color:white; padding:10px 20px; border-radius:5px; z-index:10000;"></div>
<div class="popup wishlist-popup">
   <div class="popup-content">
      <span class="close-popup">&times;</span>
      <h3>Wishlist Anda</h3>
      <div class="wishlist-items">
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

 <style>
   /* 3D Viewer Modal Styles - Added to style.css */
   .modal-3d {
     display: none;
     position: fixed;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     background: rgba(0,0,0,0.9);
     z-index: 10000;
     backdrop-filter: blur(5px);
   }

   .modal-3d-content {
     position: absolute;
     top: 50%;
     left: 50%;
     transform: translate(-50%, -50%);
     width: 90%;
     height: 80%;
     max-width: 1000px;
     background: rgba(17, 17, 17, 0.9);
     border-radius: 12px;
     padding: 20px;
     color: white;
     border: 1px solid var(--first-gra-clr);
     box-shadow: 0 10px 30px rgba(195, 43, 251, 0.3);
   }

   #shoe3dViewer {
     width: 100%;
     height: 65vh;
     background: #222;
     border-radius: 8px;
     margin: 15px 0;
     border: 1px solid rgba(255,255,255,0.1);
   }

   .close-3d {
     position: absolute;
     top: 15px;
     right: 25px;
     font-size: 30px;
     color: white;
     cursor: pointer;
     transition: var(--transition-one);
   }

   .close-3d:hover {
     color: var(--first-gra-clr);
   }

   .shoe-details {
     padding: 15px;
     background: rgba(255,255,255,0.05);
     border-radius: 8px;
     text-align: center;
   }

   .add-to-cart-3d {
     width: 100%;
     margin-top: 15px;
     background-image: var(--gra-primary);
   }

   .add-to-cart-3d:hover {
     background-image: var(--gra-white);
     color: var(--dark-clr);
   }
 </style>

 <script>
 // 3D Viewer Integration - Add to script.js
 document.addEventListener('DOMContentLoaded', function() {
   // 3D Viewer Variables
   let scene, camera, renderer, shoeModel, controls;
   const modal3D = document.getElementById('shoe3dModal');
   const close3D = document.querySelector('.close-3d');
   const loadingIndicator = document.getElementById('loading-indicator');
   const errorMessage = document.getElementById('error-message');

   // Product to 3D Model Mapping
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
     "nike kyrie 2 \"inferno\"": "models/nike_kyrie_2_inferno.fbx",
     "nike blazer mid 77": "models/nike_blazer_mid_77.fbx",
     "nike air max 1 \"infrared\"": "models/nike_air_max_1_infrared.fbx",
     "hive running tilt": "models/hive_running_tilt.fbx",
     "nike golf \"green lunar\"": "models/nike_golf_green_lunar.fbx",
     "nike roshe one": "models/nike_roshe_one.fbx",
     "converse chuck taylor 2018": "models/converse_chuck_taylor_2018.fbx",
     // Add more mappings as needed
   };

   // Add 3D view button to all product cards
   document.querySelectorAll('.collection-item, .arrival .col').forEach(card => {
     const buyBtn = card.querySelector('.show-btn');
     const newBtn = buyBtn.cloneNode(true);
     newBtn.textContent = '3D';
     newBtn.classList.add('view-3d-btn');
     newBtn.style.marginTop = '10px';
     buyBtn.parentNode.appendChild(newBtn);

     newBtn.addEventListener('click', function(e) {
       e.stopPropagation();
       const productName = card.querySelector('.heading-three').textContent.trim();
       const productPrice = card.querySelector('.show-price').textContent.trim();
       const productImg = card.querySelector('img').src;

       open3DViewer(
         modelMap[productName.toLowerCase()] || "models/default_shoe.fbx", // Use lowercase for map lookup
         productName,
         productPrice,
         productImg
       );
     });
   });

   // Store current product data for "Add to Cart" in 3D viewer
   let currentProductData = {};

   // Open 3D Viewer
   function open3DViewer(modelPath, name, price, img) {
     modal3D.style.display = 'block';
     document.body.style.overflow = 'hidden';

     // Set product info and store for cart
     document.getElementById('shoe3dTitle').textContent = name.toUpperCase();
     document.getElementById('shoe3dPrice').textContent = price;
     document.getElementById('shoe3dDescription').textContent = `Explore ${name} in 3D`;
     currentProductData = { id: name, name: name, price: price, image: img };

     init3DScene();
     load3DModel(modelPath);
     animate();
   }

   // Close 3D Viewer
   function close3DViewer() {
     modal3D.style.display = 'none';
     document.body.style.overflow = 'auto';
     loadingIndicator.style.display = 'none'; // Ensure loading indicator is hidden
     errorMessage.style.display = 'none'; // Ensure error message is hidden

     if (renderer) {
       renderer.dispose();
       renderer.domElement.remove(); // Remove canvas to prevent memory leaks
     }
     if (shoeModel) {
        scene.remove(shoeModel);
        shoeModel = null; // Clear model reference
     }
     if (scene.environment) {
         scene.environment.dispose(); // Dispose environment map
     }
     scene = null;
     camera = null;
     controls = null;
   }

   // Initialize Three.js scene
   function init3DScene() {
     const container = document.getElementById('shoe3dViewer');
     container.innerHTML = ''; // Clear previous content

     // Scene
     scene = new THREE.Scene();
     // scene.background = new THREE.Color(0x111111); // Will be overridden by HDRI

     // Camera
     camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
     camera.position.set(0, 0, 5); // Adjusted initial position

     // Renderer
     renderer = new THREE.WebGLRenderer({ antialias: true });
     renderer.setSize(container.clientWidth, container.clientHeight);
     renderer.outputEncoding = THREE.sRGBEncoding; // For better color accuracy with PBR
     renderer.toneMapping = THREE.ACESFilmicToneMapping; // Enhances contrast and realism
     renderer.toneMappingExposure = 1.25; // Adjust exposure
     container.appendChild(renderer.domElement);

     // Lights (Will be replaced by environment map)
     // const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
     // scene.add(ambientLight);
     // const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
     // directionalLight.position.set(1, 1, 1);
     // scene.add(directionalLight);

     // Controls
     controls = new THREE.OrbitControls(camera, renderer.domElement);
     controls.enableDamping = true;
     controls.dampingFactor = 0.25;
     controls.target.set(0, 0, 0); // Ensure controls target the center of the scene

     // Load HDRI Environment Map
     const pmremGenerator = new THREE.PMREMGenerator(renderer);
     pmremGenerator.compileEquirectangularShader();

     new THREE.RGBELoader()
         .setDataType(THREE.HalfFloatType) // Use HalfFloatType for better quality
         .setPath('/') // Adjust this path if your HDRI is in a subfolder, e.g., 'img/'
         .load('royal_esplanade_1k.hdr', function(texture) { // Placeholder HDRI, replace with your own
             const envMap = pmremGenerator.fromEquirectangular(texture).texture;
             scene.environment = envMap; // Set environment map for reflections and ambient lighting
             scene.background = envMap; // Set as background as well
             texture.dispose();
             pmremGenerator.dispose();
         }, undefined, function(error) {
             console.error('Error loading HDRI:', error);
             // Fallback to solid background and basic lights if HDRI fails
             scene.background = new THREE.Color(0x111111);
             scene.add(new THREE.AmbientLight(0xffffff, 0.8));
             scene.add(new THREE.DirectionalLight(0xffffff, 0.5));
         });
   }

   // Load 3D Model
   function load3DModel(path) {
     loadingIndicator.style.display = 'flex'; // Show loading indicator
     errorMessage.style.display = 'none'; // Hide any previous error messages

     if (shoeModel) {
       scene.remove(shoeModel);
     }

     const loader = new THREE.FBXLoader();
     loader.load(
       path,
       function(object) {
         shoeModel = object;

         shoeModel.traverse(function(child) {
             if (child.isMesh) {
                 // Apply a standard material that reacts to the environment map
                 child.material = new THREE.MeshStandardMaterial({
                     color: 0xffffff, // Base color (can be overridden by textures if loaded)
                     metalness: 0.8, // Example metalness
                     roughness: 0.2, // Example roughness
                     envMap: scene.environment, // Apply environment map
                     envMapIntensity: 1 // Adjust intensity of reflections
                 });
             }
         });

         // Center the model
         const box = new THREE.Box3().setFromObject(shoeModel);
         const center = box.getCenter(new THREE.Vector3());
         shoeModel.position.sub(center);

         // Scale the model to fit
         const size = box.getSize(new THREE.Vector3()).length();
         const scale = 3.0 / size; // Adjust '3.0' based on desired model size in scene
         shoeModel.scale.set(scale, scale, scale);

         scene.add(shoeModel);
         loadingIndicator.style.display = 'none'; // Hide loading indicator
       },
       undefined, // onProgress callback (optional)
       function(error) {
         console.error('Error loading 3D model:', error);
         loadingIndicator.style.display = 'none'; // Hide loading indicator
         errorMessage.textContent = '3D model could not be loaded.';
         errorMessage.style.display = 'block';
         document.getElementById('shoe3dViewer').innerHTML = `
           <div style="color:white;text-align:center;padding-top:50%;">
             <i class="fa-solid fa-triangle-exclamation" style="font-size:2rem;color:#ff6b6b;"></i>
             <p>3D model could not be loaded</p>
           </div>`;
       }
     );
   }

   // Animation loop
   function animate() {
     if (!renderer) return; // Stop animation if renderer is disposed
     requestAnimationFrame(animate);
     if (controls) controls.update();
     if (renderer && scene && camera) renderer.render(scene, camera);
   }

   // Event Listeners
   close3D.addEventListener('click', close3DViewer);

   window.addEventListener('click', (e) => {
     if (e.target === modal3D) {
       close3DViewer();
     }
   });

   window.addEventListener('resize', function() {
     if (camera && renderer) {
       const container = document.getElementById('shoe3dViewer');
       if (container.clientWidth > 0 && container.clientHeight > 0) {
         camera.aspect = container.clientWidth / container.clientHeight;
         camera.updateProjectionMatrix();
         renderer.setSize(container.clientWidth, container.clientHeight);
       }
     }
   });

   // Add to cart from 3D viewer
   document.querySelector('.add-to-cart-3d').addEventListener('click', function() {
     if (currentProductData && currentProductData.id) {
       const existingItem = cart.find(item => item.id === currentProductData.id);
       if (existingItem) {
         existingItem.quantity++;
       } else {
         cart.push({ ...currentProductData, quantity: 1 });
       }
       localStorage.setItem('aksarawalk_cart', JSON.stringify(cart));
       updateCartCounter();
       renderCartItems();
       alert(`${currentProductData.name} added to cart!`);
     } else {
       alert('Product data not available to add to cart.');
     }
     close3DViewer();
   });
 });
 </script>
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
   <main class="hero container">
      <div class="clr-one"></div>
      <div class="clr-two"></div>
      <div class="wrapper">
         <div class="col col-text" data-aos="fade-right">
            <h1 class="heading-one">𝐛𝐞𝐬𝐭 𝐢𝐧 𝐬𝐭𝐲𝐥𝐞 <br> 𝐜𝐨𝐥𝐥𝐞𝐜𝐭𝐢𝐨𝐧 <br> <span>𝐟𝐨𝐫 𝐲𝐨𝐮</span></h1>
            <p class="sub-text">Kami mengoleksi sepatu dengan tren masa kini, kami ingin memberikan yang terbaik di dalam dunia fashion, <br> dengan memilih sepatu dengan desain "TIMELESS".</p>
            <button id="preOrderBtn" class="btn btn-hero" onclick="location.href='register.html';">Pre-Order Now</button>
         </div>
         <div class="col col-img">
            <figure data-aos="fade-left">
               <img src="img/hero.png" alt="nike-shoe">
            </figure>
            <div class="hero-img-off" data-aos="zoom-in-up">
               <h3>Get Up to 40% OFF</h3>
               <p>Diskon berlaku untuk pelanggan yang sudah membeli lebih dari 5 sepatu Nike edisi terbatas.</p>
            </div>
         </div>
      </div>
   </main>
  <div class="chatbot-container" id="aisaraChatbot">
    <div class="chatbot-header">
        <h3>AISARA 2.5</h3>
        <button class="close-chatbot" id="closeChatbot">&times;</button>
    </div>
    <div class="chatbot-messages" id="chatbotMessages">
        <div class="message-bubble bot">Halo! Saya AISARA 2.5. Ada yang bisa saya bantu terkait sepatu atau fashion di AksaraWalk?</div>
    </div>
    <div class="chatbot-input">
        <input type="text" id="chatInput" placeholder="Tanyakan sesuatu..." />
        <button id="sendMessageBtn">Kirim</button>
    </div>
</div>

<button class="floating-chatbot-btn" id="openChatbotBtn" title="Chat dengan AISARA 2.5">
    <img src="img/chatbot_logo.jpg" alt="AISARA 2.5 Logo" class="chatbot-logo-img three-d">
</button>

<style>
/* CSS Khusus Chatbot */
.chatbot-container {
    display: none; /* Default hidden */
    position: fixed;
    top: 90px; /* Posisikan 90px dari atas */
    left: 20px; /* Posisikan 20px dari kiri */
    bottom: auto;
    right: auto;
    width: 350px;
    height: 450px;
    background-color: rgba(30, 0, 40, 0.95);
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(195, 43, 251, 0.3);
    flex-direction: column;
    overflow: hidden;
    z-index: 1001; /* Z-index lebih tinggi dari video-overlay (999) */
}

.chatbot-header {
    background: linear-gradient(45deg, #c32bfb, #f818d2);
    color: white;
    padding: 15px;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: grab; /* Menunjukkan bahwa elemen bisa diseret */
}

.chatbot-header.dragging {
    cursor: grabbing;
}

.chatbot-header h3 {
    color: white;
    margin: 0;
}

.close-chatbot {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.close-chatbot:hover {
    opacity: 1;
}

.chatbot-messages {
    flex-grow: 1;
    padding: 15px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background-color: rgba(18, 1, 27, 0.8);
}

.message-bubble {
    padding: 10px 15px;
    border-radius: 15px;
    max-width: 80%;
    word-wrap: break-word;
    color: white;
    font-size: 0.9rem;
}

.message-bubble.user {
    background-color: #5b417c;
    align-self: flex-end;
    border-bottom-right-radius: 5px;
}

.message-bubble.bot {
    background-color: #3b005a;
    align-self: flex-start;
    border-bottom-left-radius: 5px;
}

.chatbot-input {
    display: flex;
    padding: 10px 15px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    background-color: rgba(18, 1, 27, 0.9);
}

.chatbot-input input {
    flex-grow: 1;
    padding: 10px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    background-color: rgba(255, 255, 255, 0.1);
    color: white;
    outline: none;
    font-size: 0.9rem;
}

.chatbot-input input::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.chatbot-input button {
    background: linear-gradient(45deg, #c32bfb, #f818d2);
    color: white;
    border: none;
    border-radius: 20px;
    padding: 10px 15px;
    margin-left: 10px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: opacity 0.2s;
}

.chatbot-input button:hover {
    opacity: 0.9;
}

.floating-chatbot-btn {
    position: fixed;
    top: 20px; /* Posisikan 20px dari atas */
    left: 20px; /* Posisikan 20px dari kiri */
    bottom: auto;
    right: auto;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #c32bfb, #f818d2);
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(195, 43, 251, 0.4);
    z-index: 1002; /* Z-index lebih tinggi dari chatbot container dan video overlay */
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    animation: float 3s ease-in-out infinite;
    transition: all 0.3s ease;
}

.floating-chatbot-btn:hover {
    transform: scale(1.1) translateY(-5px);
    box-shadow: 0 8px 20px rgba(195, 43, 251, 0.6);
}

/* CSS untuk gambar logo chatbot */
.chatbot-logo-img {
    width: 100%; /* Agar gambar mengisi lebar tombol */
    height: 100%; /* Agar gambar mengisi tinggi tombol */
    object-fit: cover; /* Agar gambar tidak terdistorsi */
    border-radius: 50%; /* Jika Anda ingin gambar tetap bulat seperti tombol */
    /* Anda bisa menambahkan padding jika gambar terlalu besar */
    /* padding: 8px; */
}

/* Efek 3D sederhana untuk logo (jika ditambahkan kelas 'three-d' di HTML) */
.chatbot-logo-img.three-d {
    box-shadow:
        inset 0 0 15px rgba(255, 255, 255, 0.5), /* Cahaya dalam */
        0 5px 25px rgba(0, 0, 0, 0.4), /* Bayangan luar */
        0 0 20px rgba(195, 43, 251, 0.7); /* Glow */
    transform: rotateY(15deg) rotateX(5deg); /* Rotasi ringan */
    transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
}

.floating-chatbot-btn:hover .chatbot-logo-img.three-d {
    transform: rotateY(0deg) rotateX(0deg) scale(1.05); /* Kembali normal saat hover */
    box-shadow:
        inset 0 0 10px rgba(255, 255, 255, 0.7),
        0 8px 30px rgba(0, 0, 0, 0.6),
        0 0 30px rgba(195, 43, 251, 0.9);
}


/* Animasi float (jika belum ada di style.css) */
@keyframes float {
   0%, 100% { transform: translateY(0); }
   50% { transform: translateY(-10px); }
}

/* Responsif untuk mobile */
@media (max-width: 768px) {
    .chatbot-container {
        width: 90%;
        height: 70%;
        top: 50%; /* Pusatkan vertikal di mobile */
        left: 5%; /* Pusatkan horizontal di mobile */
        right: 5%;
        transform: translateY(-50%); /* Penyesuaian untuk tengah vertikal */
        margin: auto;
    }
    .floating-chatbot-btn {
        top: 10px; /* Posisikan 10px dari atas di mobile */
        left: 10px; /* Posisikan 10px dari kiri di mobile */
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
}
</style>
<style>
/* Style untuk tombol enable gesture */
.enable-gesture-btn {
    position: fixed;
    top: 20px; /* Posisikan 20px dari atas */
    right: 20px; /* Posisikan 20px dari kanan */
    left: auto; /* Pastikan 'left' diatur ke auto agar tidak berkonflik dengan 'right' */
    bottom: auto; /* Pastikan properti ini diatur */
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2bffc3, #18d2f8); /* Warna berbeda */
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(43, 251, 195, 0.4);
    z-index: 1002; /* Pastikan z-index cukup tinggi */
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    animation: float 3s ease-in-out infinite;
    transition: all 0.3s ease;
}

.enable-gesture-btn:hover {
    transform: scale(1.1) translateY(-5px);
    box-shadow: 0 8px 20px rgba(43, 251, 195, 0.6);
}

/* ... (Optional: style for the video/canvas feed for debugging) ... */

/* Style untuk tombol enable voice */
.enable-voice-btn {
    position: fixed;
    top: 20px; /* Sesuaikan dengan posisi 'top' tombol gesture jika ingin di baris yang sama */
    left: 100px; /* Atur posisi agar tidak menumpuk dengan tombol chatbot */
    right: auto; /* Pastikan properti ini diatur */
    bottom: auto; /* Pastikan properti ini diatur */
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f39c12, #e67e22); /* Warna oranye */
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(243, 156, 18, 0.4);
    z-index: 999; /* Z-index sedikit lebih rendah dari gesture */
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    animation: float 3s ease-in-out infinite;
    transition: all 0.3s ease;
}

.enable-voice-btn:hover {
    transform: scale(1.1) translateY(-5px);
    box-shadow: 0 8px 20px rgba(243, 156, 18, 0.6);
}

.enable-voice-btn.active {
    background: linear-gradient(135deg, #27ae60, #2ecc71); /* Warna hijau saat aktif */
}

/* Responsif untuk mobile */
@media (max-width: 768px) {
    /* Tombol Gesture di Mobile */
    .enable-gesture-btn {
        top: 10px; /* Posisikan 10px dari atas di mobile */
        right: 10px; /* Posisikan 10px dari kanan di mobile */
        left: auto;
        width: 50px;
        height: 50px;
        font-size: 20px;
    }

    /* Tombol Voice di Mobile (jika masih digunakan) */
    .enable-voice-btn {
        top: 10px;
        left: 10px; /* Sesuaikan agar tidak menumpuk dengan chatbot jika masih ada */
        right: auto;
        width: 50px;
        height: 50px;
        font-size: 20px;
    }

    /* Tombol Chatbot di Mobile */
    .floating-chatbot-btn {
        top: 10px;
        left: 10px;
        width: 50px;
        height: 50px;
        font-size: 24px;
    }

    /* Chatbot Container di Mobile */
    .chatbot-container {
        top: 50%;
        left: 5%;
        right: 5%;
        transform: translateY(-50%);
        width: 90%;
        height: 70%;
    }
}
</style>
   <div class="subscribe-notification">
      <div class="notification-content">
        <span class="highlight">Dimas</span> baru saja mensubscribe
      </div>
    </div>
    <script>
      // Daftar nama acak untuk demo
      const names = ['Dimas', 'Rina', 'Budi', 'Ani', 'Joko', 'Siti', 'Ahmad', 'Dewi'];

      function showNotification() {
        const notification = document.querySelector('.subscribe-notification');
        const nameElement = document.querySelector('.highlight');

        // Pilih nama acak
        const randomName = names[Math.floor(Math.random() * names.length)];
        nameElement.textContent = randomName;

        // Tampilkan notifikasi
        notification.classList.add('show');

        // Sembunyikan setelah 5 detik
        setTimeout(() => {
          notification.classList.remove('show');

          // Tampilkan notifikasi baru setiap 8-15 detik
          setTimeout(showNotification, Math.random() * 7000 + 8000);
        }, 5000);
      }

      // Mulai notifikasi pertama setelah 3 detik
      setTimeout(showNotification, 3000);
      </script>
  <div class="video-overlay">
   <div class="video-container">
     <span class="close-video">&times;</span>
     <video controls autoplay muted loop>
       <source src="img/video-promo.mp4" type="video/mp4">
       Browser Anda tidak mendukung tag video.
     </video>
     <div class="video-caption">
       <h3></h3>
       <button id="discoverBtn" class="btn btn-hero" onclick="location.href='discover/index.html';">
         Discover Now
     </button>

     </div>
   </div>
 </div>

 <button class="floating-video-btn" title="Tonton Video">
   <i class="fa-solid fa-play"></i>
 </button>
 <script>
   document.addEventListener("DOMContentLoaded", function() {
     const overlay = document.querySelector(".video-overlay");
     const closeBtn = document.querySelector(".close-video");
     const openBtn = document.querySelector(".floating-video-btn");
     const discoverBtn = document.querySelector(".cta-button");

     openBtn.addEventListener("click", () => {
       overlay.style.display = "flex";
     });

     closeBtn.addEventListener("click", () => {
       overlay.style.display = "none";
     });

     discoverBtn.addEventListener("click", () => {
       alert("Discover button clicked!"); // Ganti ini dengan aksi lain jika perlu
     });
   });
 </script>

   <section class="arrival container" id="arrival">
    <div class="section-heading" data-aos="zoom-in-up">
        <div class="heading">
            <p class="sub-heading">𝐎𝐮𝐫 𝐧𝐞𝐰 𝐜𝐨𝐥𝐥𝐞𝐜𝐭𝐢𝐨𝐧</p>
            <h2 class="heading-two">𝐧𝐞𝐰 <span>𝐚𝐫𝐫𝐢𝐯𝐚𝐥𝐬</span></h2>
        </div>
        <a href="semua-produk.php" class="btn">see all</a>
    </div>
    <div class="wrapper">

        <div class="col" data-aos="zoom-in-up">
            <figure><img src="img/na1.png" alt="nike air max 94"></figure>
            <div class="col-body">
                <h3 class="heading-three">nike air max 94</h3>
                <p class="sub-heading">air max</p>
                <div class="col-footer">
                    <p class="show-price">Rp3.299.000</p>
                    <button class="show-btn btn">buy</button>
                </div>
            </div>
        </div>

        <div class="col" data-aos="zoom-in-up">
            <figure><img src="img/na2.png" alt="nike air huarache"></figure>
            <div class="col-body">
                <h3 class="heading-three">nike air huarache run ultra</h3>
                <p class="sub-heading">air gray</p>
                <div class="col-footer">
                    <p class="show-price">Rp2.799.000</p>
                    <button class="show-btn btn">buy</button>
                </div>
            </div>
        </div>

        <div class="col" data-aos="zoom-in-up">
            <figure><img src="img/na3.png" alt="nike air jordan 1"></figure>
            <div class="col-body">
                <h3 class="heading-three">nike air jordan 1 mid retro reverse new love</h3>
                <p class="sub-heading">air basket</p>
                <div class="col-footer">
                    <p class="show-price">Rp2.612.187</p>
                    <button class="show-btn btn">buy</button>
                </div>
            </div>
        </div>

        <div class="col" data-aos="zoom-in-up">
            <figure><img src="img/na4.png" alt="adidas zx flux"></figure>
            <div class="col-body">
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
               <img src="img/about6.gif" alt="about-img">
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
   <script>
      const readMoreBtn = document.querySelector(".read-more-btn");
      const extraText = document.querySelector(".extra-text");

      readMoreBtn.addEventListener("click", function () {
        // Toggle tampilan
        if (extraText.style.display === "none") {
          extraText.style.display = "block";
          readMoreBtn.textContent = "read less";
        } else {
          extraText.style.display = "none";
          readMoreBtn.textContent = "read more";
        }
      });
    </script>

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
            <figure><img src="img/men/men1.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Nike air max 2015 "dark obsidian</h3>
               <p class="sub-heading">nike</p>
               <div class="col-footer">
                  <p class="show-price">Rp3.099.000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="sports" data-aos="zoom-in-up">
            <figure><img src="img/sports/sports1.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Nike Air Zoom Pegasus</h3>
               <p class="sub-heading">nike</p>
               <div class="col-footer">
                  <p class="show-price">Rp1.799.000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="women" data-aos="zoom-in-up">
            <figure><img src="img/women/women1.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Puma Basket Burgundy</h3>
               <p class="sub-heading">puma</p>
               <div class="col-footer">
                  <p class="show-price">Rp1.500.000</p>
               <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="men" data-aos="zoom-in-up">
            <figure><img src="img/men/men2.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Nike Air Jordan 11 Concord</h3>
               <p class="sub-heading">nike</p>
               <div class="col-footer">
                  <p class="show-price">Rp.4.398.000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="men" data-aos="zoom-in-up">
            <figure><img src="img/men/men3.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Nike Air Force 1</h3>
               <p class="sub-heading">nike</p>
               <div class="col-footer">
                  <p class="show-price">Rp3.099.000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="women" data-aos="zoom-in-up">
            <figure><img src="img/women/women2.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Keds Triumph White Metallic</h3>
               <p class="sub-heading">keds</p>
               <div class="col-footer">
                  <p class="show-price">Rp.999.0000</p>
                  <button class="show-btn btn">buy</button>
               </div>
               </div>
         </div>

         <div class="col collection-item" data-item="sports" data-aos="zoom-in-up">
            <figure><img src="img/sports/sports2.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Nike Air Pro Max</h3>
               <p class="sub-heading">nike</p>
               <div class="col-footer">
                  <p class="show-price">Rp.2.399.000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="women" data-aos="zoom-in-up">
            <figure><img src="img/women/women4.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Precise Flexnit Kurven</h3>
               <p class="sub-heading">Precise</p>
               <div class="col-footer">
                  <p class="show-price">Rp450.0000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="men" data-aos="zoom-in-up">
            <figure><img src="img/men/men4.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Nike Air Jordan 11 "Banned"</h3>
               <p class="sub-heading">nike</p>
               <div class="col-footer">
                  <p class="show-price">Rp4.000.0000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="sports" data-aos="zoom-in-up">
            <figure><img src="img/sports/sports3.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Nike Kyrie 2 "Inferno"</h3>
               <p class="sub-heading">nike</p>
               <div class="col-footer">
                  <p class="show-price">Rp.3.199.0000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="women" data-aos="zoom-in-up">
            <figure><img src="img/women/women5.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Nike Blazer Mid 77</h3>
               <p class="sub-heading">nike</p>
               <div class="col-footer">
                  <p class="show-price">Rp2.000.000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="women" data-aos="zoom-in-up">
            <figure><img src="img/women/women9.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Nike Air Max 1 "Infrared"</h3>
               <p class="sub-heading">nike</p>
               <div class="col-footer">
                  <p class="show-price">Rp1.199.000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="sports" data-aos="zoom-in-up">
            <figure><img src="img/sports/sports4.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Hive Running Tilt</h3>
               <p class="sub-heading">Hive</p>
               <div class="col-footer">
                  <p class="show-price">Rp1.299.000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="women" data-aos="zoom-in-up">
            <figure><img src="img/women/women7.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Nike Golf "Green Lunar"</h3>
               <p class="sub-heading">nike</p>
               <div class="col-footer">
                  <p class="show-price">Rp1.899.000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="women" data-aos="zoom-in-up">
            <figure><img src="img/women/women8.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Nike Roshe One</h3>
               <p class="sub-heading">nike</p>
               <div class="col-footer">
                  <p class="show-price">Rp1.359.000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>

         <div class="col collection-item" data-item="sports" data-aos="zoom-in-up">
            <figure><img src="img/sports/sports5.png" alt=""></figure>
            <div class="col-body">
               <p class="rating-icon"><i class="fa-solid fa-star"></i> <span class="rating-num">4.1</span></p>
               <h3 class="heading-three">Converse Chuck Taylor 2018</h3>
               <p class="sub-heading">converse</p>
               <div class="col-footer">
                  <p class="show-price">Rp1.299.000</p>
                  <button class="show-btn btn">buy</button>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="testimonial container" data-aos="zoom-in-up">
      <div class="clr-one"></div>
      <div class="section-heading">
         <div class="heading">
            <p class="sub-heading">testimonial</p>
            <h2 class="heading-two">What our customer says</h2>
         </div>
      </div>
      <div class="wrapper">

         <div class="col" data-aos="zoom-in-up">
            <figure>
               <img src="thor.jpg" alt="Foto Rafi">
            </figure>
            <h3 class="heading-three">Rafi</h3>
            <p class="testi-messaa">
               "Saya sangat puas dengan pembelian di website ini! Kualitas sepatu yang saya terima melebihi ekspektasi saya. Nyaman dipakai dan desainnya sangat stylish. Pelayanan pelanggan juga luar biasa, respons cepat dan ramah. Pasti akan membeli lagi!"
            </p>
            <span class="rating">
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star"></i>
               <i class="fa-regular fa-star"></i>
            </span>
         </div>

         <div class="col" data-aos="zoom-in-up">
            <figure>
               <img src="natasha.jpg" alt="Foto Rachul">
            </figure>
            <h3 class="heading-three">Rachul</h3>
            <p class="testi-messaa">
               "Sudah lama mencari sepatu yang cocok untuk aktivitas sehari-hari, dan akhirnya menemukannya di sini! Sepatunya ringan, nyaman, dan sangat fashionable. Proses pemesanan juga mudah dan pengiriman cepat. Benar-benar pengalaman belanja yang menyenangkan!"
            </p>
            <span class="rating">
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star"></i>
            </span>
         </div>

         <div class="col" data-aos="zoom-in-up">
            <figure>
               <img src="hulk.jpg" alt="Foto Rahmat">
            </figure>
            <h3 class="heading-three">Rahmat</h3>
            <p class="testi-messaa">
               "Awalnya ragu untuk membeli sepatu online, tapi setelah membaca ulasan positif, saya mencobanya. Ternyata benar-benar worth it! Kualitas bahan sangat bagus, jahitannya rapi, dan ukurannya pas. Ditambah lagi, harganya sangat bersaing. Rekomendasi banget!"
            </p>
            <span class="rating">
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star"></i>
               <i class="fa-solid fa-star-half-stroke"></i>
            </span>
         </div>

      </div>
   </section>

  <section id="about-founder-ceo">
   <h2 class="section-title">𝐅𝐨𝐮𝐧𝐝𝐞𝐫 𝐀𝐧𝐝 𝐂𝐄𝐎</h2>
   <div class="founder-ceo-container">
       <div class="profile-card">
           <img src="founder.png" alt="Founder">
           <button class="toggle-btn" onclick="toggleDetails('founder-details')">Tap To Show Details</button>
           <div class="details" id="founder-details">
               <p><strong>Founder:</strong> Vivaldi Alfino Setio</p>
               <p><strong>Kelas:</strong> Broadband Multimedia 4B</p>
               <p><strong>NIM:</strong> 2303421042</p>
               <p class="social-links">
                   <a href="https://www.instagram.com/alfinosetio" target="_blank">Instagram</a> |
                   <a href="https://www.linkedin.com/in/vivaldi-alfino-setio" target="_blank">LinkedIn</a>
               </p>
           </div>
       </div>

       <div class="profile-card">
           <img src="ceo.png" alt="CEO">
           <button class="toggle-btn" onclick="toggleDetails('ceo-details')">Tap To Show Details</button>
           <div class="details" id="ceo-details">
               <p><strong>CEO:</strong> Muhammad Febryadi</p>
               <p><strong>Kelas:</strong> Broadband Multimedia 4B</p>
               <p><strong>NIM:</strong> 2303421027</p>
               <p class="social-links">
                   <a href="https://www.instagram.com/m_febryadi" target="_blank">Instagram</a> |
                   <a href="https://www.linkedin.com/in/muhammad-febryadi" target="_blank">LinkedIn</a>
               </p>
           </div>
       </div>
   </div>
</section>
  <style>
      .founder-ceo-container {
          display: flex;
          gap: 20px;
      }
      .profile {
          cursor: pointer;
          text-align: center;
      }
      .profile img {
          width: 150px;
          border-radius: 10px;
      }
      .details {
          display: none;
          margin-top: 10px;
      }
  </style>

  <script>
   function toggleDetails(id) {
       var element = document.getElementById(id);
       if (element.style.display === "none" || element.style.display === "") {
           element.style.display = "block";
       } else {
           element.style.display = "none";
       }
   }

</script>


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

   <script>
      document.getElementById("subscribeForm").addEventListener("submit", function(event) {
         event.preventDefault(); // Mencegah reload halaman

         let email = document.getElementById("emailInput").value;
         let message = document.getElementById("message");

         if (validateEmail(email)) {
            localStorage.setItem("subscribedEmail", email);
            message.innerHTML = "Thank you for subscribing!";
            message.style.color = "lightgreen";
            document.getElementById("emailInput").value = "";
         } else {
            message.innerHTML = "Please enter a valid email address.";
            message.style.color = "red";
         }
      });

      function validateEmail(email) {
         let re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
         return re.test(email);
      }
   </script>
   <div class="payment-methods">
    </div>
<div class="discount-section" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.2);">
    <label for="discountCodeInput" style="display: block; margin-bottom: 5px;">Kode Diskon (Opsional):</label>
    <input type="text" id="discountCodeInput" placeholder="Masukkan kode diskon" style="width: calc(100% - 70px); padding: 8px; border-radius: 5px; border: 1px solid #555; background-color: rgba(255,255,255,0.2); color: white; display: inline-block;">
    <button id="applyDiscountBtn" style="width: 60px; padding: 8px; border: none; border-radius: 5px; font-size: 0.9em; cursor: pointer; background-color: #007bff; color: white; display: inline-block; vertical-align: top;">Apply</button>
    <p id="discountMessage" style="margin-top: 5px; font-size: 0.85em; color: lightgreen;"></p>
</div>
<button id="processPaymentBtn" class="btn" style="margin-top: 20px;">Proses Pembayaran</button>
   <div class="social-floating-left">
      <section class="guide-section">
   <div class="guide-container">
     <h3 class="guide-title">Butuh Bantuan?</h3>
     <a href="panduan.pdf" class="guide-btn" download>
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
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="wa-logo">
            <path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
          </svg>
          <span class="wa-label">Chat Us</span>
        </a>
      </div>

      <div class="ig-btn-container">
        <a href="https://instagram.com/aksarawalk" class="ig-btn" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="ig-logo">
            <path fill="currentColor" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
          </svg>
          <span class="ig-label">Follow Us</span>
        </a>
      </div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const igBtn = document.querySelector('.ig-left-center');

        // Sembunyikan awal
        igBtn.classList.add('hidden');

        // Munculkan saat scroll 200px
        window.addEventListener('scroll', function() {
          if (window.scrollY > 200) {
            igBtn.classList.remove('hidden');
          } else {
            igBtn.classList.add('hidden');
          }
        });

        // Tetap muncul jika sudah di atas 200px saat load
        if (window.scrollY > 200) {
          igBtn.classList.remove('hidden');
        }
      });
      </script>
      <script>
         document.addEventListener('DOMContentLoaded', function() {
           const socialButtons = document.querySelector('.social-floating-left');

           // Sembunyikan awal
           socialButtons.classList.add('hidden');

           // Munculkan saat scroll 200px
           window.addEventListener('scroll', function() {
             if (window.scrollY > 200) {
               socialButtons.classList.remove('hidden');
             } else {
               socialButtons.classList.add('hidden');
             }
           });

           // Tetap muncul jika sudah di atas 200px saat load
           if (window.scrollY > 200) {
             socialButtons.classList.remove('hidden');
           }
         });
         </script>
   <footer class="container">
      <div class="wrapper">
         <div class="col" data-aos="zoom-in">
            <a href="index.html" class="logo">𝐀𝐤𝐬𝐚𝐫𝐚𝐖𝐚𝐥𝐤</a>
            <p class="about-website">AksaraWalk adalah platform fashion terbaru yang menghadirkan koleksi eksklusif <br> dengan desain modern dan elegan.Kami berkomitmen untuk memberikan produk berkualitas <br> tinggi dengan gaya yang selalu up-to-date.</p>
         </div>
         <div class="col" data-aos="zoom-in">
            <h4>quick links</h4>
            <a href="index.html">𝑯𝒐𝒎𝒆</a>
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

   <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
   <script>
      AOS.init();
   </script>
   <script src="script.js"></script>
   <div class="floating-elements">

<video id="webcam" style="display:none;"></video>
<canvas id="gestureCanvas" style="display:none;"></canvas>

<button class="enable-gesture-btn" id="enableGestureBtn" title="Aktifkan Kontrol Gestur">
    <i class="fa-solid fa-hand-pointer"></i> </button>

<style>
/* ... (existing CSS for chatbot, etc.) ... */

/* Style untuk tombol enable gesture */
.enable-gesture-btn {
    position: fixed;
    top: 20px; /* Posisikan 20px dari atas */
    right: 20px; /* Posisikan 20px dari kanan */
    left: auto; /* Pastikan 'left' diatur ke auto agar tidak berkonflik dengan 'right' */
    bottom: auto; /* Pastikan properti ini diatur */
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2bffc3, #18d2f8); /* Warna berbeda */
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(43, 251, 195, 0.4);
    z-index: 1002; /* Pastikan z-index cukup tinggi */
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    animation: float 3s ease-in-out infinite;
    transition: all 0.3s ease;
}

.enable-gesture-btn:hover {
    transform: scale(1.1) translateY(-5px);
    box-shadow: 0 8px 20px rgba(43, 251, 195, 0.6);
}

/* ... (Optional: style for the video/canvas feed for debugging) ... */

/* Style untuk tombol enable voice */
.enable-voice-btn {
    position: fixed;
    top: 20px; /* Sesuaikan dengan posisi 'top' tombol gesture jika ingin di baris yang sama */
    left: 100px; /* Atur posisi agar tidak menumpuk dengan tombol chatbot */
    right: auto; /* Pastikan properti ini diatur */
    bottom: auto; /* Pastikan properti ini diatur */
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f39c12, #e67e22); /* Warna oranye */
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(243, 156, 18, 0.4);
    z-index: 999; /* Z-index sedikit lebih rendah dari gesture */
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    animation: float 3s ease-in-out infinite;
    transition: all 0.3s ease;
}

.enable-voice-btn:hover {
    transform: scale(1.1) translateY(-5px);
    box-shadow: 0 8px 20px rgba(243, 156, 18, 0.6);
}

.enable-voice-btn.active {
    background: linear-gradient(135deg, #27ae60, #2ecc71); /* Warna hijau saat aktif */
}

/* Responsif untuk mobile */
@media (max-width: 768px) {
    /* Tombol Gesture di Mobile */
    .enable-gesture-btn {
        top: 10px; /* Posisikan 10px dari atas di mobile */
        right: 10px; /* Posisikan 10px dari kanan di mobile */
        left: auto;
        width: 50px;
        height: 50px;
        font-size: 20px;
    }

    /* Tombol Voice di Mobile (jika masih digunakan) */
    .enable-voice-btn {
        top: 10px;
        left: 10px; /* Sesuaikan agar tidak menumpuk dengan chatbot jika masih ada */
        right: auto;
        width: 50px;
        height: 50px;
        font-size: 20px;
    }

    /* Tombol Chatbot di Mobile */
    .floating-chatbot-btn {
        top: 10px;
        left: 10px;
        width: 50px;
        height: 50px;
        font-size: 24px;
    }

    /* Chatbot Container di Mobile */
    .chatbot-container {
        top: 50%;
        left: 5%;
        right: 5%;
        transform: translateY(-50%);
        width: 90%;
        height: 70%;
    }
}
</style>


</body>
</html>
<?php include 'footer.php'; ?>