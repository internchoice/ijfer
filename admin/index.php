<?php
require_once "config/db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

// If already logged in → go to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            // Secure session
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // Redirect to Dashboard
            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Invalid Email or Password!";
        }

    } else {
        $error = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>IJFER Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Professional Admin CSS -->
    <link rel="stylesheet" href="assets/css/admin-professional.css">
</head>

<body class="login-container">

<div class="login-card">
    <div class="login-header">
        <h3>
            <i class="fas fa-user-lock me-2"></i>
            IJFER Admin Login
        </h3>
        <p class="mb-0">Access your administrative dashboard</p>
    </div>
    
    <div class="login-body">
        <?php if($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" name="email" class="form-control" required placeholder="Enter your email">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>
                Login to Dashboard
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="mb-0">
                <a href="../index.php" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Website
                </a>
            </p>
        </div>
    </div>
</div>

</body>
</html>