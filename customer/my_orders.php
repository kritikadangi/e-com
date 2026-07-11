<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">My Orders</h2>
    
    <?php if (count($orders) > 0): ?>
        <div class="accordion" id="ordersAccordion">
            <?php foreach ($orders as $order): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#order<?php echo $order['id']; ?>">
                            Order #<?php echo $order['id']; ?> - <?php echo date('F j, Y', strtotime($order['order_date'])); ?>
                            <span class="ms-auto badge bg-<?php echo $order['order_status'] == 'Delivered' ? 'success' : ($order['order_status'] == 'Cancelled' ? 'danger' : 'warning'); ?>">
                                <?php echo htmlspecialchars($order['order_status']); ?>
                            </span>
                        </button>
                    </h2>
                    <div id="order<?php echo $order['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                        <div class="accordion-body">
                            <?php
                            $stmt = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                            $stmt->execute([$order['id']]);
                            $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($order_items as $item): ?>
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
                                                <td><?php echo $item['quantity']; ?></td>
                                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                <h5>Total: $<?php echo number_format($order['total_amount'], 2); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center">
            <p class="text-muted">You have no orders yet.</p>
            <a href="products.php" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
