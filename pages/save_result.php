<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$show_result = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $quiz_id = $conn->real_escape_string($_POST['quiz_id']);
    $score = $conn->real_escape_string($_POST['final_score']);
    $total = $conn->real_escape_string($_POST['total_q']);

    $sql = "INSERT INTO results (user_id, quiz_id, score, total_questions) VALUES ('$user_id', '$quiz_id', '$score', '$total')";

    if ($conn->query($sql) === TRUE) {
        $show_result = true;
    } else {
        echo "Error saving result: " . $conn->error;
    }
} else {
    // If someone tries to access this page directly without finishing a quiz
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Results | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="main-container" style="max-width: 600px; margin: 80px auto; text-align: center;">
        <?php if ($show_result): ?>
            <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                <div style="font-size: 80px; margin-bottom: 20px;">🎉</div>
                <h1 style="color: #333;">Quiz Finished!</h1>
                <p style="color: #666; font-size: 18px;">Great job completing the challenge.</p>
                
                <div style="margin: 30px 0; padding: 20px; background: #f0fdf4; border-radius: 10px;">
                    <span style="font-size: 20px; color: #166534;">Your Final Score</span>
                    <h2 style="font-size: 48px; color: #15803d; margin: 10px 0;"><?php echo $score; ?> / <?php echo $total; ?></h2>
                </div>

                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <a href="leaderboard.php?id=<?php echo $quiz_id; ?>" class="cta-main" style="text-decoration: none; background: #4CAF50; color: white; padding: 12px; border-radius: 5px;">View Leaderboard</a>
                    
                    <a href="progress.php" style="text-decoration: none; color: #007bff; font-weight: bold;">View My All-Time Progress</a>
                    
                    <a href="../index.php" style="text-decoration: none; color: #666; margin-top: 10px;">Return to Dashboard</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>