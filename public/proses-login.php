<?php
// public/proses-login.php

session_start(); // Start session for user management
require_once 'db_connect.php'; // Include database connection

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = "Email dan password harus diisi.";
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT id, nama, password, role FROM pelanggan WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    // Verify hashed password
    if (password_verify($password, $user['password'])) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nama'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['loggedin'] = true;

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: dashboard.php"); // Redirect admin to dashboard
        } else {
            header("Location: index.php?status=login_sukses"); // Redirect regular user to home
        }
        exit();
    } else {
        // Password incorrect
        $_SESSION['login_error'] = "Email atau password salah.";
        header("Location: login.php");
        exit();
    }
} else {
    // User not found
    $_SESSION['login_error'] = "Email atau password salah.";
    header("Location: login.php");
    exit();
}

$stmt->close();
$conn->close();
?>