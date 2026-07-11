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

<div class="container mt-5">
    <?php if ($success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
            <a href="my_orders.php" class="alert-link">View my orders</a>
        </div>
    <?php else: ?>
        <h2 class="mb-4">Checkout</h2>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <div><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></div>
                                <div>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <div>Total</div>
                            <div>$<?php echo number_format($total, 2); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Cash on Delivery</p>
                        <form method="POST">
                            <button type="submit" class="btn btn-primary w-100">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
