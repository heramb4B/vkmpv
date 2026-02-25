-- ============================================================
-- VKMPV Inventory Management System
-- UPDATE SCRIPT: v1 → v2
-- Run this ONLY if you already have v1 installed.
-- If fresh install, use database.sql instead.
-- ============================================================

USE vkmpv_inventory;

-- ── Add contact_submissions table (new in v2) ───────────────
CREATE TABLE IF NOT EXISTS contact_submissions (
    id           INT          AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(100) NOT NULL,
    email        VARCHAR(150) NOT NULL,
    phone        VARCHAR(20)  DEFAULT NULL,
    subject      VARCHAR(255) NOT NULL,
    message      TEXT         NOT NULL,
    status       ENUM('New','Contacted','Interested','Not Interested')
                              NOT NULL DEFAULT 'New',
    submitted_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_contact_status (status),
    INDEX idx_contact_submitted (submitted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Sample contact submissions ──────────────────────────────
INSERT IGNORE INTO contact_submissions (name, email, phone, subject, message, status, submitted_at) VALUES
('Priya Sharma',  'priya.sharma@example.com',  '9823456789', 'Bulk Order Inquiry',    'I am interested in placing a bulk order of Karma Yoga books for our school library.',       'Interested',    '2025-01-10 10:23:00'),
('Arjun Desai',   'arjun.desai@gmail.com',     '8765432109', 'Book Availability',     'Looking for the Hindi edition of Complete Works. Are these currently in stock?',             'Contacted',     '2025-01-18 14:45:00'),
('Meena Kulkarni','meena.kulkarni@yahoo.com',  NULL,         'Diary Wholesale Price', 'We run a bookstore and are interested in stocking your Vivekananda Diaries.',               'New',           '2025-01-25 09:15:00'),
('Rohit Joshi',   'rohit.joshi@outlook.com',   '7654321098', 'Donation Query',        'I would like to sponsor publication distribution to tribal schools in Maharashtra.',          'New',           '2025-02-03 16:30:00'),
('Vikram Nair',   'vikram.nair@example.org',   '8899001122', 'Calendar Order',        'Our NGO needs 100 wall calendars for branch offices. Please confirm availability.',          'Contacted',     '2025-02-12 13:20:00'),
('Sudhir Marathe','sudhir.marathe@company.com','9876500123', 'Corporate Gifting',     'Planning to gift Vivekananda literature to 200 employees. Can you customise packaging?',    'Interested',    '2025-02-19 15:00:00'),
('Lalita Bendre', 'lalita.bendre@sample.in',   '7788990011', 'Requesting Catalogue',  'Could you please email your full publication catalogue?',                                    'New',           '2025-02-22 10:10:00'),
('Nikhil Wagh',   'nikhil.wagh@techmail.com',  '9001122334', 'Website Feedback',      'Suggestion: an online ordering system with home delivery option would be very helpful.',     'Contacted',     '2025-02-24 17:30:00');

-- ── No schema changes needed to existing tables in v2 ───────
-- The users table already supports role = 'admin' for Manage Admins.
-- No migration needed for books, utilities, monthly_sales, or stock_log.
