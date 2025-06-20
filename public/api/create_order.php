<?php
// public/api/create_order.php

require_once '../db_connect.php';
require_once '../session_check.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Anda harus login untuk melakukan pemesanan.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$cart_items = $data['cart_items'] ?? [];
$discount_code = $data['discount_code'] ?? null; // Get discount code from frontend

if (empty($cart_items)) {
    http_response_code(400);
    echo json_encode(['error' => 'Keranjang belanja kosong.']);
    exit();
}

$conn->begin_transaction(); // Start transaction for atomicity

try {
    $total_amount_before_discount = 0;
    foreach ($cart_items as $item) {
        $price = filter_var($item['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $quantity = filter_var($item['quantity'], FILTER_VALIDATE_INT);

        if ($price === false || $quantity === false || $quantity <= 0) {
            throw new mysqli_sql_exception("Invalid item price or quantity in cart.");
        }
        $total_amount_before_discount += ($price * $quantity);
    }

    $final_total_amount = $total_amount_before_discount;
    $discount_applied_id = null;
    $amount_saved = 0;

    // --- Apply Discount Logic (re-validate on server-side for security) ---
    if (!empty($discount_code)) {
        $stmt_discount = $conn->prepare("SELECT id, type, value, min_purchase, start_date, end_date FROM discounts WHERE code = ? AND is_active = TRUE");
        $stmt_discount->bind_param("s", $discount_code);
        $stmt_discount->execute();
        $res_discount = $stmt_discount->get_result();

        if ($discount = $res_discount->fetch_assoc()) {
            $now = new DateTime();
            $start_date = new DateTime($discount['start_date']);
            $end_date = new DateTime($discount['end_date']);

            if ($now >= $start_date && $now <= $end_date && $total_amount_before_discount >= $discount['min_purchase']) {
                if ($discount['type'] === 'percentage') {
                    $amount_saved = $total_amount_before_discount * ($discount['value'] / 100);
                } else { // fixed amount
                    $amount_saved = $discount['value'];
                    if ($amount_saved > $total_amount_before_discount) {
                        $amount_saved = $total_amount_before_discount;
                    }
                }
                $final_total_amount = $total_amount_before_discount - $amount_saved;
                $discount_applied_id = $discount['id'];
            }
        }
        $stmt_discount->close();
    }


    // Insert into orders table
    $stmt_order = $conn->prepare("INSERT INTO orders (pelanggan_id, total_amount, status) VALUES (?, ?, 'pending')");
    $stmt_order->bind_param("id", $user_id, $final_total_amount); // Use final_total_amount
    $stmt_order->execute();
    $order_id = $stmt_order->insert_id;
    $stmt_order->close();

    // If discount was applied, log it
    if ($discount_applied_id && $amount_saved > 0) {
        $stmt_log_discount = $conn->prepare("INSERT INTO used_discounts (order_id, discount_id, amount_saved) VALUES (?, ?, ?)");
        $stmt_log_discount->bind_param("iid", $order_id, $discount_applied_id, $amount_saved);
        $stmt_log_discount->execute();
        $stmt_log_discount->close();
    }

    // Insert into order_items and update product stock
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
    $stmt_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?"); // Prevent negative stock

    foreach ($cart_items as $item) {
        $product_id = $item['id'];
        $quantity = $item['quantity'];
        $price_at_purchase = filter_var($item['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        $stmt_check_stock = $conn->prepare("SELECT stock, name FROM products WHERE id = ?");
        $stmt_check_stock->bind_param("i", $product_id);
        $stmt_check_stock->execute();
        $res_stock = $stmt_check_stock->get_result();
        $current_product = $res_stock->fetch_assoc();
        $stmt_check_stock->close();

        if (!$current_product || $current_product['stock'] < $quantity) {
            throw new mysqli_sql_exception('Stok tidak cukup untuk produk ' . htmlspecialchars($current_product['name'] ?? 'ID ' . $product_id) . '. Sisa stok: ' . htmlspecialchars($current_product['stock'] ?? '0'));
        }

        $stmt_item->bind_param("iiid", $order_id, $product_id, $quantity, $price_at_purchase);
        $stmt_item->execute();

        $stmt_stock->bind_param("iii", $quantity, $product_id, $quantity);
        $stmt_stock->execute();
        if ($stmt_stock->affected_rows === 0) {
            throw new mysqli_sql_exception('Gagal memperbarui stok untuk produk ' . htmlspecialchars($item['name']) . '. Mungkin stok sudah kurang atau produk tidak ada.');
        }
    }

    $conn->commit();
    echo json_encode([
        'message' => 'Pesanan berhasil dibuat!',
        'order_id' => $order_id,
        'original_total_amount' => $total_amount_before_discount,
        'discount_amount_saved' => $amount_saved,
        'final_total_amount' => $final_total_amount
    ]);

} catch (mysqli_sql_exception $exception) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => 'Gagal membuat pesanan: ' . $exception->getMessage()]);
} finally {
    if (isset($stmt_order)) $stmt_order->close();
    if (isset($stmt_item)) $stmt_item->close();
    if (isset($stmt_stock)) $stmt_stock->close();
    // No need to close $stmt_log_discount as it might not be set if no discount
    $conn->close();
}
?>