-- Database: shared_hosting
CREATE DATABASE IF NOT EXISTS shared_hosting;
USE shared_hosting;

-- Users Table (With Bank-grade Security Fields)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    google_id VARCHAR(255) NULL UNIQUE,
    avatar VARCHAR(255) NULL,
    password VARCHAR(255) NULL, -- Argon2 hash
    role ENUM('admin', 'client') DEFAULT 'client',
    two_factor_secret VARCHAR(255) DEFAULT NULL,
    two_factor_enabled TINYINT(1) DEFAULT 0,
    allowed_ips TEXT DEFAULT NULL, -- Comma-separated list of allowed IPs
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Site Settings
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    description VARCHAR(255)
);

-- Services Table (Plans/Products)
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL DEFAULT 'Web Development',
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0,
    billing_cycle ENUM('monthly', 'yearly', 'one-time') DEFAULT 'monthly',
    icon VARCHAR(255) DEFAULT NULL,
    features TEXT, -- JSON or comma separated
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Client Services Table (What hosting/services the client owns)
CREATE TABLE IF NOT EXISTS client_services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    domain_name VARCHAR(255) DEFAULT NULL,
    status ENUM('pending', 'active', 'suspended', 'cancelled') DEFAULT 'pending',
    billing_cycle ENUM('monthly', 'yearly', 'one-time') DEFAULT 'monthly',
    price DECIMAL(10, 2) NOT NULL,
    next_due_date DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Invoices Table
CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    client_service_id INT DEFAULT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('unpaid', 'pending_verification', 'paid', 'cancelled') DEFAULT 'unpaid',
    due_date DATE NOT NULL,
    paid_date DATETIME DEFAULT NULL,
    payment_method VARCHAR(100) DEFAULT NULL,
    utr_number VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (client_service_id) REFERENCES client_services(id) ON DELETE SET NULL
);

-- Support Tickets Table
CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    client_service_id INT DEFAULT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open',
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (client_service_id) REFERENCES client_services(id) ON DELETE SET NULL
);

-- Ticket Replies Table
CREATE TABLE IF NOT EXISTS ticket_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    user_id INT NOT NULL, -- Can be client or admin replying
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Projects Table (Portfolio)
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    description TEXT,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Testimonials Table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    company VARCHAR(255),
    feedback TEXT NOT NULL,
    initials VARCHAR(10),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Pages Table (For Privacy Policy, Terms, etc.)
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- FAQs Table
CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Audit Logs Table
CREATE TABLE IF NOT EXISTS client_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Chat Messages Table
CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);


-- ==========================================
-- INSERT DEFAULT DATA
-- ==========================================

-- Insert Default Settings
INSERT IGNORE INTO settings (setting_key, setting_value, description) VALUES
('site_name', 'Abhish.in', 'Name of the website'),
('contact_email', 'abhishcare@gmail.com', 'Primary contact email'),
('contact_phone_1', '+91 86309 71461', 'Contact phone 1'),
('contact_phone_2', '+91 82791 78287', 'Contact phone 2'),
('contact_phone_3', '+91 60450 19080', 'Contact phone 3'),
('contact_location', 'Jawalapur, Haridwar, India', 'Office location'),
('google_client_id', '', 'Google OAuth2 Client ID'),
('google_client_secret', '', 'Google OAuth2 Client Secret'),
('google_redirect_uri', 'http://localhost:8000/auth/googleCallback', 'Google OAuth2 Redirect URI'),
('smtp_host', 'smtp.example.com', 'SMTP Server Host'),
('smtp_port', '587', 'SMTP Server Port (e.g. 587 or 465)'),
('smtp_user', '', 'SMTP Username'),
('smtp_pass', '', 'SMTP Password'),
('smtp_from_email', 'noreply@abhish.in', 'From Email Address'),
('smtp_from_name', 'Abhish.in Hosting', 'From Name'),
('upi_id', 'example@upi', 'Admin UPI ID for receiving payments'),
('announcement_text', '', 'Global Announcement/Notice for Clients'),
('announcement_active', '0', 'Enable/Disable Global Announcement (1 or 0)');

-- Insert Initial Services Content
INSERT INTO services (title, category, description, price, billing_cycle, features, sort_order) VALUES
('Basic Portfolio Site', 'Web Development', 'A simple, 5-page responsive website perfect for small businesses.', 4999.00, 'one-time', 'Responsive Design,5 Pages,Basic SEO,Contact Form', 1),
('E-commerce Starter', 'Web Development', 'Full online store with payment gateway integration for up to 50 products.', 14999.00, 'one-time', 'Payment Gateway,Inventory System,Admin Dashboard,Mobile Friendly', 2),
('Enterprise Web App', 'Web Development', 'Custom scalable web application with advanced user roles and analytics.', 49999.00, 'one-time', 'Custom MVC Architecture,Advanced Security,Real-time Analytics,API Integrations', 3),
('Standard Android App', 'App Development', 'Native Android app designed for utility and content delivery.', 19999.00, 'one-time', 'Native Kotlin,Push Notifications,Offline Support,Play Store Setup', 4),
('Hybrid App (iOS & Android)', 'App Development', 'Cross-platform app utilizing Flutter for maximum reach.', 34999.00, 'one-time', 'Flutter Framework,Single Codebase,iOS & Android,Firebase Integration', 5),
('Startup SEO Package', 'Digital Marketing', 'Monthly SEO optimization to get your brand ranking on Google.', 2999.00, 'monthly', 'Keyword Research,On-page Optimization,Monthly Reports,Backlink Building', 6),
('Social Media Growth', 'Digital Marketing', 'Complete management of 3 social platforms to drive engagement.', 5999.00, 'monthly', 'Content Creation,3 Platforms,Daily Posting,Ad Campaign Management', 7);

-- Insert Initial Projects Content
INSERT INTO projects (title, category, image_url, sort_order) VALUES
('Yukti Medical App', 'Android Kotlin Healthcare', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80', 1),
('E-Commerce Platform', 'PHP MySQL E-Commerce', 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80', 2),
('Business Dashboard', 'React Node.js Analytics', 'https://images.unsplash.com/photo-1551650975-87deedd944c3?auto=format&fit=crop&w=800&q=80', 3),
('Travel Booking App', 'Flutter Firebase Travel', 'https://images.unsplash.com/photo-1547658719-da2b51169166?auto=format&fit=crop&w=800&q=80', 4);

-- Insert Initial Testimonials
INSERT INTO testimonials (client_name, company, feedback, initials, sort_order) VALUES
('Priya Sharma', 'CEO, TechCorp India', 'Abhish.in transformed our digital presence completely. Their team delivered an exceptional website that exceeded all our expectations. Professional, responsive, and highly skilled!', 'P', 1),
('Rahul Mehta', 'Founder, StartupHub', 'Working with Abhish.in was a game-changer for our startup. They developed our mobile app from scratch and it has been performing flawlessly. Highly recommend their services!', 'R', 2),
('Anjali Desai', 'Director, Fashion Bazaar', 'The attention to detail and technical expertise from the Abhish.in team is outstanding. They delivered our e-commerce platform on time and within budget. True professionals!', 'A', 3);

-- Insert Initial Pages
INSERT INTO pages (slug, title, content) VALUES
('about', 'About Us', '<h2>Transform Your Digital Vision Into Reality</h2><p>We provide premium web & app development services that drive growth, innovation, and exceptional user experiences. With years of experience, we have successfully delivered projects and satisfied many clients.</p>'),
('privacy-policy', 'Privacy Policy', '<h2>Privacy Policy</h2><p>This is the privacy policy for Abhish.in...</p>'),
('terms', 'Terms and Conditions', '<h2>Terms & Conditions</h2><p>These are the terms and conditions...</p>');

-- Insert Initial FAQs
INSERT INTO faqs (question, answer, sort_order) VALUES
('What services do you offer?', 'We offer Web Development, App Development, UI/UX Design, Backend Development, E-Commerce Solutions, and Digital Marketing.', 1),
('How can I contact you?', 'You can contact us via email at abhishcare@gmail.com or call us at +91 86309 71461.', 2);

-- Insert Default Admin User (Password is 'admin123')
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@abhish.in', '$argon2id$v=19$m=65536,t=4,p=2$eEN6UzZtLmZyM2NMc3RaTw$kuPunyAtmWwCIZEomV7UgUXdKw+4PsXB4TzgBhv24JM', 'admin');
