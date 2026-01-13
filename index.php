<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LevelUp | Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; padding: 40px 5%;">
        <section>
            <div class="hero" style="text-align: left; padding: 0;">
                <h1>Ready to <span class="highlight">LevelUp?</span></h1>
                <p>Select a quiz below or search for a specific topic in the header.</p>
            </div>
        
            <div id="featured" style="margin-top: 30px;">
                <h2 style="margin-bottom: 20px;">Top Quizzes</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
                    <?php 
                    $quizzes = $conn->query("SELECT * FROM quizzes ORDER BY created_at DESC LIMIT 6");
                    if($quizzes->num_rows > 0):
                        while($row = $quizzes->fetch_assoc()): ?>
                            <div style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #eee;">
                                <span style="font-size: 12px; background: #e8f5e9; color: #2e7d32; padding: 4px 8px; border-radius: 10px;"><?php echo htmlspecialchars($row['category']); ?></span>
                                <h3 style="margin: 10px 0; font-size: 18px;"><?php echo htmlspecialchars($row['title']); ?></h3>
                                <a href="pages/take_quiz.php?quiz_id=<?php echo $row['id']; ?>" style="display: inline-block; margin-top: 10px; color: #4CAF50; font-weight: bold; text-decoration: none;">Start Quiz →</a>
                            </div>
                        <?php endwhile; 
                    else: ?>
                        <p style="color: #888;">No quizzes available yet. Why not create one?</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <aside style="background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); height: fit-content;">
            <h3 style="margin-bottom: 15px;">Quick Stats</h3>
            <div style="margin-bottom: 20px;">
                <p style="color: #666; font-size: 14px;">Total Quizzes Taken</p>
                <span style="font-size: 24px; font-weight: bold; color: #4CAF50;">
                    <?php 
                        $user_id = $_SESSION['user_id'];
                        $count_res = $conn->query("SELECT COUNT(*) as total FROM results WHERE user_id = $user_id");
                        $count_data = $count_res->fetch_assoc();
                        echo $count_data['total'];
                    ?>
                </span>
            </div>
            <a href="pages/progress.php" style="color: #007bff; text-decoration: none; font-size: 14px; font-weight: bold;">View Detailed Progress →</a>
        </aside>
    </main>
</body>
</html>