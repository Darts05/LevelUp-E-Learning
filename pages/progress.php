<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

//Get average score percentage
$avg_sql = "SELECT AVG((score / total_questions) * 100) as average FROM results WHERE user_id = $user_id";
$avg_res = $conn->query($avg_sql);
$avg_data = $avg_res->fetch_assoc();

$average = ($avg_data['average'] !== null) ? round($avg_data['average'], 1) : 0;

//Get history
$history_sql = "SELECT quizzes.title, results.score, results.total_questions, results.taken_at 
                FROM results 
                JOIN quizzes ON results.quiz_id = quizzes.id 
                WHERE results.user_id = $user_id 
                ORDER BY results.taken_at DESC";
$history_result = $conn->query($history_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Progress | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="main-container" style="max-width: 900px; margin: 40px auto; padding: 20px;">
        
        <div style="text-align: center; margin-bottom: 40px; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <h1>Learning Progress</h1>
            <div style="font-size: 60px; color: #4CAF50; font-weight: bold;"><?php echo $average; ?>%</div>
            <p style="color: #666; font-size: 18px;">Your Average Accuracy</p>
        </div>

        <h3>Quiz History</h3>

        <?php if ($history_result->num_rows == 0): ?>
            <div style="text-align: center; padding: 60px; background: #fdfdfd; border: 2px dashed #ddd; border-radius: 10px;">
                <p style="color: #888; font-size: 18px;">You haven't taken any quizzes yet!</p>
                <p style="margin-bottom: 20px; color: #aaa;">Challenge yourself and start your first quiz today.</p>
                <a href="../index.php" class="cta-main" style="text-decoration: none; background: #4CAF50; color: white; padding: 10px 25px; border-radius: 5px; display: inline-block;">Explore Quizzes</a>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-radius: 10px;">
                    <thead>
                        <tr style="background: #f4f4f4; text-align: left;">
                            <th style="padding: 15px; border-bottom: 2px solid #ddd;">Quiz</th>
                            <th style="padding: 15px; border-bottom: 2px solid #ddd; text-align: center;">Score</th>
                            <th style="padding: 15px; border-bottom: 2px solid #ddd; text-align: right;">Date Taken</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $history_result->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px; font-weight: 500;"><?php echo htmlspecialchars($row['title']); ?></td>
                                <td style="padding: 15px; text-align: center;">
                                    <span style="color: #4CAF50; font-weight: bold;"><?php echo $row['score']; ?></span>
                                    <span style="color: #999;"> / <?php echo $row['total_questions']; ?></span>
                                </td>
                                <td style="padding: 15px; text-align: right; color: #888;">
                                    <?php echo date('d M Y', strtotime($row['taken_at'])); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px;">
            <a href="../index.php" style="text-decoration: none; color: #666;">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>