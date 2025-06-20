<?php
// public/api/products.php

require_once '../db_connect.php';
require_once '../session_check.php'; // For authentication check

header('Content-Type: application/json');

// Only allow admin users to access this API for POST, PUT, DELETE
// GET can be public or restricted based on need
$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'GET' && !isAdmin()) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Akses ditolak. Hanya admin yang bisa mengelola produk.']);
    exit();
}

switch ($method) {
    case 'POST': // Create a new product
        $data = json_decode(file_get_contents('php://input'), true);

        $name = $data['name'] ?? '';
        $category = $data['category'] ?? '';
        $price = $data['price'] ?? 0.0;
        $stock = $data['stock'] ?? 0;
        $image_url = $data['image_url'] ?? '';

        if (empty($name) || empty($category) || $price <= 0 || $stock < 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Nama, kategori, harga, dan stok harus diisi dengan benar.']);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO products (name, category, price, stock, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $name, $category, $price, $stock, $image_url);

        if ($stmt->execute()) {
            http_response_code(201); // Created
            echo json_encode(['message' => 'Produk berhasil ditambahkan.', 'product_id' => $stmt->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal menambahkan produk: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'GET': // Read products (can be filtered/paginated)
        $product_id = $_GET['id'] ?? null;
        if ($product_id) {
            $stmt = $conn->prepare("SELECT id, name, category, price, stock, image_url FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($product = $result->fetch_assoc()) {
                echo json_encode($product);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Produk tidak ditemukan.']);
            }
            $stmt->close();
        } else {
            $sql = "SELECT id, name, category, price, stock, image_url FROM products";
            $result = $conn->query($sql);
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            echo json_encode($products);
        }
        break;

    case 'PUT': // Update a product (requires ID)
        $data = json_decode(file_get_contents('php://input'), true);
        $product_id = $data['id'] ?? null;
        $name = $data['name'] ?? null;
        $category = $data['category'] ?? null;
        $price = $data['price'] ?? null;
        $stock = $data['stock'] ?? null;
        $image_url = $data['image_url'] ?? null;

        if (!$product_id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID Produk diperlukan untuk update.']);
            exit();
        }

        $updates = [];
        $params = [];
        $types = '';

        if ($name !== null) { $updates[] = "name = ?"; $params[] = $name; $types .= 's'; }
        if ($category !== null) { $updates[] = "category = ?"; $params[] = $category; $types .= 's'; }
        if ($price !== null) { $updates[] = "price = ?"; $params[] = $price; $types .= 'd'; }
        if ($stock !== null) { $updates[] = "stock = ?"; $params[] = $stock; $types .= 'i'; }
        if ($image_url !== null) { $updates[] = "image_url = ?"; $params[] = $image_url; $types .= 's'; }

        if (empty($updates)) {
            http_response_code(400);
            echo json_encode(['error' => 'Tidak ada data untuk diupdate.']);
            exit();
        }

        $sql = "UPDATE products SET " . implode(', ', $updates) . " WHERE id = ?";
        $params[] = $product_id;
        $types .= 'i';

        $stmt = $conn->prepare($sql);
        // Use call_user_func_array to bind parameters dynamically
        call_user_func_array([$stmt, 'bind_param'], array_merge([$types], $params));

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['message' => 'Produk berhasil diupdate.']);
            } else {
                echo json_encode(['message' => 'Produk tidak ditemukan atau tidak ada perubahan.']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal mengupdate produk: ' . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE': // Delete a product (requires ID)
        $product_id = $_GET['id'] ?? null; // For DELETE, can be passed in query string or body

        if (!$product_id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID Produk diperlukan untuk dihapus.']);
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['message' => 'Produk berhasil dihapus.']);
            } else {
                echo json_encode(['message' => 'Produk tidak ditemukan.']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal menghapus produk: ' . $stmt->error]);
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