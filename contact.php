<?php
$pageTitle = 'Contact Us';
require_once 'config/auth.php';
require_once 'config/db.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $msg = '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> Please fill in all required fields.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> Please enter a valid email address.</div>';
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare(
            "INSERT INTO contact_submissions (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('sssss', $name, $email, $phone, $subject, $message);
        if ($stmt->execute()) {
            $msg = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Thank you for contacting us! We will get back to you soon.</div>';
            $_POST = [];
        } else {
            $msg = '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> Submission failed. Please try again.</div>';
        }
        $stmt->close();
        $conn->close();
    }
}

require_once 'includes/header.php';
?>

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Contact Us</h1>
        <div class="gold-line"></div>
        <p>We'd love to hear from you. Reach out to us!</p>
    </div>
</div>

<div class="content-section">
    <?= $msg ?>
    <div class="contact-grid">
        <div class="contact-info-block">
            <h2 style="font-family:'Cinzel',serif; font-size:24px; color:var(--maroon); margin-bottom:6px;">Get In Touch</h2>
            <p style="color:var(--text-light); margin-bottom:24px; font-size:14px;">Visit us or reach out via phone or email.</p>

            <div class="contact-item">
                <div class="contact-item-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <h4>Address</h4>
                    <p>2nd Floor, Ashwini Heights, Tilak Road,<br>Sadashiv Peth, Pune – 411 030</p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-item-icon"><i class="fas fa-phone"></i></div>
                <div>
                    <h4>Phone</h4>
                    <p><a href="tel:+918999166623">89991 66623</a></p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <h4>Email</h4>
                    <p><a href="mailto:vkmpv@gmail.com">vkmpv@gmail.com</a></p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-item-icon"><i class="fas fa-clock"></i></div>
                <div>
                    <h4>Office Hours</h4>
                    <p>Monday – Saturday: 10:00 AM – 6:00 PM<br>Sunday: Closed</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-envelope"></i> Send a Message</h3>
            </div>
            <div class="card-body">
                <form method="POST" novalidate>
                    <div class="form-group">
                        <label class="form-label">Your Name <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="name" class="form-control"
                               placeholder="Ramesh Patil"
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address <span style="color:var(--danger)">*</span></label>
                        <input type="email" name="email" class="form-control"
                               placeholder="you@example.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number
                            <span style="color:var(--text-light); font-weight:400; font-size:12px;">(optional)</span>
                        </label>
                        <input type="tel" name="phone" class="form-control"
                               placeholder="9876543210"
                               value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subject <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="subject" class="form-control"
                               placeholder="Book Inquiry"
                               value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Message <span style="color:var(--danger)">*</span></label>
                        <textarea name="message" class="form-control" rows="5"
                                  placeholder="Your message..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
