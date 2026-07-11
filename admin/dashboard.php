<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$pending_orders = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status = 'Pending'")->fetchColumn();
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</h2>
    
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card dashboard-card bg-gradient-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Products</h5>
                            <h2 class="mb-0"><?php echo $total_products; ?></h2>
                        </div>
                        <div class="display-4 opacity-50">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card dashboard-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Orders</h5>
                            <h2 class="mb-0"><?php echo $total_orders; ?></h2>
                        </div>
                        <div class="display-4 opacity-50">
                            <i class="fas fa-shopping-basket"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card dashboard-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Customers</h5>
                            <h2 class="mb-0"><?php echo $total_users; ?></h2>
                        </div>
                        <div class="display-4 opacity-50">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card dashboard-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Pending Orders</h5>
                            <h2 class="mb-0"><?php echo $pending_orders; ?></h2>
                        </div>
                        <div class="display-4 opacity-50">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: white;">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="add_product.php" class="btn btn-primary mb-2 w-100"><i class="fas fa-plus-circle me-2"></i>Add Product</a>
                    <a href="manage_products.php" class="btn btn-secondary mb-2 w-100"><i class="fas fa-boxes me-2"></i>Manage Products</a>
                    <a href="manage_orders.php" class="btn btn-info w-100" style="color: white;"><i class="fas fa-list me-2"></i>Manage Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
