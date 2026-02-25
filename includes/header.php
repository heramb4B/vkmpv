<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — VKMPV' : 'Vivekananda Kendra Marathi Prakashan Vibhag' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tiro+Devanagari+Marathi:ital@0;1&family=Cinzel:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <?= isset($extraCSS) ? $extraCSS : '' ?>
</head>
<body class="<?= isset($bodyClass) ? $bodyClass : '' ?>">

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="nav-logo">
            <div class="logo-mark">VK</div>
            <div class="logo-text">
                <span class="logo-main">VKMPV</span>
                <span class="logo-sub">Prakashan Vibhag</span>
            </div>
        </a>
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>
        <ul class="nav-links" id="navLinks">
            <li><a href="index.php" class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">Home</a></li>
            <li><a href="about.php" class="nav-link <?= $currentPage === 'about.php' ? 'active' : '' ?>">About Us</a></li>
            <li><a href="contact.php" class="nav-link <?= $currentPage === 'contact.php' ? 'active' : '' ?>">Contact Us</a></li>
            <li class="nav-auth">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" class="nav-greeting">
                        <i class="fas fa-user-circle"></i>
                        Namaskar<?= $_SESSION['role'] === 'admin' ? ', Admin' : '' ?>, 
                        <strong><?= htmlspecialchars($_SESSION['name']) ?></strong>
                    </a>
                    <a href="logout.php" class="btn-nav-logout">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn-nav-login">Login / Sign Up</a>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</nav>
