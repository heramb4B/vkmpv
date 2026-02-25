<?php // includes/footer.php ?>
<!-- FOOTER -->
<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-col footer-brand">
            <div class="footer-logo">
                <div class="logo-mark">VK</div>
                <div class="logo-text">
                    <span class="logo-main">VKMPV</span>
                    <span class="logo-sub">Prakashan Vibhag</span>
                </div>
            </div>
            <p class="footer-tagline">Spreading wisdom through literature since 1972.</p>
            <div class="footer-social">
                <a href="#" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            </div>
        </div>

        <div class="footer-col">
            <h4 class="footer-heading">Quick Links</h4>
            <ul class="footer-links">
                <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                <li><a href="about.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
                <li><a href="contact.php"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                <li><a href="login.php"><i class="fas fa-chevron-right"></i> Login</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4 class="footer-heading">Contact Us</h4>
            <div class="footer-contact">
                <p><strong>Vivekananda Kendra</strong><br>Marathi Prakashan Vibhag</p>
                <p><i class="fas fa-map-marker-alt"></i> 2nd Floor, Ashwini Heights,<br>
                Tilak Road, Sadashiv Peth,<br>Pune – 411 030</p>
                <p><i class="fas fa-phone"></i> <a href="tel:+918999166623">89991 66623</a></p>
                <p><i class="fas fa-envelope"></i> <a href="mailto:vkmpv@gmail.com">vkmpv@gmail.com</a></p>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> Vivekananda Kendra Marathi Prakashan Vibhag. All Rights Reserved.</p>
    </div>
</footer>

<script src="assets/js/main.js"></script>
<?= isset($extraJS) ? $extraJS : '' ?>
</body>
</html>
