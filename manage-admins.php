<?php
$pageTitle = 'Manage Admins';
require_once 'config/auth.php';
require_once 'config/db.php';
requireAdmin();

$conn = getDBConnection();
$success = '';
$error   = '';

// ── Handle delete ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_admin') {
    $id = (int)($_POST['admin_id'] ?? 0);
    // Fetch email first to prevent deleting protected admin
    $chk = $conn->prepare("SELECT email FROM users WHERE id = ? AND role = 'admin'");
    $chk->bind_param('i', $id);
    $chk->execute();
    $chk->store_result();
    $chk->bind_result($adminEmail);
    $chk->fetch();
    $chk->close();

    if (empty($adminEmail)) {
        $error = 'Admin account not found.';
    } elseif ($adminEmail === 'admin@gmail.com') {
        $error = 'The primary administrator account cannot be deleted.';
    } else {
        $del = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'admin'");
        $del->bind_param('i', $id);
        $del->execute();
        $del->close();
        $success = 'Admin account deleted successfully.';
    }
}

// ── Handle create admin ─────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_admin') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $pass    = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($pass) || empty($confirm)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($pass) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($pass !== $confirm) {
        $error = 'Password and Confirm Password do not match.';
    } else {
        // Check email uniqueness
        $chk = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $chk->bind_param('s', $email);
        $chk->execute();
        $chk->store_result();
        if ($chk->num_rows > 0) {
            $error = 'An account with this email already exists.';
            $chk->close();
        } else {
            $chk->close();
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
            $stmt->bind_param('sss', $name, $email, $hash);
            if ($stmt->execute()) {
                $success = "Admin account for <strong>" . htmlspecialchars($name) . "</strong> created successfully.";
                $_POST = [];
            } else {
                $error = 'Failed to create admin account. Please try again.';
            }
            $stmt->close();
        }
    }
}

// ── Fetch all admins ────────────────────────────────────────────────────
$admins = $conn->query("SELECT id, name, email, created_at FROM users WHERE role = 'admin' ORDER BY id")->fetch_all(MYSQLI_ASSOC);
$conn->close();

require_once 'includes/header.php';
?>

<div class="app-layout">
    <?php require_once 'includes/sidebar.php'; ?>
    <main class="main-content">
        <button class="btn btn-outline" id="sidebarToggle" style="display:none; margin-bottom:16px;">
            <i class="fas fa-bars"></i> Menu
        </button>

        <div class="page-header">
            <h1><i class="fas fa-user-shield" style="color:var(--saffron); margin-right:10px;"></i> Manage Admins</h1>
            <p>Create new administrator accounts and manage existing ones.</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div style="display:grid; grid-template-columns:1fr 1.4fr; gap:28px; align-items:start;">

            <!-- ── Create Admin Form ─────────────────────────────────── -->
            <div class="card" style="position:sticky; top:calc(var(--navbar-h) + 20px);">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-plus"></i> Create New Admin</h3>
                </div>
                <div class="card-body">
                    <form method="POST" novalidate autocomplete="off">
                        <input type="hidden" name="action" value="create_admin">

                        <div class="form-group">
                            <label class="form-label">Full Name <span style="color:var(--danger)">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="Suresh Kulkarni"
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address <span style="color:var(--danger)">*</span></label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="newadmin@gmail.com"
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required
                                   autocomplete="new-email">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password <span style="color:var(--danger)">*</span></label>
                            <div class="input-group">
                                <input type="password" id="adminPass" name="password" class="form-control"
                                       placeholder="Min. 6 characters" required autocomplete="new-password">
                                <i class="fas fa-eye input-icon toggle-password" data-target="adminPass"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm Password <span style="color:var(--danger)">*</span></label>
                            <div class="input-group">
                                <input type="password" id="adminPassConfirm" name="confirm_password" class="form-control"
                                       placeholder="Repeat password" required>
                                <i class="fas fa-eye input-icon toggle-password" data-target="adminPassConfirm"></i>
                            </div>
                        </div>

                        <div style="background:var(--saffron-pale); border-radius:var(--radius); padding:12px 14px; margin-bottom:20px; font-size:13px; color:var(--text-mid);">
                            <i class="fas fa-shield-alt" style="color:var(--saffron); margin-right:6px;"></i>
                            New account will automatically be assigned the <strong>Admin</strong> role.
                        </div>

                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                            <i class="fas fa-user-plus"></i> Create Admin Account
                        </button>
                    </form>
                </div>
            </div>

            <!-- ── Existing Admins ───────────────────────────────────── -->
            <div>
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
                    <h2 style="font-family:'Cinzel',serif; font-size:18px; color:var(--maroon);">
                        Existing Admin Accounts
                    </h2>
                    <span style="background:var(--maroon-deep); color:var(--gold-light); border-radius:20px; padding:4px 14px; font-size:12px; font-weight:700;">
                        <?= count($admins) ?> Total
                    </span>
                </div>

                <!-- Table view -->
                <div class="card">
                    <div class="card-body" style="padding:0;">
                        <div class="table-wrapper">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Created On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($admins as $i => $admin): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td>
                                            <div style="display:flex; align-items:center; gap:10px;">
                                                <div class="admin-avatar" style="width:36px; height:36px; font-size:13px; flex-shrink:0;">
                                                    <?= strtoupper(mb_substr($admin['name'], 0, 1)) ?>
                                                </div>
                                                <strong><?= htmlspecialchars($admin['name']) ?></strong>
                                            </div>
                                        </td>
                                        <td style="font-size:13px;">
                                            <?= htmlspecialchars($admin['email']) ?>
                                            <?php if ($admin['email'] === 'admin@gmail.com'): ?>
                                                <span style="display:inline-block; background:var(--saffron-pale); color:var(--saffron); border-radius:12px; padding:1px 8px; font-size:10px; font-weight:700; margin-left:4px;">
                                                    PROTECTED
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="font-size:12px; color:var(--text-light);">
                                            <?= date('d M Y', strtotime($admin['created_at'])) ?>
                                        </td>
                                        <td>
                                            <?php if ($admin['email'] === 'admin@gmail.com'): ?>
                                                <span style="font-size:12px; color:var(--text-light);">
                                                    <i class="fas fa-lock"></i> Cannot delete
                                                </span>
                                            <?php else: ?>
                                                <form method="POST" onsubmit="return confirm('Delete this admin account? This action cannot be undone.')">
                                                    <input type="hidden" name="action" value="delete_admin">
                                                    <input type="hidden" name="admin_id" value="<?= $admin['id'] ?>">
                                                    <button type="submit" class="btn btn-danger" style="font-size:12px; padding:6px 14px;">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Card grid view (visual) -->
                <div class="admin-grid">
                    <?php foreach ($admins as $admin): ?>
                    <div class="admin-card">
                        <div class="admin-avatar"><?= strtoupper(mb_substr($admin['name'], 0, 1)) ?></div>
                        <div class="admin-info">
                            <div class="admin-name"><?= htmlspecialchars($admin['name']) ?></div>
                            <div class="admin-email"><?= htmlspecialchars($admin['email']) ?></div>
                            <div class="admin-since">
                                <i class="fas fa-calendar-alt" style="color:var(--saffron); font-size:10px;"></i>
                                Admin since <?= date('d M Y', strtotime($admin['created_at'])) ?>
                            </div>
                        </div>
                        <?php if ($admin['email'] === 'admin@gmail.com'): ?>
                        <span class="admin-protected"><i class="fas fa-lock"></i> Protected</span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
$extraJS = <<<'JS'
<script>
const sidebarToggle = document.getElementById('sidebarToggle');
if (sidebarToggle) {
    function checkWidth() {
        sidebarToggle.style.display = window.innerWidth <= 768 ? 'inline-flex' : 'none';
    }
    checkWidth();
    window.addEventListener('resize', checkWidth);
}

// Client-side password match validation
const form = document.querySelector('form[method="POST"]');
const passField = document.getElementById('adminPass');
const confirmField = document.getElementById('adminPassConfirm');

if (form && passField && confirmField) {
    form.addEventListener('submit', function(e) {
        if (passField.value !== confirmField.value) {
            e.preventDefault();
            confirmField.style.borderColor = 'var(--danger)';
            confirmField.setCustomValidity('Passwords do not match.');
            confirmField.reportValidity();
        } else {
            confirmField.style.borderColor = '';
            confirmField.setCustomValidity('');
        }
    });
    confirmField.addEventListener('input', function() {
        if (this.value && passField.value !== this.value) {
            this.style.borderColor = 'var(--danger)';
        } else {
            this.style.borderColor = '';
        }
    });
}
</script>
JS;
?>

<?php require_once 'includes/footer.php'; ?>
