<?php
// public/session_check.php

session_start();

function isLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['user_role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        if (isLoggedIn()) {
            // Logged in but not admin
            header("Location: index.php?error=" . urlencode("Akses ditolak. Anda tidak memiliki izin admin."));
        } else {
            // Not logged in at all
            header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
        }
        exit();
    }
}
?>