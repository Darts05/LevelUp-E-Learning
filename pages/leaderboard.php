<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if we are looking at a specific quiz or Global
$quiz_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($quiz_id) {
    // SPECIFIC QUIZ LOGIC
    $title_query = "SELECT title FROM quizzes WHERE id = $quiz_id";
    $title_res = $conn->query($title_query);
    $quiz_data = $title_res->fetch_assoc();
    $display_title = "Leaderboard: " . htmlspecialchars($quiz_data['title'] ?? 'Quiz');

    $leader_sql = "SELECT users.full_name, results.score, results.total_questions, results.taken_at 
                   FROM results 
                   JOIN users ON results.user_id = users.id 
                   WHERE results.quiz_id = $quiz_id 
                   ORDER BY results.score DESC, results.taken_at ASC 
                   LIMIT 5";
} else {
    // GLOBAL LOGIC: Sum of all scores
    $display_title = "Global Top Learners";
    $leader_sql = "SELECT users.full_name, SUM(results.score) as score, SUM(results.total_questions) as total_questions 
                   FROM results 
                   JOIN users ON results.user_id = users.id 
                   GROUP BY users.id 
                   ORDER BY score DESC 
                   LIMIT 5";
}

$leader_result = $conn->query($leader_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="main-container" style="max-width: 800px; margin: 40px auto; padding: 20px;">
        <div class="leaderboard-section" style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <h2 style="text-align: center;">🏆 <?php echo $display_title; ?></h2>

            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="border-bottom: 2px solid #eee; color: #666;">
                        <th style="text-align: left; padding: 15px;">Rank</th>
                        <th style="text-align: left; padding: 15px;">Learner</th>
                        <th style="text-align: right; padding: 15px;">Total Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    if ($leader_result && $leader_result->num_rows > 0):
                        while($row = $leader_result->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #fafafa; <?php echo ($rank <= 3) ? 'font-weight: bold;' : ''; ?>">
                                <td style="padding: 15px;">
                                    <?php 
                                        if($rank == 1) echo "🥇";
                                        elseif($rank == 2) echo "🥈";
                                        elseif($rank == 3) echo "🥉";
                                        else echo $rank;
                                    ?>
                                </td>
                                <td style="padding: 15px;"><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td style="padding: 15px; text-align: right; color: #4CAF50;">
                                    <?php echo $row['score']; ?> / <?php echo $row['total_questions']; ?>
                                </td>
                            </tr>
                            <?php $rank++; ?>
                        <?php endwhile; 
                    else: ?>
                        <tr>
                            <td colspan="3" style="padding: 30px; text-align: center; color: #999;">
                                No attempts yet. Be the first to top the board!
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <?php if($quiz_id): ?>
            <div style="margin-top: 30px; text-align: center;">
                <a href="take_quiz.php?quiz_id=<?php echo $quiz_id; ?>" class="login-btn" style="text-decoration:none;">Try Again</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>