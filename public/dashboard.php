<?php
// public/dashboard.php
require_once 'session_check.php';
requireAdmin(); // Ensures only logged-in admins can access this page

// Memanggil header
include 'header.php';

// --- Koneksi ke Database ---
require_once 'db_connect.php';

// Initial fetch of some data (more can be fetched via AJAX in JS)
// --- Fetch Dashboard Stats (can be done via AJAX for dynamic updates) ---
$dashboard_stats = [
    'num_customers' => 'N/A',
    'total_products_sold' => 'N/A',
    'average_rating' => 'N/A',
    'num_visits' => 'N/A',
    'total_products_available' => 'N/A',
    'pending_orders_count' => 'N/A',
    'recent_registrations' => []
];

// Fetch stats directly for initial load (or you can fully rely on AJAX)
$sql_customers = "SELECT COUNT(id) AS count FROM pelanggan";
$result = $conn->query($sql_customers);
if ($result && $row = $result->fetch_assoc()) {
    $dashboard_stats['num_customers'] = $row['count'];
}

$sql_products_sold = "SELECT SUM(quantity) AS total_sold FROM order_items";
$result = $conn->query($sql_products_sold);
if ($result && $row = $result->fetch_assoc()) {
    $dashboard_stats['total_products_sold'] = $row['total_sold'] ?? 0;
}

$sql_avg_rating = "SELECT AVG(rating) AS avg_rating FROM product_reviews";
$result = $conn->query($sql_avg_rating);
if ($result && $row = $result->fetch_assoc()) {
    $dashboard_stats['average_rating'] = round($row['avg_rating'] ?? 0, 2);
}

$visits_file = 'visits_count.txt';
if (file_exists($visits_file)) {
    $dashboard_stats['num_visits'] = (int)file_get_contents($visits_file);
}

$sql_total_products = "SELECT COUNT(id) AS count FROM products";
$result = $conn->query($sql_total_products);
if ($result && $row = $result->fetch_assoc()) {
    $dashboard_stats['total_products_available'] = $row['count'];
}

$sql_pending_orders = "SELECT COUNT(id) AS count FROM orders WHERE status = 'pending'";
$result = $conn->query($sql_pending_orders);
if ($result && $row = $result->fetch_assoc()) {
    $dashboard_stats['pending_orders_count'] = $row['count'];
}

$sql_recent_registrations = "SELECT nama, tanggal_registrasi FROM pelanggan ORDER BY tanggal_registrasi DESC LIMIT 5";
$result = $conn->query($sql_recent_registrations);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $dashboard_stats['recent_registrations'][] = [
            'name' => htmlspecialchars($row['nama']),
            'date' => date('d M Y, H:i', strtotime($row['tanggal_registrasi']))
        ];
    }
}


// --- Mengambil Data Pelanggan dengan role ---
$sql_pelanggan = "SELECT id, nama, email, telepon, tanggal_registrasi, role FROM pelanggan ORDER BY tanggal_registrasi DESC";
$result_pelanggan = $conn->query($sql_pelanggan);

?>

<main class="container" style="padding-top: 120px; padding-bottom: 60px; color: white;">

    <div class="section-heading">
        <div class="heading">
            <h2 class="heading-two">Admin <span>Dashboard</span></h2>
            <p class="sub-heading">Overview & Customer Data</p>
        </div>
    </div>

    <div class="dashboard-overview" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div class="stat-card" style="background-color: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; text-align: center;">
            <h3>Total Pelanggan</h3>
            <p style="font-size: 2em; font-weight: bold;"><?= $dashboard_stats['num_customers'] ?></p>
        </div>
        <div class="stat-card" style="background-color: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; text-align: center;">
            <h3>Produk Terjual</h3>
            <p style="font-size: 2em; font-weight: bold;"><?= $dashboard_stats['total_products_sold'] ?></p>
        </div>
        <div class="stat-card" style="background-color: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; text-align: center;">
            <h3>Rata-rata Rating</h3>
            <p style="font-size: 2em; font-weight: bold;"><?= $dashboard_stats['average_rating'] ?> / 5</p>
        </div>
        <div class="stat-card" style="background-color: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; text-align: center;">
            <h3>Total Kunjungan Situs</h3>
            <p style="font-size: 2em; font-weight: bold;"><?= $dashboard_stats['num_visits'] ?></p>
        </div>
        <div class="stat-card" style="background-color: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; text-align: center;">
            <h3>Produk Tersedia</h3>
            <p style="font-size: 2em; font-weight: bold;"><?= $dashboard_stats['total_products_available'] ?></p>
        </div>
        <div class="stat-card" style="background-color: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; text-align: center;">
            <h3>Pesanan Pending</h3>
            <p style="font-size: 2em; font-weight: bold;"><?= $dashboard_stats['pending_orders_count'] ?></p>
        </div>
    </div>

    <div style="margin-bottom: 40px; width: 100%; max-width: 800px; background-color: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px;">
        <h3 style="color: #c32bfb; margin-bottom: 15px; text-align: center;">Registrasi Terbaru</h3>
        <ul style="list-style: none; padding: 0;">
            <?php if (!empty($dashboard_stats['recent_registrations'])): ?>
                <?php foreach ($dashboard_stats['recent_registrations'] as $reg): ?>
                    <li style="padding: 8px 0; border-bottom: 1px dashed rgba(255,255,255,0.1); display: flex; justify-content: space-between;">
                        <span><?= $reg['name'] ?></span>
                        <span style="font-size: 0.9em; opacity: 0.8;"><?= $reg['date'] ?></span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li style="text-align: center; padding: 10px;">Belum ada registrasi baru.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="advanced-analytics" style="margin-bottom: 40px; display: grid; grid-template-columns: 1fr; gap: 30px;">
        <div style="background-color: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px;">
            <h3 style="color: #c32bfb; margin-bottom: 15px; text-align: center;">Penjualan Bulanan (12 Bulan Terakhir)</h3>
            <canvas id="salesChart"></canvas>
        </div>
        <div style="background-color: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px;">
            <h3 style="color: #c32bfb; margin-bottom: 15px; text-align: center;">5 Produk Terlaris</h3>
            <canvas id="topProductsChart"></canvas>
        </div>
        <div style="background-color: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px;">
            <h3 style="color: #c32bfb; margin-bottom: 15px; text-align: center;">5 Pelanggan Paling Aktif (Jumlah Pesanan)</h3>
            <canvas id="activeCustomersChart"></canvas>
        </div>
    </div>


    <div class="section-heading" style="width: 100%;">
        <div class="heading">
            <h2 class="heading-two">Daftar <span>Pelanggan</span></h2>
            <p class="sub-heading">Detail semua pelanggan terdaftar</p>
        </div>
    </div>

    <div style="overflow-x:auto; width: 100%;">
        <table style="width:100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: rgba(255,255,255,0.1);">
                    <th style="padding: 12px;">ID</th>
                    <th style="padding: 12px;">Nama</th>
                    <th style="padding: 12px;">Email</th>
                    <th style="padding: 12px;">Telepon</th>
                    <th style="padding: 12px;">Tanggal Registrasi</th>
                    <th style="padding: 12px;">Role</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_pelanggan->num_rows > 0) {
                    while($row = $result_pelanggan->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td style='padding: 12px; border-top: 1px solid #444;'>" . htmlspecialchars($row["id"]) . "</td>";
                        echo "<td style='padding: 12px; border-top: 1px solid #444;'>" . htmlspecialchars($row["nama"]) . "</td>";
                        echo "<td style='padding: 12px; border-top: 1px solid #444;'>" . htmlspecialchars($row["email"]) . "</td>";
                        echo "<td style='padding: 12px; border-top: 1px solid #444;'>" . htmlspecialchars($row["telepon"]) . "</td>";
                        echo "<td style='padding: 12px; border-top: 1px solid #444;'>" . htmlspecialchars(date('d M Y, H:i', strtotime($row["tanggal_registrasi"]))) . "</td>";
                        echo "<td style='padding: 12px; border-top: 1px solid #444;'>" . htmlspecialchars($row["role"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='padding: 12px; text-align: center;'>Belum ada data pelanggan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to fetch and render advanced stats
        async function fetchAndRenderAdvancedStats() {
            try {
                const response = await fetch('get_stats.php');
                const stats = await response.json();

                // 1. Sales by Month Chart
                const salesCtx = document.getElementById('salesChart').getContext('2d');
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: stats.sales_by_month.map(item => item.month),
                        datasets: [{
                            label: 'Total Penjualan (IDR)',
                            data: stats.sales_by_month.map(item => item.sales),
                            borderColor: 'rgba(195, 43, 251, 1)', // #c32bfb
                            backgroundColor: 'rgba(195, 43, 251, 0.2)',
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: 'rgba(255,255,255,0.7)',
                                    callback: function(value, index, values) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    color: 'rgba(255,255,255,0.7)'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: 'white'
                                }
                            }
                        }
                    }
                });

                // 2. Top Selling Products Chart
                const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
                new Chart(topProductsCtx, {
                    type: 'bar',
                    data: {
                        labels: stats.top_selling_products.map(item => item.name),
                        datasets: [{
                            label: 'Jumlah Terjual',
                            data: stats.top_selling_products.map(item => item.quantity_sold),
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        indexAxis: 'y', // Make it a horizontal bar chart
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    color: 'rgba(255,255,255,0.7)'
                                }
                            },
                            y: {
                                ticks: {
                                    color: 'rgba(255,255,255,0.7)'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: 'white'
                                }
                            }
                        }
                    }
                });

                // 3. Most Active Customers Chart
                const activeCustomersCtx = document.getElementById('activeCustomersChart').getContext('2d');
                new Chart(activeCustomersCtx, {
                    type: 'doughnut', // or 'pie'
                    data: {
                        labels: stats.most_active_customers.map(item => item.name),
                        datasets: [{
                            label: 'Jumlah Pesanan',
                            data: stats.most_active_customers.map(item => item.orders),
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: 'white'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Pelanggan Paling Aktif',
                                color: 'white'
                            }
                        }
                    }
                });


            } catch (error) {
                console.error('Error fetching advanced stats:', error);
            }
        }

        fetchAndRenderAdvancedStats(); // Call function on page load
    });
</script>

<?php
// Menutup koneksi
$conn->close();
// Memanggil footer
include 'footer.php';
?>