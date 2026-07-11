<?php
require_once 'config/database.php';

$stmt = $pdo->query("SELECT * FROM products WHERE stock > 0 ORDER BY created_at DESC LIMIT 6");
$featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories_stmt = $pdo->query("SELECT DISTINCT category FROM products LIMIT 6");
$categories = $categories_stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="hero-section">
    <div class="container text-center">
        <h1><i class="fas fa-shopping-bag me-2"></i>Welcome to E-Shop</h1>
        <p class="lead">Your one-stop shop for amazing products</p>
        <a href="customer/products.php" class="btn btn-light btn-lg">
            <i class="fas fa-store me-2"></i>Shop Now
        </a>
    </div>
</div>

<div class="container mt-5">
    <h2 class="mb-4">Featured Products</h2>
    <div class="row">
        <?php if (count($featured_products) > 0): ?>
            <?php foreach ($featured_products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if ($product['image']): ?>
                            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">No Image</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($product['category']); ?></p>
                            <p class="card-text fw-bold">$<?php echo number_format($product['price'], 2); ?></p>
                            <a href="customer/product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No products available yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (count($categories) > 0): ?>
<div class="container mt-5 mb-5">
    <h2 class="mb-4">Shop by Category</h2>
    <div class="row">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-2 mb-3">
                <a href="customer/products.php?category=<?php echo urlencode($category); ?>" class="btn btn-outline-primary w-100">
                    <?php echo htmlspecialchars($category); ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
