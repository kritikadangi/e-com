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

<div class="container mt-5">
    <h2 class="mb-4">Products</h2>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                <?php if ($category): ?>
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <div class="col-md-6">
            <form method="GET" class="d-flex">
                <select name="category" class="form-select me-2">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($search): ?>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-secondary">Filter</button>
            </form>
        </div>
    </div>

    <div class="row">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if ($product['image']): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">No Image</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($product['category']); ?></p>
                            <p class="card-text"><?php echo substr(htmlspecialchars($product['description']), 0, 100); ?>...</p>
                            <p class="card-text fw-bold">$<?php echo number_format($product['price'], 2); ?></p>
                            <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No products found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
