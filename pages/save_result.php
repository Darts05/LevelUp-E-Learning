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
    $score = intval($_POST['final_score']);
    $total = intval($_POST['total_q']);
    
    $percentage = ($total > 0) ? ($score / $total) * 100 : 0;

    $sql = "INSERT INTO results (user_id, quiz_id, score, total_questions, percentage) 
            VALUES ('$user_id', '$quiz_id', '$score', '$total', '$percentage')";

    if ($conn->query($sql) === TRUE) {
        $show_result = true;
    } else {
        echo "Error saving result: " . $conn->error;
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}

$user_role = $_SESSION['role']; 
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
            <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border: 1px solid #eee;">
                
                <div style="font-size: 80px; margin-bottom: 20px;">
                    <?php echo ($percentage >= 80) ? "🏆" : (($percentage >= 50) ? "🎈" : "📚"); ?>
                </div>

                <h1 style="color: #333;">
                    <?php 
                        if($percentage >= 80) echo "Excellent!";
                        elseif($percentage >= 50) echo "Good Job!";
                        else echo "Keep Practicing!";
                    ?>
                </h1>
                
                <p style="color: #666; font-size: 18px;">You've completed the challenge!</p>
                
                <div style="margin: 30px 0; padding: 25px; background: #f0fdf4; border-radius: 12px; border: 1px solid #dcfce7;">
                    <span style="font-size: 18px; color: #166534; font-weight: 500;">Your Final Score</span>
                    <h2 style="font-size: 56px; color: #15803d; margin: 10px 0;"><?php echo $score; ?> <span style="font-size: 24px; color: #86efac;">/ <?php echo $total; ?></span></h2>
                    <p style="font-weight: bold; color: #15803d;"><?php echo round($percentage, 1); ?>%</p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <a href="leaderboard.php?id=<?php echo $quiz_id; ?>" class="cta-main" 
                       style="text-decoration: none; background: #4CAF50; color: white; padding: 14px; border-radius: 8px; font-weight: bold; transition: 0.3s;">
                       View Leaderboard
                    </a>
                    
                    <?php if ($user_role == 1): ?>
                        <a href="edit_quiz.php?id=<?php echo $quiz_id; ?>" 
                           style="text-decoration: none; color: #ef6c00; font-weight: bold; padding: 10px; border: 2px solid #ffcc80; border-radius: 8px;">
                           🛠️ Edit this Quiz
                        </a>
                    <?php else: ?>
                        <a href="progress.php" 
                           style="text-decoration: none; color: #007bff; font-weight: bold; padding: 10px; border: 2px solid #bee3f8; border-radius: 8px;">
                           📈 View My Progress
                        </a>
                    <?php endif; ?>
                    
                    <a href="../index.php" style="text-decoration: none; color: #888; margin-top: 15px; font-size: 14px;">Return to Dashboard</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>