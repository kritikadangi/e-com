<?php require_once 'config/database.php';

$stmt = $pdo->query("SELECT * FROM products WHERE stock > 0 ORDER BY created_at DESC LIMIT 6");
$featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories_stmt = $pdo->query("SELECT DISTINCT category FROM products LIMIT 6");
$categories = $categories_stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="hero-section">
    <div class="container text-center fade-in-up">
        <h1><i class="fas fa-shopping-bag me-3"></i>Welcome to FashionHub</h1>
        <p class="lead mb-4">Discover amazing products at unbeatable prices</p>
        <a href="customer/products.php" class="btn btn-light btn-lg">
            <i class="fas fa-store me-2"></i>Start Shopping
        </a>
    </div>
</div>

<div class="container mt-5 pt-4">
    <h2 class="mb-4 fw-bold">
        <i class="fas fa-star text-warning me-2"></i>Featured Products
    </h2>
    <div class="row">
        <?php if (count($featured_products) > 0): ?>
            <?php foreach ($featured_products as $index => $product): ?>
                <div class="col-md-4 mb-4 fade-in-up" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                    <div class="card product-card h-100">
                        <?php $image_url = get_product_image_url($product['image'], ''); ?>
                        <?php if ($image_url): ?>
                            <img src="<?php echo htmlspecialchars($image_url); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 220px;">
                                <i class="fas fa-image text-muted fa-3x"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <span class="badge bg-primary mb-2"><?php echo htmlspecialchars($product['category']); ?></span>
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="price mb-3">NPR. <?php echo number_format($product['price'], 2); ?></p>
                            <a href="customer/product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary w-100">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-box-open text-muted fa-4x mb-3"></i>
                <p class="text-muted">No products available yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (count($categories) > 0): ?>
<div class="container mt-5 mb-5">
    <h2 class="mb-4 fw-bold">
        <i class="fas fa-tags me-2"></i>Shop by Category
    </h2>
    <div class="row">
        <?php foreach ($categories as $index => $category): ?>
            <div class="col-md-4 col-lg-2 mb-3 fade-in-up" style="animation-delay: <?php echo $index * 0.08; ?>s;">
                <a href="customer/products.php?category=<?php echo urlencode($category); ?>" class="btn btn-outline-primary w-100 py-3">
                    <i class="fas fa-folder-open me-2"></i><?php echo htmlspecialchars($category); ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
