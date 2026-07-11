# E-Commerce Website

A simple, beginner-friendly e-commerce website built with PHP, MySQL, and Bootstrap 5.

## Features

### Customer Module
- User Registration & Login
- Profile Management
- Browse & Search Products
- Product Details Page
- Shopping Cart (Add/Update/Remove Items)
- Checkout with Cash on Delivery
- Order History & Status Tracking

### Admin Module
- Separate Admin Login
- Dashboard with Statistics
- Product Management (Add, View, Edit, Delete)
- Order Management (Update Order Status)

## Technologies Used
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
- **Backend**: Core PHP
- **Database**: MySQL

## Project Structure
```
Project/
├── admin/                  # Admin panel files
│   ├── login.php
│   ├── dashboard.php
│   ├── manage_products.php
│   ├── add_product.php
│   ├── edit_product.php
│   ├── delete_product.php
│   └── manage_orders.php
├── customer/               # Customer-facing files
│   ├── login.php
│   ├── signup.php
│   ├── products.php
│   ├── product_detail.php
│   ├── cart.php
│   ├── checkout.php
│   ├── my_orders.php
│   └── profile.php
├── assets/                 # Static assets (css, js, images)
│   ├── css/
│   ├── js/
│   └── images/
├── config/                 # Configuration files
│   └── database.php        # Database connection
├── includes/               # Reusable components
│   ├── header.php
│   ├── navbar.php
│   └── footer.php
├── uploads/                # Product images upload directory
├── index.php               # Home page
├── logout.php              # Logout script
└── database.sql            # Database schema
```

## Installation & Setup

### Prerequisites
- Web Server (Apache/Nginx)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Recommended: XAMPP/WAMP/LAMP for local development

### Step 1: Download/Clone the Project
Place the project folder in your web server's document root:
- XAMPP: `C:\xampp\htdocs\`
- WAMP: `C:\wamp64\www\`
- LAMP: `/var/www/html/`

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
- Windows: Right-click > Properties > Security > Allow Full Control
- Linux/Mac: `chmod 755 uploads/` or `chmod 777 uploads/` (for development)

### Step 5: Start the Web Server
- For XAMPP: Start Apache and MySQL from the XAMPP Control Panel
- For WAMP: Start all services from the WAMP Manager
- For LAMP: Start Apache and MySQL services

### Step 6: Access the Website
- Home Page: `http://localhost/Project/`
- Customer Login: `http://localhost/Project/customer/login.php`
- Admin Login: `http://localhost/Project/admin/login.php`

## Default Credentials

### Admin Account
- Email: `admin@ecommerce.com`
- Password: `admin123`

### Customer Account
You can register a new customer account from the signup page.

## Usage

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

## Important Notes
- This is a beginner-level project and should not be used in production without additional security measures
- Always use HTTPS in production
- Sanitize and validate all user inputs
- Implement additional security features like CSRF protection, rate limiting, etc., for production use
