<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

if (isset($_GET['id']) && isset($_GET['quiz_id'])) {
    $q_id = $_GET['id'];
    $quiz_id = $_GET['quiz_id'];
    $user_id = $_SESSION['user_id'];

    // Security check: Make sure the user owns the quiz this question belongs to
    $check_ownership = "SELECT user_id FROM quizzes WHERE id = $quiz_id";
    $result = $conn->query($check_ownership);
    $row = $result->fetch_assoc();

    if ($row && $row['user_id'] == $user_id) {
        // User owns the quiz, proceed with deleting the question
        $sql = "DELETE FROM questions WHERE id = $q_id AND quiz_id = $quiz_id";

        if ($conn->query($sql) === TRUE) {
            header("Location: edit_quiz.php?id=" . $quiz_id . "&msg=q_deleted");
            exit();
        } else {
            echo "Error deleting question: " . $conn->error;
        }
    } else {
        echo "Unauthorized action.";
    }
} else {
    header("Location: my_quizzes.php");
    exit();
}
?>