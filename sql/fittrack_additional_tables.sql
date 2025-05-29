
-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    class_id INT NOT NULL,
    booking_date DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE
);

-- Attendance table
CREATE TABLE IF NOT EXISTS attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    class_id INT NOT NULL,
    attended_on DATE DEFAULT CURRENT_DATE,
    status ENUM('Present', 'Absent') DEFAULT 'Present',
    FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE
);

-- Subscriptions table
CREATE TABLE IF NOT EXISTS subscriptions (
    subscription_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    plan_name VARCHAR(100),
    price DECIMAL(10,2),
    billing_cycle ENUM('Monthly', 'Quarterly', 'Yearly') DEFAULT 'Monthly',
    next_billing_date DATE,
    payment_method ENUM('Credit Card', 'Direct Debit') DEFAULT 'Direct Debit',
    status ENUM('Active', 'Paused', 'Cancelled') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE
);

-- Direct debit mandates table
CREATE TABLE IF NOT EXISTS direct_debit_mandates (
    mandate_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    bank_name VARCHAR(100),
    account_number VARCHAR(20),
    bsb VARCHAR(10),
    authorization_date DATE,
    status ENUM('Authorized', 'Revoked') DEFAULT 'Authorized',
    FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE
);

-- Trainer feedback or notes table (optional for trainer-member interaction)
CREATE TABLE IF NOT EXISTS trainer_notes (
    note_id INT AUTO_INCREMENT PRIMARY KEY,
    trainer_id INT,
    member_id INT,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trainer_id) REFERENCES users(user_id),
    FOREIGN KEY (member_id) REFERENCES members(member_id)
);

-- Support tickets (for contacting admin or support)
CREATE TABLE IF NOT EXISTS support_tickets (
    ticket_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    subject VARCHAR(255),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Open', 'In Progress', 'Resolved') DEFAULT 'Open',
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

