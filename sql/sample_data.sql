
-- Use the correct database
USE fittrack_pro;

-- ================================
-- 1. USERS (Admin, Trainer, Member)
-- ================================
INSERT INTO users (username, password_hash, role) VALUES
-- Password: admin123
('admin', '$2y$10$qrXJ7Y1nfbEoudJ.q7XcKuXz9Fla2yEMmClYtmiCg4SjEa.1heoa.', 'admin'),
-- Password: trainer123
('trainer123', '$2b$12$30QFsDcmFyYFxbq5rnk9y.iLo9OLoLMxh/rE69m9vxZHWsMx.ysZq', 'trainer'),
-- Password: member123
('member123', '$2b$12$PlaHPkAraZ7tV7WgSKsX5.10OQuWptNRuN.PqSuapIv0v8W0xGble', 'member');

-- ================================
-- 2. MEMBERS
-- ================================
INSERT INTO members (full_name, email, phone, join_date, status) VALUES
('Alex Smith', 'alex@example.com', '1234567890', '2025-04-01', 'Active'),
('Maria Johnson', 'maria@example.com', '9876543210', '2025-03-15', 'Active'),
('Robert King', 'robert@example.com', '4567891230', '2025-02-20', 'Pending'),
('Sujata', 'member123', '0400000000', '2025-05-28', 'Active');

-- ================================
-- 3. CLASSES
-- ================================
INSERT INTO classes (title, instructor, start_time, end_time, day_of_week, capacity, enrolled) VALUES
('Morning Yoga', 'Emily Clark', '06:30:00', '07:30:00', 'Monday', 20, 12),
('HIIT Workout', 'John Lee', '09:00:00', '10:00:00', 'Wednesday', 20, 18),
('Spin Class', 'Sarah Kim', '12:00:00', '13:00:00', 'Friday', 15, 15),
('Strength Training', 'trainer123', '09:00:00', '10:00:00', 'Tuesday', 20, 0);

-- ================================
-- 4. EQUIPMENT
-- ================================
INSERT INTO equipment (name, usage_percent, status, notes) VALUES
('Treadmill #1', 85, 'Available', NULL),
('Elliptical #2', 72, 'Maintenance', 'Display not working'),
('Stationary Bike #3', 90, 'Available', NULL),
('Leg Press', 65, 'Maintenance', 'Hydraulic leak');

-- ================================
-- 5. PAYMENTS
-- ================================
INSERT INTO payments (member_id, amount, payment_date, status) VALUES
(1, 49.99, '2025-04-05', 'Paid'),
(2, 49.99, '2025-04-10', 'Pending'),
(3, 49.99, '2025-03-10', 'Failed');
