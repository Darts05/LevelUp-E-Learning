<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$conn->begin_transaction();

try {
    $q_questions = "DELETE FROM questions WHERE quiz_id IN (SELECT id FROM quizzes WHERE user_id = $user_id)";
    $conn->query($q_questions);

    $q_quizzes = "DELETE FROM quizzes WHERE user_id = $user_id";
    $conn->query($q_quizzes);

    $q_results = "DELETE FROM results WHERE user_id = $user_id";
    $conn->query($q_results);

    $q_user = "DELETE FROM users WHERE id = $user_id";
    $conn->query($q_user);

    $conn->commit();

    session_destroy();
    header("Location: index.php?msg=account_deleted");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    echo "Error deleting account: " . $e->getMessage();
}
?>