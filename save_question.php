<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_id       = $conn->real_escape_string($_POST['quiz_id']);
    $question_text = $conn->real_escape_string($_POST['question_text']);
    $option_a      = $conn->real_escape_string($_POST['option_a']);
    $option_b      = $conn->real_escape_string($_POST['option_b']);
    $option_c      = $conn->real_escape_string($_POST['option_c']);
    $option_d      = $conn->real_escape_string($_POST['option_d']);
    $correct       = $conn->real_escape_string($_POST['correct_answer']);
    $action        = $_POST['action'];
    
    $source        = isset($_POST['source']) ? $_POST['source'] : 'create';

    $sql = "INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_answer) 
            VALUES ('$quiz_id', '$question_text', '$option_a', '$option_b', '$option_c', '$option_d', '$correct')";

    if ($conn->query($sql) === TRUE) {
        if ($action == "another") {
            header("Location: add_questions.php?quiz_id=" . $quiz_id . "&from=" . $source);
        } else {
            if ($source === 'edit') {
                header("Location: pages/edit_quiz.php?id=" . $quiz_id . "&msg=updated");
            } else {
                echo "<script>alert('Quiz successfully published!'); window.location.href='index.php';</script>";
            }
        }
        exit();
    } else {
        echo "Error saving question: " . $conn->error;
    }
} else {
    header("Location: index.php");
    exit();
}
?>