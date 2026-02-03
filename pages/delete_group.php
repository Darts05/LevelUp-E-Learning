<?php
session_start();
include '../db_connect.php';

// Security: Must be logged in and must be a teacher
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $group_id = intval($_GET['id']);
    $teacher_id = $_SESSION['user_id'];

    // Only delete if the group belongs to the logged-in teacher
    $stmt = $conn->prepare("DELETE FROM groups WHERE id = ? AND teacher_id = ?");
    $stmt->bind_param("ii", $group_id, $teacher_id);

    if ($stmt->execute()) {
        header("Location: my_groups.php?deleted=1");
    } else {
        echo "Error deleting group.";
    }
}
?>