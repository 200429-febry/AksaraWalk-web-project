<?php
// public/proses-registrasi.php

require_once 'db_connect.php'; // Sertakan file koneksi database

// Start session to store messages if needed (though redirects are used here)
session_start();

// --- Ambil data dari formulir ---
$nama = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$telepon = $_POST['phone'] ?? '';

// Validasi input
if (empty($nama) || empty($email) || empty($password) || empty($confirm_password) || empty($telepon)) {
    header("Location: register.php?status=gagal&error=" . urlencode("Semua field harus diisi."));
    exit();
}

if ($password !== $confirm_password) {
    header("Location: register.php?status=gagal&error=" . urlencode("Konfirmasi password tidak cocok."));
    exit();
}

// Hash password sebelum disimpan
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = 'user'; // Default role for new registrations

// --- Gunakan Prepared Statements untuk Keamanan (Mencegah SQL Injection) ---
// Periksa apakah email sudah terdaftar
$stmt_check = $conn->prepare("SELECT id FROM pelanggan WHERE email = ?");
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $stmt_check->close();
    $conn->close();
    header("Location: register.php?status=gagal&error=" . urlencode("Email sudah terdaftar. Silakan gunakan email lain."));
    exit();
}
$stmt_check->close();

// Masukkan data pengguna baru
// Note: 'tanggal_registrasi' column should have a DEFAULT CURRENT_TIMESTAMP in your DB schema for 'pelanggan' table.
// If not, you'd need to add NOW() to the insert statement.
$stmt_insert = $conn->prepare("INSERT INTO pelanggan (nama, email, password, telepon, role) VALUES (?, ?, ?, ?, ?)");
$stmt_insert->bind_param("sssss", $nama, $email, $hashed_password, $telepon, $role);

if ($stmt_insert->execute()) {
    header("Location: register.php?status=sukses");
    exit();
} else {
    header("Location: register.php?status=gagal&error=" . urlencode("Terjadi kesalahan saat registrasi: " . $stmt_insert->error));
    exit();
}

$stmt_insert->close();
$conn->close();
?>