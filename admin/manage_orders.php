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

<div class="container mt-5">
    <h2 class="mb-4">Manage Orders</h2>
    
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
                            <div class="mb-3">
                                <h6>Customer: <?php echo htmlspecialchars($order['customer_name']); ?> (<?php echo htmlspecialchars($order['customer_email']); ?>)</h6>
                            </div>
                            <?php
                            $stmt = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                            $stmt->execute([$order['id']]);
                            $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="table-responsive mb-3">
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
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total: $<?php echo number_format($order['total_amount'], 2); ?></h5>
                                <form method="POST" class="d-flex">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="status" class="form-select me-2" style="width: auto;">
                                        <option value="Pending" <?php echo $order['order_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Approved" <?php echo $order['order_status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                        <option value="Processing" <?php echo $order['order_status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="Delivered" <?php echo $order['order_status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="Cancelled" <?php echo $order['order_status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center">
            <p class="text-muted">No orders yet.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
