<?php
$pageTitle = 'Sign Up';
require_once 'config/auth.php';
require_once 'config/db.php';
redirectIfLoggedIn();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $conn = getDBConnection();
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param('s', $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = 'An account with this email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param('sss', $name, $email, $hash);
            if ($stmt->execute()) {
                $success = 'Account created successfully! You can now <a href="login.php">log in</a>.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
            $stmt->close();
        }
        $check->close();
        $conn->close();
    }
}

require_once 'includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="logo-mark"><span>VK</span></div>
            <div class="logo-text">
                <span class="logo-main" style="color:var(--maroon)">VKMPV</span>
                <span class="logo-sub" style="color:var(--text-light)">Inventory System</span>
            </div>
        </div>

        <h2>Create Account</h2>
        <p class="auth-subtitle">Register as a Normal User to access inventory</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $success ?></div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Ramesh Patil"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Min. 6 characters" required>
                    <i class="fas fa-eye input-icon toggle-password" data-target="password"></i>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:13px;">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <p style="text-align:center; font-size:14px; margin-top:20px;">
            Already have an account? <a href="login.php" style="color:var(--saffron); font-weight:700;">Login</a>
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
