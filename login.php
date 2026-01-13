<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | LevelUp</title>
    <link rel="stylesheet" href="css/style.css"> </head>
<body>
    <div class="login-container">
        <div class="login-sidebar">
            <div class="sidebar-content">
                <img src="assets/LevelUp-Logo.png" alt="Logo" class="login-logo">
                <h1>LevelUp</h1>
                <p>Knowledge grows when shared. Ready to test yourself?</p>
            </div>
        </div>

        <div class="login-form-section">
            <form class="login-form" action="login_action.php" method="POST">
                <h2>Welcome Back</h2>

                <?php if(isset($_GET['error'])): ?>
                    <div style="background: #fee2e2; color: #dc2626; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #fecaca;">
                        Invalid Email or Password.
                    </div>
                <?php endif; ?>

                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="name@example.com" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="login-btn">Sign In</button>
                <p class="signup-text">Don't have an account? <a href="signup.php">Sign up</a></p>
            </form>
        </div>
    </div>
</body>
</html>