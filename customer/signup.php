<?php
require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = 'Email already registered.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, 'customer')");
            if ($stmt->execute([$name, $email, $hashed_password, $phone, $address])) {
                $success = 'Registration successful! You can now login.';
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5 pt-4 mb-5">
    <div class="row justify-content-center fade-in-up">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center bg-gradient-primary text-white py-4">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2"></i>Create Account</h4>
                </div>
                <div class="card-body p-5">
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-user me-2 text-primary"></i>Full Name *</label>
                            <input type="text" name="name" class="form-control form-control-lg" required placeholder="Enter your full name">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-envelope me-2 text-primary"></i>Email Address *</label>
                            <input type="email" name="email" class="form-control form-control-lg" required placeholder="Enter your email">
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fas fa-lock me-2 text-primary"></i>Password *</label>
                                <input type="password" name="password" class="form-control form-control-lg" required placeholder="Enter your password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fas fa-lock me-2 text-primary"></i>Confirm Password *</label>
                                <input type="password" name="confirm_password" class="form-control form-control-lg" required placeholder="Confirm your password">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-phone me-2 text-primary"></i>Phone Number</label>
                            <input type="text" name="phone" class="form-control form-control-lg" placeholder="Enter your phone number">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-home me-2 text-primary"></i>Address</label>
                            <textarea name="address" class="form-control form-control-lg" rows="3" placeholder="Enter your address"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>
                    </form>
                    <div class="text-center mt-4">
                        <p class="mb-0 text-muted">
                            Already have an account? 
                            <a href="login.php" class="text-decoration-none fw-bold text-primary">Sign in here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
