<?php
$pageTitle = 'Dashboard';
require_once 'config/auth.php';
require_once 'config/db.php';
requireLogin();

$conn = getDBConnection();

// Stats
$bookStock = $conn->query("SELECT COALESCE(SUM(total_stock),0) as total FROM books")->fetch_assoc()['total'];
$utilStock = $conn->query("SELECT COALESCE(SUM(total_stock),0) as total FROM utilities")->fetch_assoc()['total'];
$totalBooks = $conn->query("SELECT COUNT(*) as c FROM books")->fetch_assoc()['c'];
$totalUtils = $conn->query("SELECT COUNT(*) as c FROM utilities")->fetch_assoc()['c'];

// Monthly sales
$salesData = $conn->query("SELECT month, year, book_sales, utility_sales FROM monthly_sales ORDER BY year, FIELD(month,'January','February','March','April','May','June','July','August','September','October','November','December') LIMIT 6")->fetch_all(MYSQLI_ASSOC);

$conn->close();

$bodyClass = isAdmin() ? 'has-sidebar' : '';
require_once 'includes/header.php';
?>

<?php if (isAdmin()): ?>
<div class="app-layout">
    <?php require_once 'includes/sidebar.php'; ?>
    <main class="main-content">
        <button class="btn btn-outline" id="sidebarToggle" style="display:none; margin-bottom:16px;"><i class="fas fa-bars"></i> Menu</button>
<?php else: ?>
<div class="user-layout">
    <nav class="user-nav">
        <a href="dashboard.php" class="user-nav-link active"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="book-inventory.php" class="user-nav-link"><i class="fas fa-book"></i> Book Inventory</a>
        <a href="utilities-inventory.php" class="user-nav-link"><i class="fas fa-boxes-stacked"></i> Utilities Inventory</a>
        <a href="logout.php" class="user-nav-link" style="margin-left:auto; color:var(--danger);"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
    <main>
<?php endif; ?>

        <div class="page-header">
            <h1><i class="fas fa-chart-pie" style="color:var(--saffron); margin-right:10px;"></i> Dashboard</h1>
            <p>Welcome back, <strong><?= htmlspecialchars($_SESSION['name']) ?></strong> — here's your inventory overview.</p>
        </div>

        <!-- STAT CARDS -->
        <div class="stats-grid">
            <div class="stat-card orange">
                <div class="stat-icon orange"><i class="fas fa-book"></i></div>
                <div class="stat-value"><?= number_format($bookStock) ?></div>
                <div class="stat-label">Total Book Stock Available</div>
            </div>
            <div class="stat-card maroon">
                <div class="stat-icon maroon"><i class="fas fa-boxes-stacked"></i></div>
                <div class="stat-value"><?= number_format($utilStock) ?></div>
                <div class="stat-label">Total Utility Stock Available</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon green"><i class="fas fa-chart-line"></i></div>
                <div class="stat-value"><?= $totalBooks + $totalUtils ?></div>
                <div class="stat-label">Total Item Types in System</div>
            </div>
        </div>

        <!-- CHARTS -->
        <!-- <div class="charts-grid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Monthly Sales Overview</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Stock Distribution</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- RECENT SUMMARY TABLE -->
        <!-- <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-table"></i> Monthly Sales Summary</h3>
            </div>
            <div class="card-body" style="padding:0;">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Book Sales</th>
                                <th>Utility Sales</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_reverse($salesData) as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['month']) ?></td>
                                <td><?= $row['year'] ?></td>
                                <td><?= number_format($row['book_sales']) ?></td>
                                <td><?= number_format($row['utility_sales']) ?></td>
                                <td><strong><?= number_format($row['book_sales'] + $row['utility_sales']) ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> -->

<?php if (isAdmin()): ?>
    </main>
</div>
<?php else: ?>
    </main>
</div>
<?php endif; ?>

<?php
$labels = json_encode(array_column($salesData, 'month'));
$bookSalesJson = json_encode(array_column($salesData, 'book_sales'));
$utilSalesJson = json_encode(array_column($salesData, 'utility_sales'));
$bookStockInt = (int)$bookStock;
$utilStockInt = (int)$utilStock;

$extraJS = <<<JS
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'bar',
    data: {
        labels: $labels,
        datasets: [
            {
                label: 'Book Sales',
                data: $bookSalesJson,
                backgroundColor: 'rgba(232,98,26,0.75)',
                borderColor: '#E8621A',
                borderWidth: 2,
                borderRadius: 6
            },
            {
                label: 'Utility Sales',
                data: $utilSalesJson,
                backgroundColor: 'rgba(107,29,42,0.65)',
                borderColor: '#6B1D2A',
                borderWidth: 2,
                borderRadius: 6
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
        }
    }
});

const stockCtx = document.getElementById('stockChart').getContext('2d');
new Chart(stockCtx, {
    type: 'doughnut',
    data: {
        labels: ['Book Stock', 'Utility Stock'],
        datasets: [{
            data: [$bookStockInt, $utilStockInt],
            backgroundColor: ['#E8621A', '#6B1D2A'],
            borderWidth: 0,
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } },
        cutout: '65%'
    }
});

const sidebarToggle = document.getElementById('sidebarToggle');
if (sidebarToggle) {
    function checkWidth() {
        sidebarToggle.style.display = window.innerWidth <= 768 ? 'inline-flex' : 'none';
    }
    checkWidth();
    window.addEventListener('resize', checkWidth);
}
</script>
JS;
?>

<?php require_once 'includes/footer.php'; ?>
