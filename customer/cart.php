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

<div class="container mt-5">
    <h2 class="mb-4">Shopping Cart</h2>
    
    <?php if (count($cart_items) > 0): ?>
        <form method="POST">
            <div class="table-responsive">
                <table class="table">
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
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($item['image']): ?>
                                            <img src="../uploads/<?php echo htmlspecialchars($item['image']); ?>" style="width: 50px; height: 50px; object-fit: cover;" class="me-3">
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <input type="number" name="quantity[<?php echo $item['id']; ?>]" class="form-control" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" style="width: 100px;">
                                </td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                    <button type="submit" name="remove_item" value="1" class="btn btn-danger btn-sm">Remove</button>
                                    <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="submit" name="update_cart" class="btn btn-secondary">Update Cart</button>
                <div>
                    <h4>Total: $<?php echo number_format($total, 2); ?></h4>
                    <a href="checkout.php" class="btn btn-primary mt-2">Proceed to Checkout</a>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="text-center">
            <p class="text-muted">Your cart is empty.</p>
            <a href="products.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
