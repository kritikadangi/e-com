<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($cart_items) == 0) {
    header('Location: cart.php');
    exit;
}

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, payment_method, order_status) VALUES (?, ?, 'Cash on Delivery', 'Pending')");
        $stmt->execute([$user_id, $total]);
        $order_id = $pdo->lastInsertId();

        foreach ($cart_items as $item) {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
            
            $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $stmt->execute([$item['quantity'], $item['product_id']]);
        }

        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);

        $pdo->commit();
        $success = 'Order placed successfully!';
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = 'Something went wrong. Please try again.';
    }
}
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5 pt-4">
    <?php if ($success): ?>
        <div class="card fade-in-up">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-circle text-success fa-5x mb-4"></i>
                <h2 class="fw-bold mb-3">Order Placed Successfully!</h2>
                <p class="text-muted mb-4">Your order has been placed and is being processed</p>
                <a href="my_orders.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-receipt me-2"></i>View My Orders</a>
            </div>
        </div>
    <?php else: ?>
        <h2 class="mb-4 fw-bold">
            <i class="fas fa-credit-card me-2"></i>Checkout
        </h2>
        
        <div class="row fade-in-up">
            <div class="col-md-8 mb-4 mb-md-0">
                <div class="card">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background: #f8f9fa;">
                                <div class="fw-bold"><?php echo htmlspecialchars($item['name']); ?> <span class="text-muted">x <?php echo $item['quantity']; ?></span></div>
                                <div class="fw-bold">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="fw-bold">Total</h4>
                            <h3 class="price">$<?php echo number_format($total, 2); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-gradient-success text-white">
                        <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4 p-3 rounded" style="background: #f8f9fa;">
                            <div class="d-flex align-items-center">
                            <i class="fas fa-hand-holding-usd text-success fa-2x me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Cash on Delivery</h6>
                                <p class="text-muted small mb-0">Pay when you receive your order</p>
                            </div>
                        </div>
                    </div>
                    <form method="POST">
                        <div class="px-3 pb-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-check-circle me-2"></i>Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
