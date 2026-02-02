<?php
session_start();
include 'db_connect.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];
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
                
                <?php if ($user_role == 1): ?>
                    <div class="teacher-controls" style="background: #f0fdf4; padding: 20px; border-radius: 12px; margin-top: 20px; border: 1px dashed #4CAF50;">
                        <h3 style="color: #2e7d32; margin-top: 0;">Teacher Dashboard</h3>
                        <p>Create new content or manage your existing quizzes.</p>
                        <a href="create_quiz.php" class="btn" style="background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;">+ Create New Quiz</a>
                    </div>
                <?php else: ?>
                    <p>Select a quiz below or go to <a href="pages/browse.php" style="color: #4CAF50; font-weight: bold; text-decoration: none;">Browse Quizzes</a> to search for a specific topic.</p>
                <?php endif; ?>
            </div>
            
            <div id="featured" style="margin-top: 30px;">
                <h2 style="margin-bottom: 20px;">Top Quizzes</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
                    <?php 
                    if ($user_role == 1) {
                        $sql = "SELECT * FROM quizzes WHERE is_published = 1 OR user_id = $user_id ORDER BY created_at DESC LIMIT 6";
                    } else {
                        $sql = "SELECT * FROM quizzes WHERE is_published = 1 ORDER BY created_at DESC LIMIT 6";
                    }
                    
                    $quizzes = $conn->query($sql);
                    
                    if($quizzes->num_rows > 0):
                        while($row = $quizzes->fetch_assoc()): ?>
                            <div style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #eee; position: relative;">
                                
                                <?php if($row['is_published'] == 0): ?>
                                    <span style="position: absolute; top: 10px; right: 10px; font-size: 10px; background: #fff3e0; color: #ef6c00; padding: 2px 6px; border-radius: 4px;">Draft</span>
                                <?php endif; ?>

                                <span style="font-size: 12px; background: #e8f5e9; color: #2e7d32; padding: 4px 8px; border-radius: 10px;"><?php echo htmlspecialchars($row['category']); ?></span>
                                <h3 style="margin: 10px 0; font-size: 18px;"><?php echo htmlspecialchars($row['title']); ?></h3>
                                
                                <a href="pages/take_quiz.php?quiz_id=<?php echo $row['id']; ?>" style="display: inline-block; margin-top: 10px; color: #4CAF50; font-weight: bold; text-decoration: none;">
                                    <?php echo ($user_role == 1 && $row['user_id'] == $user_id) ? "Preview Quiz →" : "Start Quiz →"; ?>
                                </a>
                            </div>
                        <?php endwhile; 
                    else: ?>
                        <p style="color: #888;">No quizzes available yet.</p>
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
                        $count_res = $conn->query("SELECT COUNT(*) as total FROM results WHERE user_id = $user_id");
                        $count_data = $count_res->fetch_assoc();
                        echo $count_data['total'];
                    ?>
                </span>
            </div>
            <a href="pages/progress.php" style="color: #007bff; text-decoration: none; font-size: 14px; font-weight: bold;">View Detailed Progress →</a>
        </aside>
    </main>

    <?php if (isset($_SESSION['show_welcome'])): ?>
        <script>alert("Welcome back!");</script>
        <?php unset($_SESSION['show_welcome']); ?>
    <?php endif; ?>
</body>
</html>