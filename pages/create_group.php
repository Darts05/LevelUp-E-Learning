<?php
session_start();
include '../db_connect.php';

// Guard: Only Teachers (Role 1) can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_name = $conn->real_escape_string($_POST['group_name']);
    $teacher_id = $_SESSION['user_id'];
    
    // Generate a random 6-character unique code
    // Matches your requirement for a 6-char alphanumeric string
    $group_code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

    // Updated column name to 'group_code' to match your my_groups.php display
    $sql = "INSERT INTO groups (group_name, teacher_id, group_code) VALUES ('$group_name', '$teacher_id', '$group_code')";    
    if ($conn->query($sql)) {
        header("Location: my_groups.php?success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Group | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="background-color: var(--bg-light);">
    <?php $path_prefix = "../"; include '../includes/header.php'; ?>
    
    <div class="main-container" style="max-width: 500px; margin: 80px auto; padding: 0 20px;">
        <div class="browse-card" style="padding: 40px;">
            <h2 style="color: var(--text-main); margin-bottom: 10px;">Create a New Class Group</h2>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 30px;">Students will use a unique code to join your group.</p>
            
            <form method="POST">
                <div class="input-group" style="margin-bottom: 25px;">
                    <label class="input-label">Group Name</label>
                    <input type="text" name="group_name" class="editor-input" placeholder="e.g. Science Class 4A" required autofocus>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <button type="submit" class="btn-verify">Generate Group & Code</button>
                    <a href="my_groups.php" style="text-align: center; color: var(--text-muted); text-decoration: none; font-size: 14px;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>