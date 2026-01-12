<?php include '../includes/header.php'; ?>

<?php
session_start();
include '../db_connect.php';

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $quiz_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Security: Only delete if the quiz belongs to the logged-in user
    $sql = "DELETE FROM quizzes WHERE id = $quiz_id AND user_id = $user_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: my_quizzes.php?msg=deleted");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    header("Location: my_quizzes.php");
}
?>