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

<div class="container mt-5">
    <h2 class="mb-4">Edit Product</h2>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category *</label>
                            <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($product['category']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price *</label>
                            <input type="number" name="price" class="form-control" step="0.01" value="<?php echo $product['price']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stock *</label>
                            <input type="number" name="stock" class="form-control" value="<?php echo $product['stock']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <?php if ($product['image']): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" style="width: 100px; height: 100px; object-fit: cover;">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Image (optional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                        <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
