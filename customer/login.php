<?php
require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'customer'");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            header('Location: products.php');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5 pt-4">
    <div class="row justify-content-center fade-in-up">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center bg-gradient-primary text-white py-4">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-sign-in-alt me-2"></i>Customer Login</h4>
                </div>
                <div class="card-body p-5">
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-envelope me-2 text-primary"></i>Email Address</label>
                            <input type="email" name="email" class="form-control form-control-lg" required placeholder="Enter your email">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-lock me-2 text-primary"></i>Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" required placeholder="Enter your password">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </form>
                    <div class="text-center mt-4">
                        <p class="mb-0 text-muted">
                            Don't have an account? 
                            <a href="signup.php" class="text-decoration-none fw-bold text-primary">Create an account</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
