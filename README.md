# FashionHub E-Commerce Website Project Report

## Table of Contents
- [Project Overview](#project-overview)
- [Tech Stack](#tech-stack)
- [Features](#features)
  - [Customer Module](#customer-module)
  - [Admin Module](#admin-module)
- [Authentication System](#authentication-system)
- [Database Schema](#database-schema)
- [Payment System](#payment-system)
- [Project Structure](#project-structure)
- [Installation & Setup](#installation--setup)
- [Default Credentials](#default-credentials)
- [Usage Guide](#usage-guide)
- [Security Considerations](#security-considerations)
- [Future Enhancements](#future-enhancements)

---

## Project Overview

A beginner-friendly, full-featured e-commerce website built with core PHP, MySQL, and Bootstrap 5. This project demonstrates fundamental web development concepts including user authentication, database interactions, session management, and CRUD operations.

---

## Tech Stack

### Frontend
- **HTML5**: Markup language for structuring web pages
- **CSS3**: Styling web pages
- **Bootstrap 5**: Responsive CSS framework for modern UI
- **Font Awesome**: Icon library
- **JavaScript**: Client-side scripting

### Backend
- **Core PHP**: Server-side scripting language
- **PDO (PHP Data Objects)**: Database abstraction layer for secure MySQL interactions

### Database
- **MySQL 5.7+**: Relational database management system

### Server Requirements
- **Web Server**: Apache/Nginx
- **PHP Version**: 7.4 or higher
- **MySQL Version**: 5.7 or higher

---

## Features

### Customer Module

| Feature | Description |
|---------|-------------|
| **User Registration** | New customers can create an account with name, email, password, phone, and address |
| **User Login** | Secure login with email and password |
| **Profile Management** | Customers can view and update their profile information |
| **Browse Products** | View all products with pagination and category filtering |
| **Product Details** | Individual product pages with full description, price, and stock information |
| **Shopping Cart** | Add, update quantity, and remove items from cart |
| **Checkout** | Place orders with Cash on Delivery payment method |
| **Order History** | View past orders and track order status (Pending, Approved, Processing, Delivered, Cancelled) |

### Admin Module

| Feature | Description |
|---------|-------------|
| **Admin Login** | Separate secure login for administrators |
| **Dashboard** | Statistics overview (total products, orders, customers, pending orders) |
| **Product Management** | Add, view, edit, and delete products |
| **Order Management** | View all orders and update order status |

---

## Authentication System

### Implementation Details
- **Password Hashing**: Uses PHP's `password_hash()` with `PASSWORD_DEFAULT` algorithm (bcrypt)
- **Password Verification**: Uses `password_verify()` to check credentials
- **Session Management**: Custom session save path for better security
- **Role-Based Access Control (RBAC)**: Two user roles:
  - `customer`: For regular shoppers
  - `admin`: For site administrators

### Session Variables
```php
$_SESSION['user_id']    // User's unique ID
$_SESSION['user_name']  // User's full name
$_SESSION['role']       // User's role (customer/admin)
```

---

## Database Schema

The database `ecommerce` consists of 5 main tables:

### 1. `users` Table
Stores user (customer and admin) information.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT (AUTO_INCREMENT, PRIMARY KEY) | Unique user ID |
| `name` | VARCHAR(255) | User's full name |
| `email` | VARCHAR(255, UNIQUE) | User's email address |
| `password` | VARCHAR(255) | Hashed password |
| `phone` | VARCHAR(20) | User's phone number |
| `address` | TEXT | User's address |
| `role` | ENUM('customer', 'admin') | User role (default: customer) |
| `created_at` | TIMESTAMP | Account creation time |

### 2. `products` Table
Stores product information.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT (AUTO_INCREMENT, PRIMARY KEY) | Unique product ID |
| `name` | VARCHAR(255) | Product name |
| `category` | VARCHAR(100) | Product category |
| `description` | TEXT | Product description |
| `price` | DECIMAL(10,2) | Product price |
| `stock` | INT | Product stock quantity (default: 0) |
| `image` | VARCHAR(255) | Product image filename |
| `created_at` | TIMESTAMP | Product addition time |

### 3. `cart` Table
Stores shopping cart items.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT (AUTO_INCREMENT, PRIMARY KEY) | Unique cart item ID |
| `user_id` | INT (FOREIGN KEY) | Reference to users.id |
| `product_id` | INT (FOREIGN KEY) | Reference to products.id |
| `quantity` | INT | Item quantity (default: 1) |

### 4. `orders` Table
Stores order information.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT (AUTO_INCREMENT, PRIMARY KEY) | Unique order ID |
| `user_id` | INT (FOREIGN KEY) | Reference to users.id |
| `total_amount` | DECIMAL(10,2) | Order total amount |
| `payment_method` | VARCHAR(50) | Payment method (default: Cash on Delivery) |
| `order_status` | ENUM('Pending', 'Approved', 'Processing', 'Delivered', 'Cancelled') | Order status (default: Pending) |
| `order_date` | TIMESTAMP | Order placement time |

### 5. `order_items` Table
Stores individual items for each order.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT (AUTO_INCREMENT, PRIMARY KEY) | Unique order item ID |
| `order_id` | INT (FOREIGN KEY) | Reference to orders.id |
| `product_id` | INT (FOREIGN KEY) | Reference to products.id |
| `quantity` | INT | Item quantity |
| `price` | DECIMAL(10,2) | Item price at time of order |

---

## Payment System

### Current Implementation
- **Payment Method**: Cash on Delivery (COD)
- **Features**:
  - Simple checkout process
  - Order total calculation
  - Stock deduction on order placement
  - Transactional integrity using PDO transactions

---

## Project Structure

```
ecommerce/
├── admin/                      # Admin panel files
│   ├── add_product.php         # Add new product
│   ├── dashboard.php           # Admin dashboard with stats
│   ├── delete_product.php      # Delete product
│   ├── edit_product.php        # Edit product details
│   ├── login.php               # Admin login page
│   ├── manage_orders.php       # Manage and update orders
│   └── manage_products.php     # View and manage products
│
├── assets/                     # Static assets
│   └── css/
│       └── style.css           # Custom CSS styles
│
├── config/
│   └── database.php            # Database connection and configuration
│
├── customer/                   # Customer-facing files
│   ├── cart.php                # Shopping cart page
│   ├── checkout.php            # Checkout page
│   ├── login.php               # Customer login page
│   ├── my_orders.php           # Customer order history
│   ├── product_detail.php      # Individual product details
│   ├── products.php            # Products listing page
│   ├── profile.php             # Customer profile page
│   └── signup.php              # Customer registration page
│
├── includes/                   # Reusable components
│   ├── footer.php              # Footer section
│   ├── header.php              # Head section (meta, CSS, JS)
│   └── navbar.php              # Navigation bar
│
├── sessions/                   # Session files directory
│
├── uploads/                    # Product images upload directory
│
├── database.sql                # Database schema and sample data
├── generate_customer_hash.php  # Utility for password hashing
├── index.php                   # Home page
├── logout.php                  # Logout script
└── README.md                   # This file
```

---

## Installation & Setup

### Prerequisites
1. Web Server (Apache/Nginx)
2. PHP 7.4 or higher
3. MySQL 5.7 or higher
4. Recommended: XAMPP/WAMP/LAMP for local development

### Step 1: Download/Clone the Project
Place the project folder in your web server's document root:
- **XAMPP**: `C:\xampp\htdocs\`
- **WAMP**: `C:\wamp64\www\`
- **LAMP**: `/var/www/html/`

### Step 2: Create Database
1. Open phpMyAdmin (usually at `http://localhost/phpmyadmin`)
2. Create a new database named `ecommerce`
3. Import the `database.sql` file into the `ecommerce` database

### Step 3: Configure Database Connection
Update `config/database.php` with your MySQL credentials if needed:
```php
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';  // Default XAMPP username
$password = '';      // Default XAMPP password (empty)
```

### Step 4: Set Permissions
Ensure the `uploads/` directory is writable by the web server:
- **Windows**: Right-click > Properties > Security > Allow Full Control
- **Linux/Mac**: `chmod 755 uploads/` or `chmod 777 uploads/` (for development)

### Step 5: Start the Web Server
- **XAMPP**: Start Apache and MySQL from the XAMPP Control Panel
- **WAMP**: Start all services from the WAMP Manager
- **LAMP**: Start Apache and MySQL services

### Step 6: Access the Website
- **Home Page**: `http://localhost/ecommerce/`
- **Customer Login**: `http://localhost/ecommerce/customer/login.php`
- **Admin Login**: `http://localhost/ecommerce/admin/login.php`

---

## Default Credentials

### Admin Account
- **Email**: `admin@ecommerce.com`
- **Password**: `admin123`

### Customer Account
- **Email**: `customer@ecommerce.com`
- **Password**: `customer123`

You can also register a new customer account from the signup page!

---

## Usage Guide

### For Customers
1. Register or login to your account
2. Browse products on the home page or products page
3. Add products to your cart
4. View and update your cart
5. Proceed to checkout to place an order (Cash on Delivery)
6. View your order history and status in "My Orders"
7. Update your profile in "My Profile"

### For Admins
1. Login to the admin panel using the admin credentials
2. View dashboard statistics
3. Manage products (add, edit, delete)
4. Manage orders (update order status)

---

## Security Considerations

### Current Security Measures
- Password hashing using bcrypt
- PDO prepared statements to prevent SQL injection
- Session management
- Role-based access control

### Recommendations for Production
1. **Use HTTPS**: Always use SSL/TLS in production
2. **Environment Variables**: Store database credentials in environment variables instead of hardcoding
3. **CSRF Protection**: Implement CSRF tokens for forms
4. **Input Validation/Sanitization**: Add more robust input validation and sanitization
5. **Rate Limiting**: Prevent brute force attacks on login endpoints
6. **File Upload Security**: Validate and sanitize uploaded files
7. **Error Reporting**: Disable display_errors in production
8. **Session Security**: Increase session security (secure cookies, HTTP only, etc.)

---

## Future Enhancements

### Features to Add
- [ ] Multiple payment gateways (Stripe, PayPal, etc.)
- [ ] Product search and advanced filtering
- [ ] Product reviews and ratings
- [ ] Wishlist functionality
- [ ] Email notifications
- [ ] Coupon/discount system
- [ ] Inventory management with low stock alerts
- [ ] Sales reports and analytics
- [ ] User password reset functionality
- [ ] Image optimization for faster page loads
- [ ] Responsive design improvements
- [ ] API endpoints for mobile app integration

### Technical Improvements
- [ ] Implement MVC (Model-View-Controller) architecture
- [ ] Add unit and integration tests
- [ ] Use a PHP framework (Laravel, Symfony, etc.) for scalability
- [ ] Implement caching for better performance
- [ ] Add logging system
- [ ] Database migrations
