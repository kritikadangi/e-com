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
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: manage_products.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image = $product['image'];

    if (empty($name) || empty($category) || empty($price)) {
        $error = 'Please fill in all required fields.';
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                if ($product['image'] && file_exists('../uploads/' . $product['image'])) {
                    unlink('../uploads/' . $product['image']);
                }
                $image = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image);
            } else {
                $error = 'Invalid file type. Only JPG, PNG, and GIF allowed.';
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, category = ?, description = ?, price = ?, stock = ?, image = ? WHERE id = ?");
            if ($stmt->execute([$name, $category, $description, $price, $stock, $image, $product_id])) {
                $success = 'Product updated successfully!';
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$product_id]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error = 'Something went wrong.';
            }
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5 pt-4">
    <h2 class="mb-4 fw-bold"><i class="fas fa-edit me-2"></i>Edit Product</h2>
    
    <div class="row justify-content-center fade-in-up">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0 fw-bold">Product Details</h5>
                </div>
                <div class="card-body p-4">
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-tag me-2"></i>Product Name *</label>
                            <input type="text" name="name" class="form-control form-control-lg" 
                                   value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fas fa-folder me-2"></i>Category *</label>
                                <select name="category" class="form-select form-control-lg" required>
                                    <option value="Women" <?php echo $product['category'] == 'Women' ? 'selected' : ''; ?>>Women</option>
                                    <option value="Men" <?php echo $product['category'] == 'Men' ? 'selected' : ''; ?>>Men</option>
                                    <option value="Child" <?php echo $product['category'] == 'Child' ? 'selected' : ''; ?>>Child</option>
                                    <option value="Accessories" <?php echo $product['category'] == 'Accessories' ? 'selected' : ''; ?>>Accessories</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold"><i class="fas fa-indian-rupee-sign me-2"></i>Price *</label>
                                <input type="number" name="price" class="form-control form-control-lg" step="0.01" 
                                       value="<?php echo $product['price']; ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold"><i class="fas fa-boxes me-2"></i>Stock *</label>
                                <input type="number" name="stock" class="form-control form-control-lg" 
                                       value="<?php echo $product['stock']; ?>" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-align-left me-2"></i>Description</label>
                            <textarea name="description" class="form-control form-control-lg" rows="4">
<?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-image me-2"></i>Current Image</label>
                            <div class="mt-2">
                                <?php if ($product['image']): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                                         style="width: 120px; height: 120px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                <?php else: ?>
                                    <div class="bg-light d-inline-flex align-items-center justify-content-center" 
                                         style="width: 120px; height: 120px; border-radius: 12px;">
                                        <i class="fas fa-image text-muted fa-2x"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-camera me-2"></i>New Image (optional)</label>
                            <input type="file" name="image" class="form-control form-control-lg" accept="image/*">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="fas fa-save me-2"></i>Update Product
                            </button>
                            <a href="manage_products.php" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
