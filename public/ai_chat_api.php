<?php
// public/ai_chat_api.php

require_once 'db_connect.php'; // Sertakan file koneksi database

header('Content-Type: application/json');

// Mengambil kunci API dari variabel lingkungan atau file konfigurasi
// Sebaiknya tidak hardcode kunci API Anda di sini di lingkungan produksi.
// Gunakan variabel lingkungan atau sistem konfigurasi yang aman.
$gemini_api_key = getenv('GEMINI_API_KEY') ?: 'AIzaSyBBnZaBr9KWm6xT-lWlGu4Ni6eTcue_U64';

if ($gemini_api_key === 'AIzaSyBBnZaBr9KWm6xT-lWlGu4Ni6eTcue_U64') {
    http_response_code(500);
    echo json_encode(['error' => 'GEMINI_API_KEY belum diatur. Harap atur di environment variable atau ganti placeholder.']);
    exit();
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$user_message = $data['message'] ?? '';
$history = $data['history'] ?? []; // Menerima riwayat percakapan dari frontend

if (empty($user_message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Pesan tidak boleh kosong.']);
    exit();
}

// --- Logika Akses Database Produk ---
$product_info = '';
// Contoh: Deteksi pertanyaan tentang produk
if (preg_match('/(sepatu|produk|harga|tersedia|model)\s*(.*?)(\?|$)/i', $user_message, $matches)) {
    $keyword = trim($matches[2]);
    if (!empty($keyword)) {
        // Query database untuk mencari produk
        $stmt = $conn->prepare("SELECT name, category, price FROM products WHERE name LIKE ? OR category LIKE ? LIMIT 1");
        $search_term = "%" . $keyword . "%";
        $stmt->bind_param("ss", $search_term, $search_term);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $product_info = "Informasi produk:\n";
            $product_info .= "Nama: " . $product['name'] . "\n";
            $product_info .= "Kategori: " . $product['category'] . "\n";
            $product_info .= "Harga: Rp " . number_format($product['price'], 0, ',', '.') . "\n";
            $product_info .= "Apakah ada hal lain yang ingin Anda ketahui tentang produk ini?\n";
        } else {
            $product_info = "Maaf, saya tidak menemukan informasi tentang produk yang Anda maksud. Bisakah Anda memberikan nama yang lebih spesifik?\n";
        }
        $stmt->close();
    }
}

// --- Integrasi Panduan PDF (Konsep) ---
// Untuk mengintegrasikan panduan PDF, Anda perlu mengekstrak teks dari PDF
// dan membuat basis data atau indeks yang dapat dicari.
// Contoh sederhana: Jika ada pertanyaan tentang "heel drop"
$pdf_keywords = [
    'heel drop' => 'Heel drop adalah perbedaan ketinggian antara tumit dan jari kaki pada sepatu. Ini memengaruhi cara kaki mendarat dan biomekanik lari Anda.',
    'carbon plate' => 'Carbon plate adalah sisipan kaku yang terbuat dari serat karbon, dirancang untuk memberikan dorongan dan efisiensi energi saat berlari, terutama pada sepatu performa tinggi.',
    // ... tambahkan lebih banyak definisi dari panduan.pdf
];

$pdf_context = '';
foreach ($pdf_keywords as $keyword => $definition) {
    if (stripos($user_message, $keyword) !== false) {
        $pdf_context .= "Dari panduan sepatu: " . $definition . "\n";
    }
}


$api_endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . $gemini_api_key;

// Siapkan pesan untuk Gemini API
// Gabungkan product_info dan pdf_context ke dalam prompt.
$full_message = $user_message;
if (!empty($product_info)) {
    $full_message = "Pertanyaan pengguna: " . $user_message . "\n" . $product_info;
}
if (!empty($pdf_context)) {
    $full_message = "Pertanyaan pengguna: " . $user_message . "\n" . $pdf_context;
}
if (!empty($product_info) && !empty($pdf_context)) {
    $full_message = "Pertanyaan pengguna: " . $user_message . "\n" . $product_info . "\n" . $pdf_context;
}


$payload_parts = [];
foreach ($history as $h) {
    $payload_parts[] = ['text' => $h['role'] . ': ' . $h['text']];
}
$payload_parts[] = ['text' => 'user: ' . $full_message];

$payload = [
    "contents" => [
        [
            "parts" => [
                ["text" => "Anda adalah AISARA 2.5, chatbot asisten penjualan untuk toko sepatu AksaraWalk. Berikan tanggapan yang ramah, informatif, dan membantu. Jika pengguna bertanya tentang produk, coba berikan informasi detail jika tersedia. Jika pengguna bertanya tentang istilah teknis sepatu, gunakan informasi dari panduan sepatu."]
            ]
        ]
    ]
];

foreach ($payload_parts as $part) {
    if (strpos($part['text'], 'user:') === 0) {
        $payload['contents'][] = ['role' => 'user', 'parts' => [['text' => substr($part['text'], 5)]]];
    } elseif (strpos($part['text'], 'model:') === 0) {
        $payload['contents'][] = ['role' => 'model', 'parts' => [['text' => substr($part['text'], 6)]]];
    }
}


$ch = curl_init($api_endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Kesalahan cURL: ' . $error]);
    exit();
}

if ($http_code !== 200) {
    http_response_code($http_code);
    echo json_encode(['error' => 'API Gemini mengembalikan kode status ' . $http_code . ': ' . $response]);
    exit();
}

$decoded_response = json_decode($response, true);

$ai_response = $decoded_response['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak dapat memproses permintaan Anda saat ini.';

echo json_encode(['response' => $ai_response]);

$conn->close();
?>