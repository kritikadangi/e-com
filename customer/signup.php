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

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg">
                <div class="card-header text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Create Account</h4>
                </div>
                <div class="card-body p-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-user me-2"></i>Full Name *</label>
                            <input type="text" name="name" class="form-control form-control-lg" required placeholder="Enter your full name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-envelope me-2"></i>Email *</label>
                            <input type="email" name="email" class="form-control form-control-lg" required placeholder="Enter your email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-lock me-2"></i>Password *</label>
                            <input type="password" name="password" class="form-control form-control-lg" required placeholder="Enter your password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-lock me-2"></i>Confirm Password *</label>
                            <input type="password" name="confirm_password" class="form-control form-control-lg" required placeholder="Confirm your password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-phone me-2"></i>Phone</label>
                            <input type="text" name="phone" class="form-control form-control-lg" placeholder="Enter your phone number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-home me-2"></i>Address</label>
                            <textarea name="address" class="form-control form-control-lg" rows="3" placeholder="Enter your address"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-user-plus me-2"></i>Sign Up
                        </button>
                    </form>
                    <div class="mt-4 text-center">
                        <p class="mb-0">Already have an account? <a href="login.php" class="text-decoration-none fw-bold" style="color: #667eea;">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
