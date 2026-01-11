<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // We use bin2hex(rewrite) to ensure no hidden characters interfere
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];

        // If index.html is in the same folder as this PHP file:
        echo "<script>alert('Welcome back, " . $user['full_name'] . "!'); window.location.href='index.html';</script>";
    } else {
        // Since we are in the root, we must go INTO 'pages' to find login.html
        echo "<script>alert('Invalid Email or Password!'); window.location.href='pages/login.html';</script>";
    }
}
?>