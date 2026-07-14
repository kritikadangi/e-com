<?php
require_once 'config/database.php';

$role = $_SESSION['role'] ?? null;

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

if ($role === 'admin') {
    header('Location: admin/login.php');
} else {
    header('Location: index.php');
}
exit;
?>