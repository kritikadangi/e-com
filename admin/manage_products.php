<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5 pt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0"><i class="fas fa-boxes me-2"></i>Manage Products</h2>
        <a href="add_product.php" class="btn btn-primary btn-lg">
            <i class="fas fa-plus-circle me-2"></i>Add Product
        </a>
    </div>
    
    <div class="card shadow-lg fade-in-up">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="fw-bold">ID</th>
                            <th class="fw-bold">Image</th>
                            <th class="fw-bold">Name</th>
                            <th class="fw-bold">Category</th>
                            <th class="fw-bold">Price</th>
                            <th class="fw-bold">Stock</th>
                            <th class="fw-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $index => $product): ?>
                            <tr class="fade-in-up" style="animation-delay: <?php echo $index * 0.05; ?>s;">
                                <td class="fw-bold"><?php echo $product['id']; ?></td>
                                <td>
                                    <?php $image_url = get_product_image_url($product['image'], '../'); ?>
                                    <?php if ($image_url): ?>
                                        <img src="<?php echo htmlspecialchars($image_url); ?>" 
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                    <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px; border-radius: 8px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold"><?php echo htmlspecialchars($product['name']); ?></td>
                                <td>
                                    <span class="badge bg-primary"><?php echo htmlspecialchars($product['category']); ?></span>
                                </td>
                                <td class="fw-bold">$<?php echo number_format($product['price'], 2); ?></td>
                                <td>
                                    <span class="badge <?php echo $product['stock'] > 10 ? 'bg-success' : ($product['stock'] > 0 ? 'bg-warning' : 'bg-danger'); ?>">
                                        <?php echo $product['stock']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary me-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
