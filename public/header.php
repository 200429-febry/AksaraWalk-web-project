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
</head>


<body>
 </script>
   <!-- Add this at the top of your <body> -->
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
   <!--AI-->
  

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


   <div class="popup wishlist-popup">
   <div class="popup-content">
      <span class="close-popup">&times;</span>
      <h3>My Favorites</h3>
      <div class="wishlist-items">
         </div>
   </div>
</div>

<div class="popup cart-popup">
   <div class="popup-content">
      <span class="close-popup">&times;</span>
      <h3>Shopping Cart</h3>
      <div class="cart-items">
         </div>
      <div class="cart-total">
         <p>Total: <span class="total-price">Rp0</span></p>
         <button class="checkout-btn btn">Checkout</button>
      </div>
   </div>
</div>

<div class="popup payment-modal">
   <div class="popup-content">
      <span class="close-popup">&times;</span>
      <h3>Pilih Metode Pembayaran</h3>
      <div class="payment-options">
         <div class="payment-option" data-method="GoPay">
            <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg" alt="GoPay">
            <span>GoPay</span>
         </div>
         <div class="payment-option" data-method="PayPal">
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal">
            <span>PayPal</span>
         </div>
         <div class="payment-option" data-method="BCA">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia_logo.svg" alt="BCA">
            <span>BCA Virtual Account</span>
         </div>
         <div class="payment-option" data-method="Mandiri">
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" alt="Mandiri">
            <span>Mandiri Virtual Account</span>
         </div>
          <div class="payment-option" data-method="BRI">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/2560px-BANK_BRI_logo.svg.png" alt="BRI">
            <span>BRI Virtual Account</span>
         </div>
         <div class="payment-option" data-method="ShopeePay">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/ShopeePay_logo.svg/1200px-ShopeePay_logo.svg.png" alt="ShopeePay">
            <span>ShopeePay</span>
         </div>
         <div class="payment-option" data-method="COD">
            <img src="https://cdn-icons-png.flaticon.com/512/649/649451.png" alt="COD">
            <span>Cash on Delivery (COD)</span>
         </div>
      </div>
      <button id="process-payment-btn" class="btn" disabled>Lanjutkan Pembayaran</button>
   </div>
</div>