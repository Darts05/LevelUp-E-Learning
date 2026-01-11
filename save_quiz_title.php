<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['quiz_title'];
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];

    // Insert the quiz header into the 'quizzes' table
    $sql = "INSERT INTO quizzes (user_id, title, category) VALUES ('$user_id', '$title', '$category')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        
        // Redirect to the question-adding page, passing the ID in the URL
        header("Location: add_questions.php?quiz_id=" . $last_id);
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>