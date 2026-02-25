<?php
$pageTitle = 'Forgot Password';
require_once 'config/auth.php';
require_once 'config/db.php';
redirectIfLoggedIn();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        // Always show success to prevent user enumeration
        $success = 'If an account exists with that email, a password reset link has been sent. Please check your inbox.';
        // In production: generate token, save to DB, send email
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
        </div>

        <h2>Forgot Password</h2>
        <p class="auth-subtitle">Enter your email and we'll send you a reset link</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $success ?></div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:13px;">
                <i class="fas fa-paper-plane"></i> Send Reset Link
            </button>
        </form>

        <p style="text-align:center; font-size:14px; margin-top:20px;">
            Remembered it? <a href="login.php" style="color:var(--saffron); font-weight:700;">Back to Login</a>
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
