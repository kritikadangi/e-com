<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $cart_id => $quantity) {
            $quantity = intval($quantity);
            if ($quantity > 0) {
                $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$quantity, $cart_id, $user_id]);
            } else {
                $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
                $stmt->execute([$cart_id, $user_id]);
            }
        }
    } elseif (isset($_POST['remove_item'])) {
        $cart_id = $_POST['cart_id'];
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->execute([$cart_id, $user_id]);
    }
}

$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.image, p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5 pt-4">
    <h2 class="mb-4 fw-bold">
        <i class="fas fa-shopping-cart me-2"></i>Shopping Cart
    </h2>
    
    <?php if (count($cart_items) > 0): ?>
        <form method="POST">
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr class="fade-in-up">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php $image_url = get_product_image_url($item['image'], '../'); ?>
                                                <?php if ($image_url): ?>
                                                    <img src="<?php echo htmlspecialchars($image_url); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" class="me-3" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                <?php else: ?>
                                                    <div class="bg-light me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 8px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <span class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></span>
                                            </div>
                                        </td>
                                        <td class="fw-bold">$<?php echo number_format($item['price'], 2); ?></td>
                                        <td>
                                            <input type="number" name="quantity[<?php echo $item['id']; ?>]" class="form-control" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" style="max-width: 120px;">
                                        </td>
                                        <td class="fw-bold">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                        <td>
                                            <button type="submit" name="remove_item" value="1" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash me-1"></i>Remove
                                            </button>
                                            <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <button type="submit" name="update_cart" class="btn btn-secondary">
                        <i class="fas fa-sync-alt me-2"></i>Update Cart
                    </button>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="fw-bold mb-3">
                                <i class="fas fa-calculator me-2"></i>Order Summary
                            </h4>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold">Total:</span>
                                <span class="fs-3 price">$<?php echo number_format($total, 2); ?></span>
                            </div>
                            <a href="checkout.php" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="text-center py-5 fade-in-up">
            <i class="fas fa-shopping-cart text-muted fa-5x mb-4"></i>
            <h3 class="text-muted mb-3">Your cart is empty</h3>
            <p class="text-muted mb-4">Add some amazing products to your cart!</p>
            <a href="products.php" class="btn btn-primary btn-lg">
                <i class="fas fa-store me-2"></i>Continue Shopping
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
