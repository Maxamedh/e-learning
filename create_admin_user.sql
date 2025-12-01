-- Create Admin User for E-LOOX Academy
-- Run this SQL after importing database_schema.sql

-- Option 1: Create admin with password 'admin123'
INSERT INTO users (uuid, email, password_hash, role, first_name, last_name, is_active, email_verified) 
VALUES (
    UUID(),
    'admin@elooxacademy.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'admin',
    'Admin',
    'User',
    TRUE,
    TRUE
);

-- Option 2: Create admin with password 'admin123' (more secure hash)
-- Password: admin123
INSERT INTO users (uuid, email, password_hash, role, first_name, last_name, is_active, email_verified) 
VALUES (
    UUID(),
    'admin@elooxacademy.com',
    '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', -- password: admin123
    'admin',
    'Admin',
    'User',
    TRUE,
    TRUE
);

-- If you want to create your own admin user, use this PHP code to generate password hash:
-- <?php echo password_hash('your_password_here', PASSWORD_DEFAULT); ?>

