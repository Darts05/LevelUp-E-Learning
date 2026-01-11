<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $sql = "INSERT INTO users (full_name, email, password) VALUES ('$name', '$email', '$pass')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration Successful!'); window.location.href='pages/login.html';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>