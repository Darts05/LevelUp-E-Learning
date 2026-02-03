<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$show_result = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $quiz_id = intval($_POST['quiz_id']);
    $score = intval($_POST['final_score']);
    $total = intval($_POST['total_q']);
    $percentage = ($total > 0) ? ($score / $total) * 100 : 0;

    // Secure database entry
    $stmt = $conn->prepare("INSERT INTO results (user_id, quiz_id, score, total_questions, percentage) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiid", $user_id, $quiz_id, $score, $total, $percentage);

    if ($stmt->execute()) {
        $show_result = true;
    } else {
        die("Error saving result: " . $conn->error);
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
    <title>Results | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Specific page logic combined with your design system */
        .results-container {
            max-width: 600px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .results-card {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            text-align: center;
            border-top: 8px solid #4CAF50;
        }
        .score-box {
            background: #f0fdf4;
            padding: 30px;
            border-radius: 15px;
            margin: 25px 0;
            border: 1px solid #dcfce7;
        }
        .score-big {
            font-size: 64px;
            font-weight: 800;
            color: #15803d;
            margin: 10px 0;
        }
        .score-total {
            font-size: 24px;
            color: #86efac;
            font-weight: 400;
        }
        .progress-track {
            width: 100%;
            height: 12px;
            background: #e2e8f0;
            border-radius: 10px;
            margin: 15px 0;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: #4CAF50;
            transition: width 1.5s ease-out;
        }
        .action-btns {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <?php $path_prefix = "../"; include '../includes/header.php'; ?>

    <div class="results-container">
        <?php if ($show_result): ?>
            <div class="results-card">
                <div style="font-size: 70px; margin-bottom: 10px;">
                    <?php echo ($percentage >= 80) ? "🏆" : (($percentage >= 50) ? "🎈" : "📚"); ?>
                </div>

                <h1 style="color: #1a202c;"><?php echo ($percentage >= 80) ? "Mastery Achieved!" : "Challenge Completed!"; ?></h1>
                <p style="color: #718096;">Great work on finishing the assessment.</p>

                <div class="score-box">
                    <span style="text-transform: uppercase; font-size: 12px; font-weight: 700; color: #166534; letter-spacing: 1px;">Accuracy</span>
                    <div class="score-big">
                        <?php echo $score; ?><span class="score-total"> / <?php echo $total; ?></span>
                    </div>
                    
                    <div class="progress-track">
                        <div class="progress-fill" style="width: <?php echo $percentage; ?>%;"></div>
                    </div>
                    
                    <p style="font-weight: 800; color: #15803d; font-size: 20px;"><?php echo round($percentage, 1); ?>%</p>
                </div>

                

                <div class="action-btns">
                    <a href="statistics.php" class="btn-verify" style="margin: 0; padding: 16px; width: 100%; text-decoration: none;">
                        📊 View My Growth Statistics
                    </a>
                    
                    <?php if ($user_role == 1): ?>
                        <a href="edit_quiz.php?id=<?php echo $quiz_id; ?>" class="btn-outline" style="color: #c05621; border-color: #feebc8; text-decoration: none;">
                            🛠️ Return to Editor
                        </a>
                    <?php else: ?>
                        <a href="leaderboard.php?id=<?php echo $quiz_id; ?>" class="btn-outline" style="text-decoration: none;">
                            🥇 Check Leaderboard
                        </a>
                    <?php endif; ?>
                    
                    <a href="../index.php" style="color: #a0aec0; text-decoration: none; font-size: 14px; margin-top: 10px;">Back to Dashboard</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>