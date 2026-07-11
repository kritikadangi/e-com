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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h4 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Customer Login</h4>
                </div>
                <div class="card-body p-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                            <input type="email" name="email" class="form-control form-control-lg" required placeholder="Enter your email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" required placeholder="Enter your password">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </form>
                    <div class="mt-4 text-center">
                        <p class="mb-0">Don't have an account? <a href="signup.php" class="text-decoration-none fw-bold" style="color: #667eea;">Sign up here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
