<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape all inputs to prevent SQL errors and Injection
    $q_id = $conn->real_escape_string($_POST['q_id']);
    $quiz_id = $conn->real_escape_string($_POST['quiz_id']);
    $text = $conn->real_escape_string($_POST['question_text']);
    $a = $conn->real_escape_string($_POST['option_a']);
    $b = $conn->real_escape_string($_POST['option_b']);
    $c = $conn->real_escape_string($_POST['option_c']);
    $d = $conn->real_escape_string($_POST['option_d']);
    $correct = $conn->real_escape_string($_POST['correct_answer']);

    $sql = "UPDATE questions SET 
            question_text = '$text', 
            option_a = '$a', 
            option_b = '$b', 
            option_c = '$c', 
            option_d = '$d', 
            correct_answer = '$correct' 
            WHERE id = $q_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the quiz edit page with a success message
        header("Location: edit_quiz.php?id=" . $quiz_id . "&msg=updated");
        exit();
    } else {
        echo "Error updating question: " . $conn->error;
    }
} else {
    header("Location: my_quizzes.php");
    exit();
}
?>