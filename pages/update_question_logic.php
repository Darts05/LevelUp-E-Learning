<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $q_id = intval($_POST['question_id']);
    $quiz_id = intval($_POST['quiz_id']);
    
    $check_ownership = "SELECT id FROM quizzes WHERE id = $quiz_id AND user_id = $user_id";
    $ownership_res = $conn->query($check_ownership);

    if ($ownership_res->num_rows == 0) {
        die("Unauthorized access: You do not have permission to edit this quiz.");
    }

    $text = $conn->real_escape_string($_POST['question_text']);
    $a = $conn->real_escape_string($_POST['option_a']);
    $b = $conn->real_escape_string($_POST['option_b']);
    $c = $conn->real_escape_string($_POST['option_c']);
    $d = $conn->real_escape_string($_POST['option_d']);
    $correct = $_POST['correct_answer'];

    $sql = "UPDATE questions SET 
            question_text = '$text', 
            option_a = '$a', 
            option_b = '$b', 
            option_c = '$c', 
            option_d = '$d', 
            correct_answer = '$correct' 
            WHERE id = $q_id AND quiz_id = $quiz_id";

    if ($conn->query($sql)) {
        header("Location: edit_quiz.php?id=$quiz_id&msg=updated");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    header("Location: my_quizzes.php");
    exit();
}
?>