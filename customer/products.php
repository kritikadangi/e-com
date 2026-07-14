<?php
require_once '../config/database.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

$query = "SELECT * FROM products WHERE stock > 0";
$params = [];

if ($search) {
    $query .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category) {
    $query .= " AND category = ?";
    $params[] = $category;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories_stmt = $pdo->query("SELECT DISTINCT category FROM products");
$categories = $categories_stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5 pt-4">
    <h2 class="mb-4 fw-bold">
        <i class="fas fa-boxes me-2"></i>All Products
    </h2>
    
    <div class="card mb-4 p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <form method="GET" class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                    </div>
                    <?php if ($category): ?>
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                </form>
            </div>
            <div class="col-md-6">
                <form method="GET" class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-filter text-muted"></i></span>
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php if ($search): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                    <?php endif; ?>
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-sliders-h me-1"></i>Filter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $index => $product): ?>
                <div class="col-md-4 mb-4 fade-in-up" style="animation-delay: <?php echo $index * 0.08; ?>s;">
                    <div class="card product-card h-100">
                        <?php $image_url = get_product_image_url($product['image'], '../'); ?>
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
                            <p class="card-text text-muted small mb-2"><?php echo substr(htmlspecialchars($product['description']), 0, 80); ?>...</p>
                            <p class="price mb-3">$<?php echo number_format($product['price'], 2); ?></p>
                            <div class="d-grid gap-2">
                                <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-search-minus text-muted fa-4x mb-3"></i>
                <h4 class="text-muted">No products found</h4>
                <p class="text-muted">Try adjusting your search or filter criteria</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
