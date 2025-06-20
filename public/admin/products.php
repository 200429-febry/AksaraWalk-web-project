<?php
// public/admin/products.php
require_once '../session_check.php';
requireAdmin(); // Ensures only logged-in admins can access this page

include '../header.php'; // Include the header
?>

<main class="container" style="padding-top: 120px; padding-bottom: 60px; color: white;">
    <div class="section-heading">
        <div class="heading">
            <h2 class="heading-two">Manajemen <span>Produk</span></h2>
            <p class="sub-heading">Tambah, Edit, dan Hapus Produk</p>
        </div>
    </div>

    <div class="product-management-section" style="background-color: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; margin-bottom: 40px;">
        <h3 style="color: #c32bfb; margin-bottom: 20px;">Tambah Produk Baru</h3>
        <form id="addProductForm" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div style="grid-column: span 2;">
                <label for="productName" style="display: block; margin-bottom: 5px;">Nama Produk:</label>
                <input type="text" id="productName" name="name" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #555; background-color: rgba(255,255,255,0.2); color: white;">
            </div>
            <div>
                <label for="productCategory" style="display: block; margin-bottom: 5px;">Kategori:</label>
                <input type="text" id="productCategory" name="category" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #555; background-color: rgba(255,255,255,0.2); color: white;">
            </div>
            <div>
                <label for="productPrice" style="display: block; margin-bottom: 5px;">Harga (IDR):</label>
                <input type="number" id="productPrice" name="price" step="0.01" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #555; background-color: rgba(255,255,255,0.2); color: white;">
            </div>
            <div>
                <label for="productStock" style="display: block; margin-bottom: 5px;">Stok:</label>
                <input type="number" id="productStock" name="stock" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #555; background-color: rgba(255,255,255,0.2); color: white;">
            </div>
            <div style="grid-column: span 2;">
                <label for="productImage" style="display: block; margin-bottom: 5px;">URL Gambar (Opsional):</label>
                <input type="text" id="productImage" name="image_url" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #555; background-color: rgba(255,255,255,0.2); color: white;">
            </div>
            <div style="grid-column: span 2;">
                <button type="submit" style="width: 100%; padding: 12px; border: none; border-radius: 8px; font-size: 1em; font-weight: 600; cursor: pointer; background: linear-gradient(45deg, #800080, #9400D3); color: white; transition: 0.3s;">Tambah Produk</button>
            </div>
        </form>
    </div>

    <h3 style="color: #c32bfb; margin-bottom: 20px;">Daftar Produk</h3>
    <div style="overflow-x:auto;">
        <table id="productsTable" style="width:100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: rgba(255,255,255,0.1);">
                    <th style="padding: 12px;">ID</th>
                    <th style="padding: 12px;">Nama</th>
                    <th style="padding: 12px;">Kategori</th>
                    <th style="padding: 12px;">Harga</th>
                    <th style="padding: 12px;">Stok</th>
                    <th style="padding: 12px;">Gambar</th>
                    <th style="padding: 12px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="7" style="text-align: center; padding: 20px;">Memuat produk...</td></tr>
            </tbody>
        </table>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addProductForm = document.getElementById('addProductForm');
    const productsTableBody = document.getElementById('productsTable').getElementsByTagName('tbody')[0];

    async function fetchProducts() {
        productsTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 20px;">Memuat produk...</td></tr>';
        try {
            const response = await fetch('../api/products.php');
            const products = await response.json();

            productsTableBody.innerHTML = ''; // Clear loading message

            if (products.error) {
                productsTableBody.innerHTML = `<tr><td colspan="7" style="color: red; text-align: center;">Error: ${products.error}</td></tr>`;
                return;
            }

            if (products.length === 0) {
                productsTableBody.innerHTML = `<tr><td colspan="7" style="text-align: center;">Belum ada produk.</td></tr>`;
            } else {
                products.forEach(product => {
                    const row = productsTableBody.insertRow();
                    row.innerHTML = `
                        <td style='padding: 12px; border-top: 1px solid #444;'>${product.id}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>${product.name}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>${product.category}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(product.price)}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>${product.stock}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>
                            ${product.image_url ? `<img src="${product.image_url}" alt="${product.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">` : 'N/A'}
                        </td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>
                            <button class="edit-product-btn" data-id="${product.id}" style="background-color: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; margin-right: 5px;">Edit</button>
                            <button class="delete-product-btn" data-id="${product.id}" style="background-color: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer;">Hapus</button>
                        </td>
                    `;
                });
            }
        } catch (error) {
            console.error('Error fetching products:', error);
            productsTableBody.innerHTML = `<tr><td colspan="7" style="color: red; text-align: center;">Gagal memuat produk.</td></tr>`;
        }
    }

    // Add Product
    addProductForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        const formData = new FormData(addProductForm);
        const productData = Object.fromEntries(formData.entries());

        // Convert price and stock to correct types
        productData.price = parseFloat(productData.price);
        productData.stock = parseInt(productData.stock, 10);

        try {
            const response = await fetch('../api/products.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(productData)
            });
            const result = await response.json();
            if (response.ok) {
                alert(result.message);
                addProductForm.reset(); // Clear form
                fetchProducts(); // Refresh list
            } else {
                alert('Error: ' + (result.error || 'Terjadi kesalahan saat menambahkan produk.'));
            }
        } catch (error) {
            console.error('Error adding product:', error);
            alert('Terjadi kesalahan saat berkomunikasi dengan server.');
        }
    });

    // Edit/Delete Product (Delegation)
    productsTableBody.addEventListener('click', async function(event) {
        if (event.target.classList.contains('delete-product-btn')) {
            const productId = event.target.dataset.id;
            if (confirm(`Apakah Anda yakin ingin menghapus produk ID ${productId}?`)) {
                try {
                    const response = await fetch(`../api/products.php?id=${productId}`, {
                        method: 'DELETE'
                    });
                    const result = await response.json();
                    if (response.ok) {
                        alert(result.message);
                        fetchProducts(); // Refresh list
                    } else {
                        alert('Error: ' + (result.error || 'Terjadi kesalahan saat menghapus produk.'));
                    }
                } catch (error) {
                    console.error('Error deleting product:', error);
                    alert('Terjadi kesalahan saat berkomunikasi dengan server.');
                }
            }
        }
        // TODO: Implement Edit functionality (e.g., open a modal, pre-fill form)
        if (event.target.classList.contains('edit-product-btn')) {
            const productId = event.target.dataset.id;
            alert('Fitur edit untuk produk ID ' + productId + ' belum diimplementasikan sepenuhnya. Anda bisa menambahkan modal atau form pre-filled di sini.');
            // Example of how you might fetch data for editing:
            // const response = await fetch(`../api/products.php?id=${productId}`);
            // const product = await response.json();
            // if (response.ok && product) {
            //     // Populate a modal/form with product data for editing
            // }
        }
    });

    // Initial fetch of products when page loads
    fetchProducts();
});
</script>

<?php include '../footer.php'; ?>