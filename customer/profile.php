<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (empty($name)) {
        $error = 'Name is required.';
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?");
        if ($stmt->execute([$name, $phone, $address, $user_id])) {
            $_SESSION['user_name'] = $name;
            $success = 'Profile updated successfully!';
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = 'Something went wrong.';
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-5 pt-4">
    <h2 class="mb-4 fw-bold">
        <i class="fas fa-user-circle me-2"></i>My Profile
    </h2>
    
    <div class="row justify-content-center fade-in-up">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Edit Profile</h5>
                </div>
                <div class="card-body p-4">
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-user me-2"></i>Full Name</label>
                            <input type="text" name="name" class="form-control form-control-lg" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-envelope me-2"></i>Email Address</label>
                            <input type="email" class="form-control form-control-lg bg-light" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-phone me-2"></i>Phone Number</label>
                            <input type="text" name="phone" class="form-control form-control-lg" value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-home me-2"></i>Address</label>
                            <textarea name="address" class="form-control form-control-lg" rows="4"><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
