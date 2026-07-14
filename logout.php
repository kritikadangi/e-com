<?php
require_once 'config/database.php';
$role = $_SESSION['role'] ?? null;
session_destroy();

if ($role === 'admin') {
    header('Location: admin/login.php');
} else {
    header('Location: index.php');
}
exit;
?>
