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

<div class="container mt-5 pt-4">
    <h2 class="mb-4 fw-bold">
        <i class="fas fa-receipt me-2"></i>My Orders
    </h2>
    
    <?php if (count($orders) > 0): ?>
        <div class="accordion" id="ordersAccordion">
            <?php foreach ($orders as $index => $order): ?>
                <div class="accordion-item mb-3 border-0 shadow-sm rounded fade-in-up" style="animation-delay: <?php echo $index * 0.08; ?>s;">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#order<?php echo $order['id']; ?>">
                            <i class="fas fa-box-open me-2 text-primary"></i>
                            Order #<?php echo $order['id']; ?> - <?php echo date('F j, Y', strtotime($order['order_date'])); ?>
                            <span class="ms-auto badge bg-<?php echo $order['order_status'] == 'Delivered' ? 'success' : ($order['order_status'] == 'Cancelled' ? 'danger' : 'warning'); ?> rounded-pill px-3 py-2">
                                <i class="fas fa-<?php echo $order['order_status'] == 'Delivered' ? 'check-circle' : ($order['order_status'] == 'Cancelled' ? 'times-circle' : 'clock'); ?> me-1"></i>
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
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover mb-0">
                                            <thead class="table-light">
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
                                                                    <img src="../uploads/<?php echo htmlspecialchars($item['image']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" class="me-3" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                                <?php else: ?>
                                                                    <div class="bg-light me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 8px;">
                                                                        <i class="fas fa-image text-muted"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <span class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></span>
                                                            </div>
                                                        </td>
                                                        <td class="fw-bold">$<?php echo number_format($item['price'], 2); ?></td>
                                                        <td class="fw-bold"><?php echo $item['quantity']; ?></td>
                                                        <td class="fw-bold">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <h4 class="fw-bold mb-0">
                                            Order Total: <span class="price">$<?php echo number_format($order['total_amount'], 2); ?></span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5 fade-in-up">
            <i class="fas fa-receipt text-muted fa-5x mb-4"></i>
            <h3 class="text-muted mb-3">You have no orders yet</h3>
            <p class="text-muted mb-4">Start shopping to place your first order!</p>
            <a href="products.php" class="btn btn-primary btn-lg">
                <i class="fas fa-store me-2"></i>Start Shopping
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
