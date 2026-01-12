<?php
include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $q_id = $_POST['q_id'];
    $quiz_id = $_POST['quiz_id'];
    $text = $_POST['question_text'];
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $d = $_POST['option_d'];
    $correct = $_POST['correct_answer'];

    $sql = "UPDATE questions SET 
            question_text = '$text', 
            option_a = '$a', 
            option_b = '$b', 
            option_c = '$c', 
            option_d = '$d', 
            correct_answer = '$correct' 
            WHERE id = $q_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: edit_quiz.php?id=" . $quiz_id);
    } else {
        echo "Error: " . $conn->error;
    }
}
?>