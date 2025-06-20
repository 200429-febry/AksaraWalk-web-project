<?php
// public/get_stats.php

require_once 'db_connect.php'; // Include the database connection file

header('Content-Type: application/json');

$stats = [
    'num_customers' => 0,
    'total_products_sold' => 0,
    'average_rating' => 0,
    'num_visits' => 0,
    'total_products_available' => 0,
    'pending_orders_count' => 0,
    'recent_registrations' => [],
    'sales_by_month' => [], // New: Penjualan per bulan
    'top_selling_products' => [], // New: Produk terlaris
    'most_active_customers' => [] // New: Pelanggan paling aktif
];

// 1. Get Number of Customers
$sql_customers = "SELECT COUNT(id) AS count FROM pelanggan";
$result_customers = $conn->query($sql_customers);
if ($result_customers && $row = $result_customers->fetch_assoc()) {
    $stats['num_customers'] = $row['count'];
}

// 2. Get Total Products Sold (from order_items table)
$sql_products_sold = "SELECT SUM(quantity) AS total_sold FROM order_items";
$result_products_sold = $conn->query($sql_products_sold);
if ($result_products_sold && $row = $result_products_sold->fetch_assoc()) {
    $stats['total_products_sold'] = $row['total_sold'] ?? 0;
}

// 3. Get Average Rating (from product_reviews table)
$sql_avg_rating = "SELECT AVG(rating) AS avg_rating FROM product_reviews";
$result_avg_rating = $conn->query($sql_avg_rating);
if ($result_avg_rating && $row = $result_avg_rating->fetch_assoc()) {
    $stats['average_rating'] = round($row['avg_rating'] ?? 0, 2);
}

// 4. Get Number of Visits (from file-based counter)
$visits_file = 'visits_count.txt';
if (file_exists($visits_file)) {
    $stats['num_visits'] = (int)file_get_contents($visits_file);
} else {
    file_put_contents($visits_file, 0); // Initialize if file doesn't exist
    $stats['num_visits'] = 0;
}

// 5. Get Total Products Available (from products table)
$sql_total_products = "SELECT COUNT(id) AS count FROM products";
$result_total_products = $conn->query($sql_total_products);
if ($result_total_products && $row = $result_total_products->fetch_assoc()) {
    $stats['total_products_available'] = $row['count'];
}

// 6. Get Pending Orders Count (from orders table)
$sql_pending_orders = "SELECT COUNT(id) AS count FROM orders WHERE status = 'pending'";
$result_pending_orders = $conn->query($sql_pending_orders);
if ($result_pending_orders && $row = $result_pending_orders->fetch_assoc()) {
    $stats['pending_orders_count'] = $row['count'];
}

// 7. Get Recent Registrations (e.g., last 5)
$sql_recent_registrations = "SELECT nama, tanggal_registrasi FROM pelanggan ORDER BY tanggal_registrasi DESC LIMIT 5";
$result_recent_registrations = $conn->query($sql_recent_registrations);
if ($result_recent_registrations) {
    while ($row = $result_recent_registrations->fetch_assoc()) {
        $stats['recent_registrations'][] = [
            'name' => htmlspecialchars($row['nama']),
            'date' => date('d M Y, H:i', strtotime($row['tanggal_registrasi']))
        ];
    }
}

// --- New Advanced Stats ---

// 8. Sales by Month (last 12 months)
$sql_sales_by_month = "
    SELECT
        DATE_FORMAT(order_date, '%Y-%m') AS sales_month,
        SUM(total_amount) AS total_sales
    FROM orders
    WHERE order_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
    GROUP BY sales_month
    ORDER BY sales_month ASC";
$result_sales_by_month = $conn->query($sql_sales_by_month);
if ($result_sales_by_month) {
    while ($row = $result_sales_by_month->fetch_assoc()) {
        $stats['sales_by_month'][] = [
            'month' => $row['sales_month'],
            'sales' => (float)$row['total_sales']
        ];
    }
}

// 9. Top Selling Products (by quantity sold)
$sql_top_products = "
    SELECT
        p.name AS product_name,
        SUM(oi.quantity) AS total_quantity_sold
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    GROUP BY p.name
    ORDER BY total_quantity_sold DESC
    LIMIT 5"; // Top 5 products
$result_top_products = $conn->query($sql_top_products);
if ($result_top_products) {
    while ($row = $result_top_products->fetch_assoc()) {
        $stats['top_selling_products'][] = [
            'name' => htmlspecialchars($row['product_name']),
            'quantity_sold' => (int)$row['total_quantity_sold']
        ];
    }
}

// 10. Most Active Customers (by number of orders)
$sql_active_customers = "
    SELECT
        pl.nama AS customer_name,
        COUNT(o.id) AS total_orders
    FROM orders o
    JOIN pelanggan pl ON o.pelanggan_id = pl.id
    GROUP BY pl.nama
    ORDER BY total_orders DESC
    LIMIT 5"; // Top 5 customers
$result_active_customers = $conn->query($sql_active_customers);
if ($result_active_customers) {
    while ($row = $result_active_customers->fetch_assoc()) {
        $stats['most_active_customers'][] = [
            'name' => htmlspecialchars($row['customer_name']),
            'orders' => (int)$row['total_orders']
        ];
    }
}

echo json_encode($stats);

$conn->close();
?>