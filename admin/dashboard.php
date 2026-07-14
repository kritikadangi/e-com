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

<div class="container mt-5 pt-4">
    <h2 class="mb-4 fw-bold">
        <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
    </h2>
    
    <div class="row mb-4">
        <div class="col-md-3 mb-4 fade-in-up" style="animation-delay: 0s;">
            <div class="card dashboard-card" style="background: var(--primary-gradient); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1 opacity-90"><i class="fas fa-box me-2"></i>Products</h5>
                            <h2 class="mb-0 fw-bold"><?php echo $total_products; ?></h2>
                        </div>
                        <div class="display-3 opacity-30">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 fade-in-up" style="animation-delay: 0.1s;">
            <div class="card dashboard-card" style="background: var(--success-gradient); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1 opacity-90"><i class="fas fa-shopping-basket me-2"></i>Orders</h5>
                            <h2 class="mb-0 fw-bold"><?php echo $total_orders; ?></h2>
                        </div>
                        <div class="display-3 opacity-30">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 fade-in-up" style="animation-delay: 0.2s;">
            <div class="card dashboard-card" style="background: var(--danger-gradient); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1 opacity-90"><i class="fas fa-users me-2"></i>Customers</h5>
                            <h2 class="mb-0 fw-bold"><?php echo $total_users; ?></h2>
                        </div>
                        <div class="display-3 opacity-30">
                            <i class="fas fa-user-friends"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 fade-in-up" style="animation-delay: 0.3s;">
            <div class="card dashboard-card" style="background: var(--warning-gradient); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1 opacity-90"><i class="fas fa-hourglass-half me-2"></i>Pending Orders</h5>
                            <h2 class="mb-0 fw-bold"><?php echo $pending_orders; ?></h2>
                        </div>
                        <div class="display-3 opacity-30">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row fade-in-up" style="animation-delay: 0.4s;">
        <div class="col-md-8 mb-4">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-secondary text-white">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="add_product.php" class="btn btn-primary w-100 py-3 fw-bold">
                                <i class="fas fa-plus-circle mb-2 fa-2x d-block"></i>Add Product
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="manage_products.php" class="btn btn-secondary w-100 py-3 fw-bold">
                                <i class="fas fa-boxes mb-2 fa-2x d-block"></i>Manage Products
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="manage_orders.php" class="btn btn-info w-100 py-3 fw-bold" style="color: white;">
                                <i class="fas fa-list mb-2 fa-2x d-block"></i>Manage Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
