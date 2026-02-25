<?php
// includes/sidebar.php — Admin only sidebar
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="logo-mark sm">VK</div>
            <span>Admin Panel</span>
        </div>
        <button class="sidebar-close" id="sidebarClose"><i class="fas fa-times"></i></button>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="dashboard.php" class="sidebar-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="book-inventory.php" class="sidebar-link <?= $currentPage === 'book-inventory.php' || $currentPage === 'add-book.php' ? 'active' : '' ?>">
                    <i class="fas fa-book"></i>
                    <span>Book Inventory</span>
                </a>
            </li>
            <li>
                <a href="utilities-inventory.php" class="sidebar-link <?= $currentPage === 'utilities-inventory.php' || $currentPage === 'add-utility.php' ? 'active' : '' ?>">
                    <i class="fas fa-boxes-stacked"></i>
                    <span>Utilities Inventory</span>
                </a>
            </li>
            <li class="sidebar-divider"></li>
            <li>
                <a href="contact-submissions.php" class="sidebar-link <?= $currentPage === 'contact-submissions.php' ? 'active' : '' ?>">
                    <i class="fas fa-inbox"></i>
                    <span>Form Submissions</span>
                </a>
            </li>
            <li>
                <a href="manage-admins.php" class="sidebar-link <?= $currentPage === 'manage-admins.php' ? 'active' : '' ?>">
                    <i class="fas fa-user-shield"></i>
                    <span>Manage Admins</span>
                </a>
            </li>
            <li>
    <a href="manage-users.php" class="sidebar-link <?= $currentPage === 'manage-users.php' ? 'active' : '' ?>">
        <i class="fas fa-users"></i>
        <span>Manage Users</span>
    </a>
</li>
        </ul>
    </nav>
    <div class="sidebar-footer">
        <a href="logout.php" class="sidebar-logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
