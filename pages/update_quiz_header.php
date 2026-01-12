<?php
session_start();
include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_id = $_POST['quiz_id'];
    $title = $_POST['title'];
    $category = $_POST['category'];

    // Update the title and category in the quizzes table
    $sql = "UPDATE quizzes SET title = '$title', category = '$category' WHERE id = $quiz_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the edit page with a success message
        header("Location: edit_quiz.php?id=" . $quiz_id . "&msg=updated");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>