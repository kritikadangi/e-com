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

<div class="container mt-5 pt-4">
    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
            <a href="cart.php" class="alert-link ms-2">View Cart</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="row fade-in-up">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card p-2">
                <?php $image_url = get_product_image_url($product['image'], '../'); ?>
                <?php if ($image_url): ?>
                    <img src="<?php echo htmlspecialchars($image_url); ?>" class="img-fluid rounded product-detail-img" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 400px;">
                        <i class="fas fa-image text-muted fa-4x"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <span class="badge bg-primary mb-3"><?php echo htmlspecialchars($product['category']); ?></span>
                    <h2 class="fw-bold mb-3"><?php echo htmlspecialchars($product['name']); ?></h2>
                    <h3 class="fw-bold price mb-4">$<?php echo number_format($product['price'], 2); ?></h3>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-box text-muted"></i>
                            <span class="fw-bold">Stock:</span>
                            <?php if ($product['stock'] > 0): ?>
                                <span class="badge bg-success"><?php echo $product['stock']; ?> available</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Out of Stock</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="fw-bold mb-2"><i class="fas fa-info-circle me-2"></i>Description</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
                    </div>
                    
                    <?php if ($product['stock'] > 0): ?>
                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label fw-bold"><i class="fas fa-sort-numeric-up me-2"></i>Quantity</label>
                                <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?php echo $product['stock']; ?>" style="max-width: 200px;">
                            </div>
                            <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-lg w-100" disabled>
                            <i class="fas fa-times-circle me-2"></i>Out of Stock
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
