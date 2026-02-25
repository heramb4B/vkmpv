<?php
$pageTitle = 'Utilities Inventory';
require_once 'config/auth.php';
require_once 'config/db.php';
requireLogin();

$conn = getDBConnection();

$success = '';
$error = '';

// Handle POST actions (admin only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAdmin()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_stock' || $action === 'reduce_stock') {
        $itemId = (int)($_POST['item_id'] ?? 0);
        $qty    = (int)($_POST['quantity'] ?? 0);
        if ($qty <= 0) { $error = 'Quantity must be positive.'; }
        else {
            if ($action === 'add_stock') {
                $conn->query("UPDATE utilities SET total_stock = total_stock + $qty WHERE id = $itemId");
            } else {
                $cur = $conn->query("SELECT total_stock FROM utilities WHERE id=$itemId")->fetch_assoc()['total_stock'];
                if ($qty > $cur) { $error = 'Cannot reduce more than available stock ('.$cur.').'; }
                else { $conn->query("UPDATE utilities SET total_stock = total_stock - $qty WHERE id = $itemId"); }
            }
            if (!$error) {
                $logAction = ($action === 'add_stock') ? 'add' : 'reduce';
                $userId = $_SESSION['user_id'];
                $stmt = $conn->prepare("INSERT INTO stock_log (item_type,item_id,action,quantity,performed_by) VALUES ('utility',?,?,?,?)");
                $stmt->bind_param('isis', $itemId, $logAction, $qty, $userId);
                $stmt->execute();
                $stmt->close();
                $success = 'Stock updated successfully.';
            }
        }
    }

    if ($action === 'delete_utility') {
        $itemId = (int)($_POST['item_id'] ?? 0);
        $conn->query("DELETE FROM utilities WHERE id=$itemId");
        $success = 'Utility item deleted successfully.';
    }
}

// Fetch utilities
$langFilter = $_GET['lang'] ?? 'all';
$query = "SELECT * FROM utilities";

// Update the allowed values to include 'NA'
if (in_array($langFilter, ['Marathi', 'Hindi', 'English', 'NA'])) {
    $escaped = $conn->real_escape_string($langFilter);
    $query .= " WHERE language='$escaped'";
}
$query .= " ORDER BY id";
$utilities = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

$conn->close();

require_once 'includes/header.php';
?>

<div class="app-layout">
<?php if (isAdmin()): ?>
    <?php require_once 'includes/sidebar.php'; ?>
<?php endif; ?>
    <main class="main-content">
        <?php if (isAdmin()): ?>
        <button class="btn btn-outline" id="sidebarToggle" style="display:none; margin-bottom:16px;"><i class="fas fa-bars"></i> Menu</button>
        <?php else: ?>
        <nav class="user-nav" style="margin-bottom:20px;">
            <a href="dashboard.php" class="user-nav-link"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <a href="book-inventory.php" class="user-nav-link"><i class="fas fa-book"></i> Book Inventory</a>
            <a href="utilities-inventory.php" class="user-nav-link active"><i class="fas fa-boxes-stacked"></i> Utilities Inventory</a>
            <a href="logout.php" class="user-nav-link" style="margin-left:auto; color:var(--danger);"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
        <?php endif; ?>

        <div class="page-header">
            <h1><i class="fas fa-boxes-stacked" style="color:var(--saffron); margin-right:10px;"></i> Utilities Inventory</h1>
            <p>Manage diaries, calendars, posters and other utility items.</p>
        </div>

        <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list"></i> All Utilities</h3>
                <div class="toolbar" style="margin:0;">
                    <div class="toolbar-left">
    <label style="font-size:13px; color:var(--text-light); font-weight:600;">Filter by Language:</label>
    <select id="langFilter" class="form-control" style="width:auto; padding:7px 14px;">
    <option value="all">All</option>
    <option value="Marathi" <?= $langFilter==='Marathi'?'selected':'' ?>>Marathi</option>
    <option value="Hindi" <?= $langFilter==='Hindi'?'selected':'' ?>>Hindi</option>
    <option value="English" <?= $langFilter==='English'?'selected':'' ?>>English</option>
    <option value="NA" <?= $langFilter==='NA'?'selected':'' ?>>N/A</option>
</select>

    <div class="search-box" style="position:relative; margin-left:15px;">
        <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-light);"></i>
        <input type="text" id="searchInput" class="form-control" placeholder="Search utility name..." style="padding-left:35px; width:250px;">
    </div>
</div>
                    <?php if (isAdmin()): ?>
                    <div class="toolbar-right">
                        <a href="add-utility.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Utility</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body" style="padding:0;">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Language</th>
                                <th>Total Stock</th>
                                
                                <?php if (isAdmin()): ?><th>Actions</th><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($utilities)): ?>
                            <tr><td colspan="<?= isAdmin()?6:5 ?>" style="text-align:center; padding:40px; color:var(--text-light);">No utility items found.</td></tr>
                            <?php else: ?>
                            <?php foreach ($utilities as $i => $item): ?>
                            <tr data-lang="<?= $item['language'] ?>">
                                <td><?= $i + 1 ?></td>
                                <td><code style="font-size:12px; color:var(--maroon);">#<?= $item['id'] ?></code></td>
                                <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                                <td>
                                    <span class="badge badge-<?= strtolower($item['language']) ?>">
                                        <?= $item['language'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="stock-cell <?= $item['total_stock'] < 50 ? 'low' : ($item['total_stock'] < 150 ? 'mid' : 'ok') ?>">
                                        <?= number_format($item['total_stock']) ?>
                                    </span>
                                </td>
                                <?php if (isAdmin()): ?>
                                <td>
                                    <div class="actions-cell">
                                        <button class="btn btn-success"
                                            data-modal="stockModal"
                                            data-id="<?= $item['id'] ?>"
                                            data-name="<?= htmlspecialchars($item['name']) ?>"
                                            data-action="add_stock">
                                            <i class="fas fa-plus"></i> Add
                                        </button>
                                        <button class="btn btn-warning"
                                            data-modal="stockModal"
                                            data-id="<?= $item['id'] ?>"
                                            data-name="<?= htmlspecialchars($item['name']) ?>"
                                            data-action="reduce_stock">
                                            <i class="fas fa-minus"></i> Reduce
                                        </button>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this utility item?')">
                                            <input type="hidden" name="action" value="delete_utility">
                                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                        </form>
                                    </div>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- STOCK MODAL -->
<?php if (isAdmin()): ?>
<div class="modal-overlay" id="stockModal">
    <div class="modal-box">
        <h3 class="modal-title"><span class="modal-action-label">Update</span> Stock</h3>
        <p style="font-size:14px; color:var(--text-light); margin-bottom:20px;">
            Item: <strong class="modal-item-name"></strong>
        </p>
        <form method="POST">
            <input type="hidden" name="action" value="">
            <input type="hidden" name="item_id" value="">
            <div class="form-group">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" min="1" value="1" required>
            </div>
            <div style="display:flex; gap:12px; justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('stockModal').classList.remove('show')">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<?php
$extraJS = <<<JS
<script>
const searchInput = document.getElementById('searchInput');
const langFilter = document.getElementById('langFilter');
const tableRows = document.querySelectorAll('.data-table tbody tr');

function filterTable() {
    const searchTerm = searchInput.value.toLowerCase();
    const selectedLang = langFilter.value.toLowerCase();

    tableRows.forEach(row => {
        if (row.cells.length <= 1) return;

        const text = row.textContent.toLowerCase();
        const rowLang = row.getAttribute('data-lang').toLowerCase();
        
        const matchesSearch = text.includes(searchTerm);
        const matchesLang = selectedLang === 'all' || rowLang === selectedLang;

        row.style.display = (matchesSearch && matchesLang) ? '' : 'none';
    });
}

searchInput.addEventListener('input', filterTable);
langFilter.addEventListener('change', filterTable);

// Sidebar toggle logic...
const sidebarToggle = document.getElementById('sidebarToggle');
if (sidebarToggle) {
    function checkWidth() { sidebarToggle.style.display = window.innerWidth <= 768 ? 'inline-flex' : 'none'; }
    checkWidth();
    window.addEventListener('resize', checkWidth);
}
</script>
JS;
?>

<?php require_once 'includes/footer.php'; ?>
