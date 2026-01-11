<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo "<script>alert('This email is already registered! Please use another.'); window.location.href='pages/signup.html';</script>";
    } else {
        $sql = "INSERT INTO users (full_name, email, password) VALUES ('$name', '$email', '$pass')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registration Successful!'); window.location.href='pages/login.html';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>