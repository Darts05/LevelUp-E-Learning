<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = intval($_POST['role']);
    
    $pass = $conn->real_escape_string($_POST['password']);

    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo "<script>alert('This email is already registered!'); window.location.href='signup.php';</script>";
        exit();
    } else {
        $sql = "INSERT INTO users (full_name, email, password, role) VALUES ('$name', '$email', '$pass', '$role')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registration Successful! Please Login.'); window.location.href='login.php';</script>";
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up | LevelUp</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-sidebar" style="background: #4CAF50;">
            <div class="sidebar-content">
                <img src="assets/LevelUp-Logo.png" alt="Logo" class="login-logo">
                <h1>Join LevelUp</h1>
                <p>Start your journey today and track your progress.</p>
            </div>
        </div>

        <div class="login-form-section">
            <form class="login-form" action="signup.php" method="POST">
                <h2>Create Account</h2>

                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" placeholder="Enter your name" required>
                </div>

                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="name@example.com" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="input-group">
                    <label for="role">Account Type</label>
                    <select name="role" id="role" required>
                        <option value="0">Student</option>
                        <option value="1">Teacher</option>
                    </select>
                </div>
    
                <button type="submit" class="login-btn" style="background: #4CAF50;">Register</button>
                
                <p class="signup-text">Already have an account? <a href="login.php">Login here</a></p>
            </form>
        </div>
    </div>
</body>
</html>