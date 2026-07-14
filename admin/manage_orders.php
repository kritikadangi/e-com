<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
}

$stmt = $pdo->query("SELECT o.*, u.name as customer_name, u.email as customer_email FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.order_date DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5 pt-4">
    <h2 class="mb-4 fw-bold"><i class="fas fa-shopping-basket me-2"></i>Manage Orders</h2>
    
    <?php if (count($orders) > 0): ?>
        <div class="accordion" id="ordersAccordion">
            <?php foreach ($orders as $index => $order): ?>
                <div class="accordion-item mb-3 border-0 shadow-sm rounded fade-in-up" style="animation-delay: <?php echo $index * 0.08; ?>s;">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-bold bg-light" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#order<?php echo $order['id']; ?>">
                            <i class="fas fa-receipt me-2 text-primary"></i>
                            Order #<?php echo $order['id']; ?> - <?php echo date('F j, Y H:i', strtotime($order['order_date'])); ?>
                            <span class="ms-auto badge rounded-pill bg-<?php 
                                echo $order['order_status'] == 'Delivered' ? 'success' : 
                                    ($order['order_status'] == 'Cancelled' ? 'danger' : 
                                        ($order['order_status'] == 'Processing' ? 'info' : 'warning')); ?> px-3 py-2">
                                <i class="fas fa-<?php 
                                    echo $order['order_status'] == 'Delivered' ? 'check-circle' : 
                                        ($order['order_status'] == 'Cancelled' ? 'times-circle' : 
                                            ($order['order_status'] == 'Processing' ? 'cogs' : 'clock')); ?> me-1"></i>
                                <?php echo htmlspecialchars($order['order_status']); ?>
                            </span>
                        </button>
                    </h2>
                    <div id="order<?php echo $order['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                        <div class="accordion-body">
                            <div class="mb-4 p-3 bg-light rounded">
                                <h6 class="fw-bold mb-2">
                                    <i class="fas fa-user-circle me-2 text-primary"></i>Customer: <?php echo htmlspecialchars($order['customer_name']); ?>
                                </h6>
                                <p class="mb-0"><i class="fas fa-envelope me-2 text-muted"></i><?php echo htmlspecialchars($order['customer_email']); ?></p>
                            </div>
                            <?php
                            $stmt = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                            $stmt->execute([$order['id']]);
                            $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="card mb-4 border-0">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th class="fw-bold">Product</th>
                                                    <th class="fw-bold">Price</th>
                                                    <th class="fw-bold">Quantity</th>
                                                    <th class="fw-bold">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($order_items as $item): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <?php if ($item['image']): ?>
                                                                    <img src="../uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" 
                                                                         class="me-3">
                                                                <?php else: ?>
                                                                    <div class="bg-light me-3 d-flex align-items-center justify-content-center" 
                                                                         style="width: 50px; height: 50px; border-radius: 8px;">
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
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                <h4 class="fw-bold mb-0">
                                    <i class="fas fa-calculator me-2"></i>Total: <span class="text-primary">$<?php echo number_format($order['total_amount'], 2); ?></span>
                                </h4>
                                <form method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="status" class="form-select" style="min-width: 160px;">
                                        <option value="Pending" <?php echo $order['order_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Approved" <?php echo $order['order_status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                        <option value="Processing" <?php echo $order['order_status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="Delivered" <?php echo $order['order_status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="Cancelled" <?php echo $order['order_status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-primary">
                                        <i class="fas fa-sync-alt me-1"></i>Update Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5 fade-in-up">
            <i class="fas fa-shopping-basket text-muted fa-5x mb-4"></i>
            <h3 class="text-muted mb-3">No orders yet</h3>
            <p class="text-muted">No orders have been placed yet.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
