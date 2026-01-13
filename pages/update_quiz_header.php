<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_id = $conn->real_escape_string($_POST['quiz_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);

    // Update the title and category in the quizzes table
    $sql = "UPDATE quizzes SET title = '$title', category = '$category' WHERE id = $quiz_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the edit page with a success message
        header("Location: edit_quiz.php?id=" . $quiz_id . "&msg=updated");
        exit();
    } else {
        echo "Error updating quiz: " . $conn->error;
    }
} else {
    // Redirect if they try to access this file without a POST request
    header("Location: my_quizzes.php");
    exit();
}
?>