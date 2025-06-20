<?php
// public/api/apply_discount.php

require_once '../db_connect.php';
// session_check.php is not strictly needed here if we only validate, not apply, but useful if discount is user-specific
// require_once '../session_check.php';

header('Content-Type: application/json');

$code = $_GET['code'] ?? '';
$total_amount = filter_var($_GET['total'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

if (empty($code) || $total_amount <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Kode diskon dan total belanja diperlukan.']);
    exit();
}

$stmt = $conn->prepare("SELECT id, type, value, min_purchase, start_date, end_date FROM discounts WHERE code = ? AND is_active = TRUE");
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();

if ($discount = $result->fetch_assoc()) {
    $now = new DateTime();
    $start_date = new DateTime($discount['start_date']);
    $end_date = new DateTime($discount['end_date']);

    if ($now < $start_date || $now > $end_date) {
        http_response_code(400);
        echo json_encode(['error' => 'Kode diskon sudah kadaluarsa atau belum aktif.']);
        exit();
    }

    if ($total_amount < $discount['min_purchase']) {
        http_response_code(400);
        echo json_encode(['error' => 'Total belanja Anda kurang dari batas minimum ' . number_format($discount['min_purchase'], 0, ',', '.') . ' untuk diskon ini.']);
        exit();
    }

    $discount_amount = 0;
    if ($discount['type'] === 'percentage') {
        $discount_amount = $total_amount * ($discount['value'] / 100);
    } else { // fixed amount
        $discount_amount = $discount['value'];
        // Ensure fixed discount doesn't make total negative
        if ($discount_amount > $total_amount) {
            $discount_amount = $total_amount;
        }
    }

    echo json_encode([
        'message' => 'Diskon berhasil diterapkan.',
        'discount' => [
            'id' => $discount['id'],
            'code' => $discount['code'],
            'type' => $discount['type'],
            'value' => (float)$discount['value'],
            'amount' => (float)$discount_amount // The actual amount saved
        ]
    ]);

} else {
    http_response_code(404);
    echo json_encode(['error' => 'Kode diskon tidak ditemukan atau tidak aktif.']);
}

$stmt->close();
$conn->close();
?>