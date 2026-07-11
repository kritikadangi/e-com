<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: manage_products.php');
    exit;
}

$product_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product) {
    if ($product['image'] && file_exists('../uploads/' . $product['image'])) {
        unlink('../uploads/' . $product['image']);
    }
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
}

header('Location: manage_products.php');
exit;
?>
