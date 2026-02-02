<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['quiz_title']);
    $category = $conn->real_escape_string($_POST['category']);
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO quizzes (user_id, title, category, is_published) 
            VALUES ('$user_id', '$title', '$category', 0)";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        
        header("Location: add_questions.php?quiz_id=" . $last_id);
        exit();
    } else {
        echo "Error creating quiz: " . $conn->error;
    }
} else {
    header("Location: create_quiz.php");
    exit();
}
?>