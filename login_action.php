<?php
session_start();
include 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Fetch user by email only (we verify password later)
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // 2. Verify Password (Works for hashed passwords)
        // Note: If you currently have plain text passwords in your DB, 
        // use: if ($password == $user['password'])
        // But it is highly recommended to use: password_verify($password, $user['password'])
        if (password_verify($password, $user['password']) || $password == $user['password']) {
            
            // 3. FIX: Storing all necessary data in Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role']; // <--- THIS FIXES THE UNDEFINED INDEX ERROR
            $_SESSION['show_welcome'] = true; 

            header("Location: index.php"); 
            exit();
        } else {
            // Password incorrect
            header("Location: login.php?error=invalid");
            exit();
        }
    } else {
        // User not found
        header("Location: login.php?error=invalid");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>