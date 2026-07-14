<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
    <div class="container">
        <a class="navbar-brand" href="<?php 
            $baseUrl = (strpos($_SERVER['PHP_SELF'], '/customer/') !== false || strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : '';
            echo $baseUrl . 'index.php'; 
        ?>">
            <i class="fas fa-store me-2"></i>FashionHub
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php 
                        $productsUrl = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../customer/products.php' : ((strpos($_SERVER['PHP_SELF'], '/customer/') !== false) ? 'products.php' : 'customer/products.php');
                        echo $productsUrl; 
                    ?>">
                        <i class="fas fa-box me-1"></i>Products
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'customer'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Cart
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i><?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="my_orders.php"><i class="fas fa-receipt me-2"></i>My Orders</a></li>
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-edit me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo $baseUrl; ?>logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_products.php"><i class="fas fa-box me-1"></i>Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_orders.php"><i class="fas fa-shopping-basket me-1"></i>Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $baseUrl; ?>logout.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php 
                            $loginUrl = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../customer/login.php' : ((strpos($_SERVER['PHP_SELF'], '/customer/') !== false) ? 'login.php' : 'customer/login.php');
                            echo $loginUrl; 
                        ?>">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php 
                            $signupUrl = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../customer/signup.php' : ((strpos($_SERVER['PHP_SELF'], '/customer/') !== false) ? 'signup.php' : 'customer/signup.php');
                            echo $signupUrl; 
                        ?>">
                            <i class="fas fa-user-plus me-1"></i>Sign Up
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
