<?php
session_start();
include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $quiz_id = $_POST['quiz_id'];
    $score = $_POST['final_score'];
    $total = $_POST['total_q'];

    $sql = "INSERT INTO results (user_id, quiz_id, score, total_questions) VALUES ('$user_id', '$quiz_id', '$score', '$total')";

    if ($conn->query($sql) === TRUE) {
        echo "<div style='text-align:center; padding:50px;'>";
        echo "<h1>Quiz Finished!</h1>";
        echo "<h2>Your Final Score: $score / $total</h2>";
        echo "<a href='../index.php' class='login-btn'>Back to Dashboard</a>";
        echo "</div>";
    }
}
?>