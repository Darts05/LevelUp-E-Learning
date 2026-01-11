<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Search for the user in the database
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Store user info in the session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];

        echo "<script>alert('Welcome back, " . $user['full_name'] . "!'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Invalid Email or Password!'); window.location.href='pages/login.html';</script>";
    }
}
?>