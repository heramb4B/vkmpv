<?php
$pageTitle = 'Home';
require_once 'config/auth.php';
require_once 'includes/header.php';
?>

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="hero-pattern"></div>
    <div class="hero-accent"></div>
    <div class="hero-content">
        <div class="hero-text">
            <span class="hero-label">&#x1F4DA; Publication House</span>
            <h1 class="hero-title">
                Vivekananda Kendra<br>
                <span>Marathi Prakashan</span><br>
                Vibhag
            </h1>
            <p class="hero-desc">
                Dedicated to spreading the timeless wisdom of Swami Vivekananda through
                literature in Marathi, Hindi & English — managed with care by our NGO.
            </p>
            <div class="hero-btns">
                <a href="<?= isLoggedIn() ? 'dashboard.php' : 'login.php' ?>" class="btn-hero-primary">
                    <i class="fas fa-arrow-right"></i> Enter Application
                </a>
                <a href="about.php" class="btn-hero-secondary">About Us</a>
            </div>
        </div>
        <div class="hero-image-side">
            <div class="hero-decorative">
                <div class="hero-monogram">&#x22;</div>
            </div>
        </div>
    </div>
</section>

<!-- INFO SECTION -->
<section style="background: var(--cream-dark); padding: 6px 0;">
    <div class="info-section">
        <div style="text-align:center; margin-bottom: 52px;">
            <span style="font-size:12px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--saffron);">About Our Organisation</span>
            <h2 style="font-family:'Cinzel',serif; font-size:32px; color:var(--maroon); margin-top:10px; margin-bottom:10px;">Rooted in Heritage, Driven by Purpose</h2>
            <div style="width:60px;height:3px;background:linear-gradient(90deg,var(--gold),var(--saffron));margin:0 auto;border-radius:2px;"></div>
        </div>
        <div class="info-grid">
            <div class="info-card">
                <div class="info-card-icon"><i class="fas fa-om"></i></div>
                <h3>Vivekananda Kendra</h3>
                <p>
                    Vivekananda Kendra is a spiritually oriented service mission founded in 1972 by
                    Eknathji Ranade, inspired by the life and message of Swami Vivekananda.
                    The Kendra conducts various developmental activities in education, rural development,
                    natural resource development, and cultural activities across India — particularly
                    in remote regions like the Northeast.
                </p>
                <p style="margin-top:12px;">
                    The Kendra's motto, <em>"Man Making, Nation Building,"</em> encapsulates its core philosophy
                    of developing strong, service-oriented individuals who contribute to society's growth.
                </p>
            </div>
            <div class="info-card">
                <div class="info-card-icon"><i class="fas fa-book-open"></i></div>
                <h3>Vivekananda Kendra Marathi Prakashan Vibhag</h3>
                <p>
                    The Marathi Prakashan Vibhag (VKMPV) is the publication wing of Vivekananda Kendra
                    dedicated to making the writings, speeches and philosophy of Swami Vivekananda
                    accessible to Marathi-speaking readers.
                </p>
                <p style="margin-top:12px;">
                    As a publication house run by this non-governmental organisation, VKMPV publishes books,
                    diaries, calendars, and other educational materials in Marathi, Hindi, and English
                    — reaching thousands of readers across Maharashtra and beyond.
                </p>
                <p style="margin-top:12px;">
                    Located in the heart of Pune at Sadashiv Peth, VKMPV has been a beacon of
                    Vivekananda's thought for decades, offering literature at accessible prices to all.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- QUICK STATS BAR -->
<section style="background:var(--maroon-deep); padding:40px 24px;">
    <div style="max-width:1280px; margin:0 auto; display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:32px; text-align:center;">
        <div>
            <div style="font-family:'Cinzel',serif; font-size:36px; font-weight:700; color:var(--gold-light);">50+</div>
            <div style="color:rgba(255,255,255,0.65); font-size:14px; margin-top:4px;">Titles Published</div>
        </div>
        <div>
            <div style="font-family:'Cinzel',serif; font-size:36px; font-weight:700; color:var(--gold-light);">3</div>
            <div style="color:rgba(255,255,255,0.65); font-size:14px; margin-top:4px;">Languages</div>
        </div>
        <div>
            <div style="font-family:'Cinzel',serif; font-size:36px; font-weight:700; color:var(--gold-light);">1972</div>
            <div style="color:rgba(255,255,255,0.65); font-size:14px; margin-top:4px;">Founded</div>
        </div>
        <div>
            <div style="font-family:'Cinzel',serif; font-size:36px; font-weight:700; color:var(--gold-light);">NGO</div>
            <div style="color:rgba(255,255,255,0.65); font-size:14px; margin-top:4px;">Non-Profit Mission</div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
