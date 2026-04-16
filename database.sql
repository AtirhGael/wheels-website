-- Elite BBS Rims - Database Schema
-- Run this in phpMyAdmin or via command line

-- Create database
CREATE DATABASE IF NOT EXISTS elitebbs_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE elitebbs_db;

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2) DEFAULT NULL,
    sku VARCHAR(100) UNIQUE,
    stock INT DEFAULT 0,
    category VARCHAR(100),
    brand VARCHAR(100),
    size VARCHAR(50),
    finish VARCHAR(50),
    fitment_data JSON,
    images JSON,
    featured BOOLEAN DEFAULT FALSE,
    status ENUM('active','draft') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_category (category),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(30),
    billing_address TEXT,
    shipping_address TEXT,
    vehicle_make VARCHAR(50),
    vehicle_model VARCHAR(50),
    vehicle_year VARCHAR(10),
    notes TEXT,
    items_json JSON NOT NULL,
    subtotal DECIMAL(10,2) DEFAULT 0,
    shipping_cost DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'email_transfer',
    status ENUM('pending','processing','shipped','completed','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_number (order_number),
    INDEX idx_status (status),
    INDEX idx_customer_email (customer_email),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order items table (optional - can also store in items_json)
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(255) NOT NULL,
    product_sku VARCHAR(100),
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX idx_order_id (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    role ENUM('super_admin','admin') DEFAULT 'admin',
    status ENUM('active','inactive') DEFAULT 'active',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Site settings table
CREATE TABLE IF NOT EXISTS site_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin user (username: admin, password: admin123)
-- Password is hashed with password_hash()
INSERT INTO admins (username, password, email, full_name, role, status) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@elitebbswheelsus.shop', ' administrator', 'super_admin', 'active');

-- Default site settings
INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_name', 'Elite BBS Rims'),
('site_email', 'info@elitebbswheelsus.shop'),
('contact_phone', '(555) 123-4567'),
('contact_address', '123 Wheel Street, Auto City, CA 90001'),
('business_hours', 'Mon-Fri: 9AM-6PM EST');

-- Sample product data (optional - can insert test data)
-- INSERT INTO products (name, slug, short_description, description, price, category, brand, status) VALUES
-- ('BBS Super RS', 'bbs-super-rs', 'Classic 3-piece forged wheel', 'The BBS Super RS is a legendary 3-piece forged wheel...', 450.00, 'Wheels', 'BBS', 'active');