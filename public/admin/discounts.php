<?php
// public/admin/discounts.php
require_once '../session_check.php';
requireAdmin();

include '../header.php';
?>

<main class="container" style="padding-top: 120px; padding-bottom: 60px; color: white;">
    <div class="section-heading">
        <div class="heading">
            <h2 class="heading-two">Manajemen <span>Diskon & Promo</span></h2>
            <p class="sub-heading">Kelola kode diskon untuk pelanggan</p>
        </div>
    </div>

    <div class="discount-management-section" style="background-color: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; margin-bottom: 40px;">
        <h3 style="color: #c32bfb; margin-bottom: 20px;">Tambah Kode Diskon Baru</h3>
        <form id="addDiscountForm" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label for="discountCode" style="display: block; margin-bottom: 5px;">Kode Diskon:</label>
                <input type="text" id="discountCode" name="code" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #555; background-color: rgba(255,255,255,0.2); color: white;">
            </div>
            <div>
                <label for="discountType" style="display: block; margin-bottom: 5px;">Tipe Diskon:</label>
                <select id="discountType" name="type" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #555; background-color: rgba(255,255,255,0.2); color: white;">
                    <option value="percentage">Persentase (%)</option>
                    <option value="fixed">Nominal (Rp)</option>
                </select>
            </div>
            <div>
                <label for="discountValue" style="display: block; margin-bottom: 5px;">Nilai Diskon:</label>
                <input type="number" id="discountValue" name="value" step="0.01" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #555; background-color: rgba(255,255,255,0.2); color: white;">
            </div>
            <div>
                <label for="minPurchase" style="display: block; margin-bottom: 5px;">Min. Pembelian (Opsional):</label>
                <input type="number" id="minPurchase" name="min_purchase" step="0.01" value="0" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #555; background-color: rgba(255,255,255,0.2); color: white;">
            </div>
            <div>
                <label for="startDate" style="display: block; margin-bottom: 5px;">Tanggal Mulai:</label>
                <input type="datetime-local" id="startDate" name="start_date" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #555; background-color: rgba(255,255,255,0.2); color: white;">
            </div>
            <div>
                <label for="endDate" style="display: block; margin-bottom: 5px;">Tanggal Berakhir:</label>
                <input type="datetime-local" id="endDate" name="end_date" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #555; background-color: rgba(255,255,255,0.2); color: white;">
            </div>
            <div style="grid-column: span 2;">
                <label>
                    <input type="checkbox" id="isActive" name="is_active" checked style="margin-right: 5px;"> Aktifkan Diskon
                </label>
            </div>
            <div style="grid-column: span 2;">
                <button type="submit" style="width: 100%; padding: 12px; border: none; border-radius: 8px; font-size: 1em; font-weight: 600; cursor: pointer; background: linear-gradient(45deg, #800080, #9400D3); color: white; transition: 0.3s;">Tambah Diskon</button>
            </div>
        </form>
    </div>

    <h3 style="color: #c32bfb; margin-bottom: 20px;">Daftar Diskon Aktif</h3>
    <div style="overflow-x:auto;">
        <table id="discountsTable" style="width:100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: rgba(255,255,255,0.1);">
                    <th style="padding: 12px;">ID</th>
                    <th style="padding: 12px;">Kode</th>
                    <th style="padding: 12px;">Tipe</th>
                    <th style="padding: 12px;">Nilai</th>
                    <th style="padding: 12px;">Min. Beli</th>
                    <th style="padding: 12px;">Mulai</th>
                    <th style="padding: 12px;">Berakhir</th>
                    <th style="padding: 12px;">Aktif</th>
                    <th style="padding: 12px;">Dibuat</th>
                    <th style="padding: 12px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="10" style="text-align: center; padding: 20px;">Memuat diskon...</td></tr>
            </tbody>
        </table>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addDiscountForm = document.getElementById('addDiscountForm');
    const discountsTableBody = document.getElementById('discountsTable').getElementsByTagName('tbody')[0];

    async function fetchDiscounts() {
        discountsTableBody.innerHTML = '<tr><td colspan="10" style="text-align: center; padding: 20px;">Memuat diskon...</td></tr>';
        try {
            const response = await fetch('../api/discounts.php');
            const discounts = await response.json();

            discountsTableBody.innerHTML = ''; // Clear loading message

            if (discounts.error) {
                discountsTableBody.innerHTML = `<tr><td colspan="10" style="color: red; text-align: center;">Error: ${discounts.error}</td></tr>`;
                return;
            }

            if (discounts.length === 0) {
                discountsTableBody.innerHTML = `<tr><td colspan="10" style="text-align: center;">Belum ada kode diskon.</td></tr>`;
            } else {
                discounts.forEach(discount => {
                    const row = discountsTableBody.insertRow();
                    row.innerHTML = `
                        <td style='padding: 12px; border-top: 1px solid #444;'>${discount.id}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>${discount.code}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>${discount.type === 'percentage' ? 'Persentase' : 'Nominal'}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>${discount.type === 'percentage' ? discount.value + '%' : new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(discount.value)}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(discount.min_purchase)}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>${new Date(discount.start_date).toLocaleString()}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>${new Date(discount.end_date).toLocaleString()}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>${discount.is_active ? 'Ya' : 'Tidak'}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>${new Date(discount.created_at).toLocaleString()}</td>
                        <td style='padding: 12px; border-top: 1px solid #444;'>
                            <button class="toggle-active-btn" data-id="${discount.id}" data-active="${discount.is_active}" style="background-color: ${discount.is_active ? '#ffc107' : '#28a745'}; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; margin-right: 5px;">${discount.is_active ? 'Nonaktifkan' : 'Aktifkan'}</button>
                            <button class="delete-discount-btn" data-id="${discount.id}" style="background-color: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer;">Hapus</button>
                        </td>
                    `;
                    // Optional: Add edit button if you implement PUT for discounts
                });
            }
        } catch (error) {
            console.error('Error fetching discounts:', error);
            discountsTableBody.innerHTML = `<tr><td colspan="10" style="color: red; text-align: center;">Gagal memuat diskon.</td></tr>`;
        }
    }

    // Add Discount
    addDiscountForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        const formData = new FormData(addDiscountForm);
        const discountData = Object.fromEntries(formData.entries());

        discountData.value = parseFloat(discountData.value);
        discountData.min_purchase = parseFloat(discountData.min_purchase);
        discountData.is_active = formData.has('is_active'); // Check if checkbox is checked

        try {
            const response = await fetch('../api/discounts.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(discountData)
            });
            const result = await response.json();
            if (response.ok) {
                alert(result.message);
                addDiscountForm.reset();
                fetchDiscounts();
            } else {
                alert('Error: ' + (result.error || 'Terjadi kesalahan saat menambahkan diskon.'));
            }
        } catch (error) {
            console.error('Error adding discount:', error);
            alert('Terjadi kesalahan saat berkomunikasi dengan server.');
        }
    });

    // Delete and Toggle Active (Delegation)
    discountsTableBody.addEventListener('click', async function(event) {
        if (event.target.classList.contains('delete-discount-btn')) {
            const discountId = event.target.dataset.id;
            if (confirm(`Apakah Anda yakin ingin menghapus diskon ID ${discountId}?`)) {
                try {
                    const response = await fetch(`../api/discounts.php?id=${discountId}`, {
                        method: 'DELETE'
                    });
                    const result = await response.json();
                    if (response.ok) {
                        alert(result.message);
                        fetchDiscounts();
                    } else {
                        alert('Error: ' + (result.error || 'Terjadi kesalahan saat menghapus diskon.'));
                    }
                } catch (error) {
                    console.error('Error deleting discount:', error);
                    alert('Terjadi kesalahan saat berkomunikasi dengan server.');
                }
            }
        } else if (event.target.classList.contains('toggle-active-btn')) {
            const discountId = event.target.dataset.id;
            const currentStatus = event.target.dataset.active === 'true'; // Convert string to boolean
            const newStatus = !currentStatus;
            
            if (confirm(`Apakah Anda yakin ingin ${newStatus ? 'mengaktifkan' : 'menonaktifkan'} diskon ID ${discountId}?`)) {
                try {
                    const response = await fetch(`../api/discounts.php`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: discountId, is_active: newStatus })
                    });
                    const result = await response.json();
                    if (response.ok) {
                        alert(result.message);
                        fetchDiscounts();
                    } else {
                        alert('Error: ' + (result.error || 'Terjadi kesalahan saat mengubah status diskon.'));
                    }
                } catch (error) {
                    console.error('Error toggling discount status:', error);
                    alert('Terjadi kesalahan saat berkomunikasi dengan server.');
                }
            }
        }
    });

    fetchDiscounts();
});
</script>

<?php include '../footer.php'; ?>