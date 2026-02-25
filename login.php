<?php
$pageTitle = 'Login';
require_once 'config/auth.php';
require_once 'config/db.php';
redirectIfLoggedIn();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name']    = $user['name'];
                $_SESSION['email']   = $user['email'];
                $_SESSION['role']    = $user['role'];
                session_regenerate_id(true);
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Incorrect password. Please try again.';
            }
        } else {
            $error = 'No account found with that email address.';
        }
        $stmt->close();
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

        <h2>Welcome Back</h2>
        <p class="auth-subtitle">Sign in to access the inventory system</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <div class="input-group">
                    <input type="email" id="email" name="email" class="form-control"
                           placeholder="you@example.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    <i class="fas fa-eye input-icon toggle-password" data-target="password"></i>
                </div>
            </div>
            <div style="text-align:right; margin-bottom:20px; margin-top:-8px;">
                <a href="forget-pass.php" style="font-size:13px; color:var(--saffron);">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:13px;">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="auth-divider">or</div>
        <p style="text-align:center; font-size:14px;">
            Don't have an account? <a href="signup.php" style="color:var(--saffron); font-weight:700;">Sign Up</a>
        </p>

        <!-- Demo credentials hint -->
        <!-- <div style="margin-top:24px; padding:14px; background:var(--saffron-pale); border-radius:var(--radius); font-size:12px; color:var(--text-mid);">
            <strong style="color:var(--maroon);">Demo Credentials:</strong><br>
            Admin: admin@gmail.com / Admin@1234<br>
            User: user@gmail.com / User@1234
        </div> -->
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
