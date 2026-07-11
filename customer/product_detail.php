<?php
require_once '../config/database.php';

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$product_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: products.php');
    exit;
}

$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $quantity = intval($_POST['quantity']);
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $new_quantity = $existing['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$new_quantity, $existing['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);
    }

    $success = 'Product added to cart!';
}
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-6">
            <?php if ($product['image']): ?>
                <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid rounded">
            <?php else: ?>
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" style="height: 400px;">No Image</div>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p class="text-muted"><?php echo htmlspecialchars($product['category']); ?></p>
            <h3 class="text-primary">$<?php echo number_format($product['price'], 2); ?></h3>
            <p class="mb-3">Stock: <?php echo $product['stock']; ?></p>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            
            <?php if ($product['stock'] > 0): ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?php echo $product['stock']; ?>" style="width: 150px;">
                    </div>
                    <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg">Add to Cart</button>
                </form>
            <?php else: ?>
                <button class="btn btn-secondary btn-lg" disabled>Out of Stock</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
