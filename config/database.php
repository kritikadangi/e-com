<?php
// Set custom session save path
$sessionPath = __DIR__ . '/../sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}
session_save_path($sessionPath);

// Error reporting configuration
error_reporting(E_ALL);
ini_set('display_errors', 1); // Set to 0 in production
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error.log');

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("<div style='padding:20px;background:#fee;color:#c33;border:1px solid #fcc;border-radius:5px;max-width:600px;margin:50px auto;'>
            <h3>Database Connection Error</h3>
            <p>" . htmlspecialchars($e->getMessage()) . "</p>
         </div>");
}
?>
