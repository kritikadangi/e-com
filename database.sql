-- Create database
CREATE DATABASE IF NOT EXISTS ecommerce;
USE ecommerce;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (name, email, password, phone, address, role) VALUES 
('Admin', 'admin@ecommerce.com', '$2y$10$7KhacWzYhTWb3wLus1fNIeHai0eRZvzRq4ZAD/rHLNAI8RjQjFywm', '1234567890', 'Admin Address', 'admin');

-- Insert default customer user (password: customer123)
INSERT INTO users (name, email, password, phone, address, role) VALUES 
('John Doe', 'customer@ecommerce.com', '$2y$10$QE3QqgG4BAq2BWZFFQ.j7.hSEfP.xHs2owMBnvF.ZaMid8BdIGO8y', '9876543210', '123 Main St, City', 'customer');

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert dummy products
INSERT INTO products (name, category, description, price, stock, image) VALUES 
('Floral Summer Dress', 'Women', 'Beautiful floral summer dress with lightweight fabric', 49.99, 20, ''),
('Classic Denim Jeans', 'Men', 'Comfortable classic denim jeans for men', 39.99, 15, ''),
('Cute T-Shirt', 'Child', 'Soft cotton t-shirt for kids with cartoon print', 14.99, 30, ''),
('Leather Wallet', 'Accessories', 'Genuine leather wallet with multiple card slots', 24.99, 18, ''),
('Elegant Handbag', 'Women', 'Stylish handbag for everyday use', 69.99, 12, ''),
('Running Shoes', 'Men', 'Comfortable running shoes with cushioning', 59.99, 10, '');

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'Cash on Delivery',
    order_status ENUM('Pending', 'Approved', 'Processing', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
