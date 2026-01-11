<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_id = $_POST['quiz_id'];
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct = $_POST['correct_answer'];
    $action = $_POST['action'];

    $sql = "INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_answer) 
            VALUES ('$quiz_id', '$question_text', '$option_a', '$option_b', '$option_c', '$option_d', '$correct')";

    if ($conn->query($sql) === TRUE) {
        if ($action == "another") {
            // Send back to add another question for the SAME quiz
            header("Location: add_questions.php?quiz_id=" . $quiz_id);
        } else {
            // If they clicked "Finish", send them to the Dashboard
            echo "<script>alert('Quiz successfully published!'); window.location.href='index.php';</script>";
        }
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>