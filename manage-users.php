<?php
$pageTitle = 'Manage Users';
require_once 'config/auth.php';
require_once 'config/db.php';
requireAdmin();

$conn = getDBConnection();
$success = '';
$error = '';

// 1. Handle Add User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_user') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $error = "Email already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $name, $email, $pass);
        if ($stmt->execute()) $success = "User added successfully!";
        else $error = "Error adding user.";
    }
}

// 2. Handle Delete User
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM users WHERE id = $id AND role = 'user'");
    $success = "User removed.";
}

$users = $conn->query("SELECT * FROM users WHERE role = 'user' ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
require_once 'includes/header.php';
?>

<div class="app-layout">
    <?php require_once 'includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="inventory-header">
            <div>
                <h2 class="page-title">Manage Users</h2>
                <p class="page-subtitle">Add, search, and manage regular application users</p>
            </div>
            <button class="btn btn-primary" onclick="document.getElementById('addUserModal').classList.add('show')">
                <i class="fas fa-plus"></i> Add User
            </button>
        </div>

        <div class="toolbar-card" style="margin-bottom:20px; padding:15px;">
            <div style="position:relative; max-width:400px;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-light);"></i>
                <input type="text" id="userSearch" class="form-control" placeholder="Search by name or email..." style="padding-left:35px;">
            </div>
        </div>

        <?php if($success): ?> <div class="alert alert-success"><?= $success ?></div> <?php endif; ?>
        <?php if($error): ?> <div class="alert alert-danger"><?= $error ?></div> <?php endif; ?>

        <div class="data-card">
            <table class="data-table" id="userTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined Date</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $u): ?>
                    <tr class="user-row">
                        <td><strong><?= htmlspecialchars($u['name']) ?></strong></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                        <td style="text-align:right;">
                            <a href="?delete=<?= $u['id'] ?>" class="btn btn-sm btn-outline confirm-delete" style="color:var(--danger);">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<div class="modal-overlay" id="addUserModal">
    <div class="modal-box">
        <h3 class="modal-title">Add New User</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add_user">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Temporary Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('addUserModal').classList.remove('show')">Cancel</button>
                <button type="submit" class="btn btn-primary">Create User</button>
            </div>
        </form>
    </div>
</div>

<?php
$extraJS = <<<JS
<script>
// Real-time Search Logic
const searchInput = document.getElementById('userSearch');
const rows = document.querySelectorAll('.user-row');

searchInput.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
    });
});
</script>
JS;
require_once 'includes/footer.php';