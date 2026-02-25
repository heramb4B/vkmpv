<?php
$pageTitle = 'Add Book';
require_once 'config/auth.php';
require_once 'config/db.php';
requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn     = trim($_POST['isbn'] ?? '');
    $title    = trim($_POST['title'] ?? '');
    $language = $_POST['language'] ?? '';
    $writer   = trim($_POST['writer'] ?? '');
    $date     = $_POST['date_published'] ?? '';
    $stock    = (int)($_POST['total_stock'] ?? 0);

    $allowed_langs = ['Marathi', 'Hindi', 'English'];

    if (empty($isbn) || empty($title) || empty($language) || empty($writer) || empty($date)) {
        $error = 'Please fill in all required fields.';
    } elseif (!in_array($language, $allowed_langs)) {
        $error = 'Invalid language selected.';
    } elseif ($stock < 0) {
        $error = 'Stock cannot be negative.';
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare("INSERT INTO books (isbn, title, language, writer, date_published, total_stock) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssi', $isbn, $title, $language, $writer, $date, $stock);
        if ($stmt->execute()) {
            $success = 'Book added successfully!';
            // Clear fields
            $_POST = [];
        } else {
            if ($conn->errno === 1062) {
                $error = 'A book with this ISBN already exists.';
            } else {
                $error = 'Failed to add book. Please try again.';
            }
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
            <a href="book-inventory.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="page-header" style="margin-bottom:0;">
                <h1><i class="fas fa-plus-circle" style="color:var(--saffron); margin-right:10px;"></i> Add New Book</h1>
                <p>Enter book details to add it to the inventory.</p>
            </div>
        </div>

        <?php if ($error): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>

        <div class="card" style="max-width:680px;">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-book"></i> Book Details</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <div class="form-group">
                            <label class="form-label">ISBN Number <span style="color:var(--danger)">*</span></label>
                            <input type="text" name="isbn" class="form-control"
                                   placeholder="978-81-XXXX-XXX-X"
                                   value="<?= htmlspecialchars($_POST['isbn'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Language <span style="color:var(--danger)">*</span></label>
                            <select name="language" class="form-control" required>
                                <option value="">-- Select Language --</option>
                                <option value="Marathi" <?= ($_POST['language']??'')==='Marathi'?'selected':'' ?>>Marathi</option>
                                <option value="Hindi" <?= ($_POST['language']??'')==='Hindi'?'selected':'' ?>>Hindi</option>
                                <option value="English" <?= ($_POST['language']??'')==='English'?'selected':'' ?>>English</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Title of Book <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="title" class="form-control"
                               placeholder="Enter book title"
                               value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Writer / Author <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="writer" class="form-control"
                               placeholder="e.g. Swami Vivekananda"
                               value="<?= htmlspecialchars($_POST['writer'] ?? '') ?>" required>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <div class="form-group">
                            <label class="form-label">Date Published <span style="color:var(--danger)">*</span></label>
                            <input type="date" name="date_published" class="form-control"
                                   value="<?= htmlspecialchars($_POST['date_published'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Initial Stock</label>
                            <input type="number" name="total_stock" class="form-control"
                                   min="0" placeholder="0"
                                   value="<?= htmlspecialchars($_POST['total_stock'] ?? '0') ?>">
                        </div>
                    </div>

                    <div style="display:flex; gap:12px; margin-top:8px;">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Book</button>
                        <a href="book-inventory.php" class="btn btn-outline">Cancel</a>
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
