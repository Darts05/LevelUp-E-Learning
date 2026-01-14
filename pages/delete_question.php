<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

if (isset($_GET['id']) && isset($_GET['quiz_id'])) {
    $q_id = intval($_GET['id']);
    $quiz_id = intval($_GET['quiz_id']);
    $user_id = $_SESSION['user_id'];

    // Security check: Use a prepared statement to check ownership
    $check_stmt = $conn->prepare("SELECT user_id FROM quizzes WHERE id = ?");
    $check_stmt->bind_param("i", $quiz_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && $row['user_id'] == $user_id) {
        // User owns the quiz, proceed with deleting the question using prepared statement
        $del_stmt = $conn->prepare("DELETE FROM questions WHERE id = ? AND quiz_id = ?");
        $del_stmt->bind_param("ii", $q_id, $quiz_id);

        if ($del_stmt->execute()) {
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