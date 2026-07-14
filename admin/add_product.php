<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
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
    $image = '';

    if (empty($name) || empty($category) || empty($price)) {
        $error = 'Please fill in all required fields.';
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $image = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image);
            } else {
                $error = 'Invalid file type. Only JPG, PNG, and GIF allowed.';
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("INSERT INTO products (name, category, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $category, $description, $price, $stock, $image])) {
                $success = 'Product added successfully!';
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
    <h2 class="mb-4 fw-bold"><i class="fas fa-plus-circle me-2"></i>Add Product</h2>
    
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
                            <input type="text" name="name" class="form-control form-control-lg" required>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fas fa-folder me-2"></i>Category *</label>
                                <select name="category" class="form-select form-control-lg" required>
                                    <option value="Women">Women</option>
                                    <option value="Men">Men</option>
                                    <option value="Child">Child</option>
                                    <option value="Accessories">Accessories</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold"><i class="fas fa-dollar-sign me-2"></i>Price *</label>
                                <input type="number" name="price" class="form-control form-control-lg" step="0.01" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold"><i class="fas fa-boxes me-2"></i>Stock *</label>
                                <input type="number" name="stock" class="form-control form-control-lg" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-align-left me-2"></i>Description</label>
                            <textarea name="description" class="form-control form-control-lg" rows="4"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-image me-2"></i>Product Image</label>
                            <input type="file" name="image" class="form-control form-control-lg" accept="image/*">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="fas fa-plus-circle me-2"></i>Add Product
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
