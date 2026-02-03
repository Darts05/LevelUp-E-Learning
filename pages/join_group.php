<?php
session_start();
include '../db_connect.php';

// 1. Security: Ensure only Students can join
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$message = "";
$status = "";

// 2. The "Action" Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_code = strtoupper(trim($_POST['group_code']));
    $student_id = $_SESSION['user_id'];

    // Find the group
    $stmt = $conn->prepare("SELECT id, group_name FROM groups WHERE group_code = ?");
    $stmt->bind_param("s", $input_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $group = $result->fetch_assoc();
        $group_id = $group['id'];

        // Check if already joined
        $check = $conn->prepare("SELECT id FROM group_members WHERE group_id = ? AND student_id = ?");
        $check->bind_param("ii", $group_id, $student_id);
        $check->execute();
        
        if ($check->get_result()->num_rows == 0) {
            $join = $conn->prepare("INSERT INTO group_members (group_id, student_id) VALUES (?, ?)");
            $join->bind_param("ii", $group_id, $student_id);
            
            if ($join->execute()) {
                $message = "Successfully joined " . htmlspecialchars($group['group_name']) . "!";
                $status = "success";
            }
        } else {
            $message = "You are already a member of this group.";
            $status = "error";
        }
    } else {
        $message = "Invalid invite code. Please check and try again.";
        $status = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join a Class | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="background-color: var(--bg-light);">
    <?php $path_prefix = "../"; include '../includes/header.php'; ?>

    <div class="main-container" style="max-width: 500px; margin: 80px auto; padding: 0 20px;">
        <div class="browse-card" style="padding: 40px;">
            <h2 style="margin-bottom: 10px;">Enter Class Code</h2>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 25px;">
                Ask your teacher for the 6-character code to join their classroom.
            </p>

            <?php if ($message): ?>
                <div style="padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; 
                    background: <?php echo $status == 'success' ? '#E8F5E9' : '#FFEBEE'; ?>; 
                    color: <?php echo $status == 'success' ? '#2E7D32' : '#C62828'; ?>;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group" style="margin-bottom: 25px;">
                    <input type="text" name="group_code" class="editor-input" 
                           placeholder="e.g. AB12CD" maxlength="6" required 
                           style="text-align: center; font-size: 24px; letter-spacing: 4px; text-transform: uppercase;">
                </div>
                
                <button type="submit" class="btn-verify" style="width: 100%;">Join Classroom</button>
                <a href="../index.php" style="display: block; text-align: center; margin-top: 20px; color: var(--text-muted); text-decoration: none; font-size: 14px;">Return to Dashboard</a>
            </form>
        </div>
    </div>
</body>
</html>