<?php
$pageTitle = 'Add Utility';
require_once 'config/auth.php';
require_once 'config/db.php';
requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $language = $_POST['language'] ?? '';
    $stock    = (int)($_POST['total_stock'] ?? 0);

    $allowed_langs = ['English', 'Marathi', 'Hindi', 'NA'];

    if (empty($name) || empty($language)) {
        $error = 'Please fill in all required fields.';
    } elseif (!in_array($language, $allowed_langs)) {
        $error = 'Invalid language selected.';
    } elseif ($stock < 0) {
        $error = 'Stock cannot be negative.';
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare("INSERT INTO utilities (name, language, total_stock) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $name, $language, $stock);
        if ($stmt->execute()) {
            $success = 'Utility item added successfully!';
            $_POST = [];
        } else {
            $error = 'Failed to add utility item. Please try again.';
        }
        $stmt->close();
        $conn->close();
    }
}

require_once 'includes/header.php';
?>

<div class="app-layout">
    <?php require_once 'includes/sidebar.php'; ?>
    <main class="main-content">
        <button class="btn btn-outline" id="sidebarToggle" style="display:none; margin-bottom:16px;"><i class="fas fa-bars"></i> Menu</button>

        <div style="display:flex; align-items:center; gap:16px; margin-bottom:28px;">
            <a href="utilities-inventory.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="page-header" style="margin-bottom:0;">
                <h1><i class="fas fa-plus-circle" style="color:var(--saffron); margin-right:10px;"></i> Add New Utility</h1>
                <p>Enter item details to add it to the utilities inventory.</p>
            </div>
        </div>

        <?php if ($error): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>

        <div class="card" style="max-width:540px;">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-boxes-stacked"></i> Utility Item Details</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Item Name <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="name" class="form-control"
                               placeholder="e.g. Vivekananda Diary 2025"
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <div class="form-group">
    <label class="form-label">Language</label>
    <select name="language" class="form-control" required>
        <option value="Marathi">Marathi</option>
        <option value="Hindi">Hindi</option>
        <option value="English">English</option>
        <option value="NA">NA</option>
    </select>
</div>
                        <div class="form-group">
                            <label class="form-label">Initial Stock</label>
                            <input type="number" name="total_stock" class="form-control"
                                   min="0" placeholder="0"
                                   value="<?= htmlspecialchars($_POST['total_stock'] ?? '0') ?>">
                        </div>
                    </div>

                    <div style="display:flex; gap:12px; margin-top:8px;">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Utility</button>
                        <a href="utilities-inventory.php" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php
$extraJS = <<<JS
<script>
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
