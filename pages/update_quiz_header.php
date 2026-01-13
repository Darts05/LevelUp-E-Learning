<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_id = $conn->real_escape_string($_POST['quiz_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);

    $sql = "UPDATE quizzes SET title = '$title', category = '$category' WHERE id = $quiz_id";

    if ($conn->query($sql) === TRUE) {
        
        if (isset($_POST['delete_ids']) && is_array($_POST['delete_ids'])) {
            foreach ($_POST['delete_ids'] as $q_id) {
                $q_id = intval($q_id); 
                $conn->query("DELETE FROM questions WHERE id = $q_id AND quiz_id = $quiz_id");
            }
        }

        header("Location: edit_quiz.php?id=" . $quiz_id . "&msg=updated");
        exit();
    } else {
        echo "Error updating quiz: " . $conn->error;
    }
} else {
    header("Location: my_quizzes.php");
    exit();
}
?>