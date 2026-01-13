<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['quiz_title']);
    $category = $conn->real_escape_string($_POST['category']);
    $user_id = $_SESSION['user_id'];

    // Insert the quiz header into the 'quizzes' table
    $sql = "INSERT INTO quizzes (user_id, title, category) VALUES ('$user_id', '$title', '$category')";

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