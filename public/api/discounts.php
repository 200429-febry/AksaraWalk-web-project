<?php
// public/api/discounts.php

require_once '../db_connect.php';
require_once '../session_check.php';

header('Content-Type: application/json');

// Only allow admin users to access this API
if (!isAdmin()) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Akses ditolak. Hanya admin yang bisa mengelola diskon.']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST': // Create a new discount
        $data = json_decode(file_get_contents('php://input'), true);

        $code = $data['code'] ?? '';
        $type = $data['type'] ?? '';
        $value = $data['value'] ?? 0.0;
        $min_purchase = $data['min_purchase'] ?? 0.0;
        $start_date = $data['start_date'] ?? '';
        $end_date = $data['end_date'] ?? '';
        $is_active = $data['is_active'] ?? false;

        if (empty($code) || empty($type) || $value <= 0 || empty($start_date) || empty($end_date)) {
            http_response_code(400);
            echo json_encode(['error' => 'Kode, tipe, nilai, tanggal mulai, dan tanggal berakhir harus diisi dengan benar.']);
            exit();
        }

        if (!in_array($type, ['percentage', 'fixed'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Tipe diskon tidak valid.']);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO discounts (code, type, value, min_purchase, start_date, end_date, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssddssi", $code, $type, $value, $min_purchase, $start_date, $end_date, $is_active);

        if ($stmt->execute()) {
            http_response_code(201); // Created
            echo json_encode(['message' => 'Diskon berhasil ditambahkan.', 'discount_id' => $stmt->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal menambahkan diskon: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'GET': // Read discounts
        $discount_id = $_GET['id'] ?? null;
        if ($discount_id) {
            $stmt = $conn->prepare("SELECT * FROM discounts WHERE id = ?");
            $stmt->bind_param("i", $discount_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($discount = $result->fetch_assoc()) {
                echo json_encode($discount);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Diskon tidak ditemukan.']);
            }
            $stmt->close();
        } else {
            $sql = "SELECT * FROM discounts ORDER BY created_at DESC";
            $result = $conn->query($sql);
            $discounts = [];
            while ($row = $result->fetch_assoc()) {
                $discounts[] = $row;
            }
            echo json_encode($discounts);
        }
        break;

    case 'PUT': // Update a discount (only status for now)
        $data = json_decode(file_get_contents('php://input'), true);
        $discount_id = $data['id'] ?? null;
        $is_active = isset($data['is_active']) ? (int)$data['is_active'] : null;

        if (!$discount_id || $is_active === null) {
            http_response_code(400);
            echo json_encode(['error' => 'ID diskon dan status aktif diperlukan untuk update.']);
            exit();
        }

        $stmt = $conn->prepare("UPDATE discounts SET is_active = ? WHERE id = ?");
        $stmt->bind_param("ii", $is_active, $discount_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['message' => 'Status diskon berhasil diupdate.']);
            } else {
                echo json_encode(['message' => 'Diskon tidak ditemukan atau status tidak berubah.']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal mengupdate diskon: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE': // Delete a discount
        $discount_id = $_GET['id'] ?? null;

        if (!$discount_id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID Diskon diperlukan untuk dihapus.']);
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM discounts WHERE id = ?");
        $stmt->bind_param("i", $discount_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['message' => 'Diskon berhasil dihapus.']);
            } else {
                echo json_encode(['message' => 'Diskon tidak ditemukan.']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal menghapus diskon: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Metode HTTP tidak diizinkan.']);
        break;
}

$conn->close();
?>