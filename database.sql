-- ============================================================
-- Vivekananda Kendra Marathi Prakashan Vibhag
-- Inventory Management System — Complete Database Schema
-- Version 2.0 (includes Contact Submissions & Admin Management)
-- ============================================================

CREATE DATABASE IF NOT EXISTS vkmpv_inventory
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE vkmpv_inventory;

-- ============================================================
-- TABLE: users
-- Stores both admin and normal user accounts.
-- role = 'admin' | 'user'
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id         INT          AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    password   VARCHAR(255) NOT NULL COMMENT 'bcrypt hash via password_hash()',
    role       ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_email (email),
    INDEX idx_users_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: books
-- Book inventory with multi-language support.
-- ============================================================
CREATE TABLE IF NOT EXISTS books (
    id             INT          AUTO_INCREMENT PRIMARY KEY,
    isbn           VARCHAR(20)  NOT NULL,
    title          VARCHAR(255) NOT NULL,
    language       ENUM('Marathi','Hindi','English') NOT NULL,
    writer         VARCHAR(150) NOT NULL,
    date_published DATE         NOT NULL,
    total_stock    INT          NOT NULL DEFAULT 0,
    created_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_books_isbn (isbn),
    INDEX idx_books_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: utilities
-- Utility items (diaries, calendars, posters, etc.)
-- ============================================================
CREATE TABLE IF NOT EXISTS utilities (
    id          INT          AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    language    ENUM('Marathi','Hindi','English') NOT NULL,
    total_stock INT          NOT NULL DEFAULT 0,
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_utilities_language (language)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: monthly_sales
-- Dashboard analytics — monthly book and utility sales.
-- ============================================================
CREATE TABLE IF NOT EXISTS monthly_sales (
    id            INT         AUTO_INCREMENT PRIMARY KEY,
    month         VARCHAR(20) NOT NULL,
    year          INT         NOT NULL,
    book_sales    INT         NOT NULL DEFAULT 0,
    utility_sales INT         NOT NULL DEFAULT 0,
    created_at    TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_monthly_sales (month, year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: stock_log
-- Audit trail for every stock add/reduce action.
-- ============================================================
CREATE TABLE IF NOT EXISTS stock_log (
    id           INT      AUTO_INCREMENT PRIMARY KEY,
    item_type    ENUM('book','utility') NOT NULL,
    item_id      INT      NOT NULL,
    action       ENUM('add','reduce')  NOT NULL,
    quantity     INT      NOT NULL,
    performed_by INT      NOT NULL,
    performed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_stock_log_item (item_type, item_id),
    CONSTRAINT fk_stock_log_user
        FOREIGN KEY (performed_by) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: contact_submissions
-- Stores all contact form submissions from the public website.
-- status: New (default) → Contacted → Interested / Not Interested
-- ============================================================
CREATE TABLE IF NOT EXISTS contact_submissions (
    id           INT          AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(100) NOT NULL,
    email        VARCHAR(150) NOT NULL,
    phone        VARCHAR(20)  DEFAULT NULL COMMENT 'Optional phone number',
    subject      VARCHAR(255) NOT NULL,
    message      TEXT         NOT NULL,
    status       ENUM('New','Contacted','Interested','Not Interested')
                              NOT NULL DEFAULT 'New',
    submitted_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_contact_status (status),
    INDEX idx_contact_submitted (submitted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DEFAULT USER ACCOUNTS
-- ──────────────────────────────────────────────────────────
-- Admin:        admin@gmail.com  /  Admin@1234
-- Normal User:  user@gmail.com   /  User@1234
--
-- Passwords are stored as bcrypt hashes (PASSWORD_BCRYPT).
-- Run setup-passwords.php after import to set correct hashes,
-- as this file stores placeholder hashes for portability.
-- ============================================================

INSERT INTO users (name, email, password, role) VALUES
(
    'Admin',
    'admin@gmail.com',
    -- Hash for 'Admin@1234' — regenerate via: password_hash('Admin@1234', PASSWORD_BCRYPT)
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin'
),
(
    'Ramesh Patil',
    'user@gmail.com',
    -- Hash for 'User@1234'  — regenerate via: password_hash('User@1234', PASSWORD_BCRYPT)
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'user'
);

-- IMPORTANT: Run setup-passwords.php (php setup-passwords.php) to set real passwords,
-- then delete that file from your server.

-- ============================================================
-- SAMPLE BOOKS DATA
-- ============================================================
INSERT INTO books (isbn, title, language, writer, date_published, total_stock) VALUES
('978-81-7310-001-1', 'Vivekananda Charitra',   'Marathi', 'Swami Vivekananda', '2018-01-15', 250),
('978-81-7310-002-2', 'Karma Yoga',              'Marathi', 'Swami Vivekananda', '2019-03-20', 180),
('978-81-7310-003-3', 'Raja Yoga',               'Hindi',   'Swami Vivekananda', '2017-06-10', 320),
('978-81-7310-004-4', 'Jnana Yoga',              'Hindi',   'Swami Vivekananda', '2020-08-05', 145),
('978-81-7310-005-5', 'Complete Works Vol. 1',   'English', 'Swami Vivekananda', '2016-11-22', 200),
('978-81-7310-006-6', 'Complete Works Vol. 2',   'English', 'Swami Vivekananda', '2016-11-22', 175),
('978-81-7310-007-7', 'Bhakti Yoga',             'Marathi', 'Swami Vivekananda', '2021-01-10', 300),
('978-81-7310-008-8', 'Yuva Shakti',             'Marathi', 'Eknathji Ranade',   '2015-07-04', 410),
('978-81-7310-009-9', 'Rashtra Nirman',           'Hindi',   'Eknathji Ranade',   '2014-09-11', 260),
('978-81-7310-010-0', 'Man Making Education',    'English', 'Swami Vivekananda', '2013-05-18', 130),
('978-81-7310-011-1', 'Vivek Vichar',            'Marathi', 'Various Authors',   '2022-03-01', 85),
('978-81-7310-012-2', 'Sandesh Granth',          'Hindi',   'Various Authors',   '2022-06-15', 95);

-- ============================================================
-- SAMPLE UTILITIES DATA
-- ============================================================
INSERT INTO utilities (name, language, total_stock) VALUES
('Vivekananda Diary 2024',          'Marathi', 500),
('Vivekananda Diary 2024',          'Hindi',   450),
('Vivekananda Diary 2024',          'English', 300),
('Vivekananda Wall Calendar 2024',  'Marathi', 800),
('Vivekananda Wall Calendar 2024',  'Hindi',   700),
('Vivekananda Desktop Calendar 2024','English', 400),
('Inspirational Poster Set',        'Marathi', 250),
('Inspirational Poster Set',        'Hindi',   220),
('Vivekananda Bookmark Set',        'English', 1000),
('Kendra Pen Set',                  'Marathi', 600),
('Kendra Notepad',                  'Hindi',   350),
('Vivekananda Tote Bag',            'English', 150);

-- ============================================================
-- SAMPLE MONTHLY SALES DATA (last 6 months)
-- ============================================================
INSERT INTO monthly_sales (month, year, book_sales, utility_sales) VALUES
('September', 2024, 320, 180),
('October',   2024, 410, 220),
('November',  2024, 280, 195),
('December',  2024, 550, 380),
('January',   2025, 390, 210),
('February',  2025, 430, 260);

-- ============================================================
-- SAMPLE CONTACT FORM SUBMISSIONS
-- Demonstrates all status values for testing the admin panel.
-- ============================================================
INSERT INTO contact_submissions (name, email, phone, subject, message, status, submitted_at) VALUES
(
    'Priya Sharma',
    'priya.sharma@example.com',
    '9823456789',
    'Bulk Order Inquiry',
    'Namaste, I am interested in placing a bulk order of Karma Yoga and Bhakti Yoga books for our school library. Could you please share pricing for 50 copies of each? We are based in Nashik.',
    'Interested',
    '2025-01-10 10:23:00'
),
(
    'Arjun Desai',
    'arjun.desai@gmail.com',
    '8765432109',
    'Book Availability Query',
    'Hello, I am looking for the Hindi edition of Complete Works Vol. 1 and Vol. 2 by Swami Vivekananda. Are these currently in stock? If yes, can I visit your office to purchase them directly?',
    'Contacted',
    '2025-01-18 14:45:00'
),
(
    'Meena Kulkarni',
    'meena.kulkarni@yahoo.com',
    NULL,
    'Diary Wholesale Price',
    'We run a small bookstore in Pune and are interested in stocking your Vivekananda Diaries. Could you provide us with wholesale pricing details and minimum order quantity?',
    'New',
    '2025-01-25 09:15:00'
),
(
    'Rohit Joshi',
    'rohit.joshi@outlook.com',
    '7654321098',
    'Donation / Sponsorship Query',
    'I would like to sponsor the distribution of your publications to tribal schools in Maharashtra. Please guide me on how I can contribute and what the process is.',
    'New',
    '2025-02-03 16:30:00'
),
(
    'Sunita Patil',
    'sunita.patil@rediffmail.com',
    '9988776655',
    'Marathi Translation Request',
    'We are a literary group in Aurangabad and wish to enquire about publishing a Marathi translation of a spiritual text. Do you accept manuscripts for review? What is your submission process?',
    'Not Interested',
    '2025-02-07 11:00:00'
),
(
    'Vikram Nair',
    'vikram.nair@example.org',
    '8899001122',
    'Calendar Order for Office',
    'Our NGO would like to order 100 Vivekananda Wall Calendars for 2024 in both Marathi and Hindi for our branch offices across Maharashtra. Please confirm availability and delivery timelines.',
    'Contacted',
    '2025-02-12 13:20:00'
),
(
    'Ananya Gokhale',
    'ananya.g@gmail.com',
    NULL,
    'Event Collaboration',
    'We are organising a Youth Leadership Camp in Pune in March 2025 and would love to have VKMPV as a content partner. Could we set up a meeting to discuss possible collaboration?',
    'New',
    '2025-02-14 08:45:00'
),
(
    'Sudhir Marathe',
    'sudhir.marathe@company.com',
    '9876500123',
    'Corporate Gifting',
    'Our company is planning to gift Vivekananda literature sets to employees on our annual day. We need approximately 200 sets. Can you customise packaging with our logo? Please share a quote.',
    'Interested',
    '2025-02-19 15:00:00'
),
(
    'Lalita Bendre',
    'lalita.bendre@sample.in',
    '7788990011',
    'Requesting Catalogue',
    'Namaskar! Could you please email or post your full publication catalogue? I would like to browse all available titles before placing an order.',
    'New',
    '2025-02-22 10:10:00'
),
(
    'Nikhil Wagh',
    'nikhil.wagh@techmail.com',
    '9001122334',
    'Website Feedback',
    'The website is very informative. I appreciate the work VKMPV does. A small suggestion: it would be helpful to have an online ordering system with home delivery option. Thank you.',
    'Contacted',
    '2025-02-24 17:30:00'
);
