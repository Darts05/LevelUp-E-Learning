<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. Get Overall Average and Best Score
$stats_sql = "SELECT 
                AVG((score / total_questions) * 100) as average,
                MAX((score / total_questions) * 100) as best
              FROM results WHERE user_id = $user_id";
$stats_res = $conn->query($stats_sql);
$stats_data = $stats_res->fetch_assoc();

$average = ($stats_data['average'] !== null) ? round($stats_data['average'], 1) : 0;
$best = ($stats_data['best'] !== null) ? round($stats_data['best'], 1) : 0;

// 2. Get Quiz History
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
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 40px;">
            <div style="text-align: center; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-bottom: 5px solid #4CAF50;">
                <h1 style="margin: 0; font-size: 20px; color: #888;">Average Accuracy</h1>
                <div style="font-size: 50px; color: #4CAF50; font-weight: bold;"><?php echo $average; ?>%</div>
            </div>
            <div style="text-align: center; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-bottom: 5px solid #FFD700;">
                <h1 style="margin: 0; font-size: 20px; color: #888;">Best Score</h1>
                <div style="font-size: 50px; color: #FFD700; font-weight: bold;"><?php echo $best; ?>%</div>
            </div>
        </div>

        <h3>Quiz History</h3>

        <?php if ($history_result->num_rows == 0): ?>
            <div style="text-align: center; padding: 60px; background: #fdfdfd; border: 2px dashed #ddd; border-radius: 10px;">
                <p style="color: #888; font-size: 18px;">No data yet!</p>
                <a href="../index.php" class="btn-verify" style="display: inline-block; width: auto; padding: 10px 30px; margin-top: 15px;">Explore Quizzes</a>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-radius: 10px;">
                    <thead>
                        <tr style="background: #f4f4f4; text-align: left;">
                            <th style="padding: 15px; border-bottom: 2px solid #ddd;">Quiz</th>
                            <th style="padding: 15px; border-bottom: 2px solid #ddd; text-align: center;">Result</th>
                            <th style="padding: 15px; border-bottom: 2px solid #ddd; text-align: right;">Date Taken</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $history_result->fetch_assoc()): 
                            $percent = ($row['score'] / $row['total_questions']) * 100;
                            $color = ($percent >= 80) ? '#2e7d32' : (($percent >= 50) ? '#f57c00' : '#d32f2f');
                        ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px; font-weight: 500;">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </td>
                                <td style="padding: 15px; text-align: center;">
                                    <span style="color: <?php echo $color; ?>; font-weight: bold; font-size: 18px;">
                                        <?php echo $row['score']; ?> / <?php echo $row['total_questions']; ?>
                                    </span>
                                    <div style="font-size: 12px; color: #999;"><?php echo round($percent); ?>%</div>
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
            <a href="../index.php" style="text-decoration: none; color: #666; font-weight: bold;">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>