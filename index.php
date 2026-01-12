<?php include 'includes/header.php'; ?>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login.html");
    exit();
}
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

    <main>
        <section class="hero">
            <h1>Welcome back, <span class="highlight"><?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span></h1>
            <p>Your learning journey continues. Ready to beat your high score today?</p>
            
            <div class="hero-btns">
                <div class="user-actions">
                    <a href="create_quiz.php" class="cta-main">Start a New Quiz</a>
                    <a href="pages/my_quizzes.php" style="margin-left: 15px; color: #007bff; text-decoration: none;">Manage My Quizzes</a>
                </div>
                <a href="#featured" class="cta-sub">View My Progress</a>
            </div>
        </section>

        <section id="featured" class="content-section" style="padding: 50px; text-align: center;">
            <h2>Recent Quizzes</h2>
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; margin-top: 20px;">
                <?php
                include 'db_connect.php';
                $sql = "SELECT * FROM quizzes ORDER BY created_at DESC LIMIT 3";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        ?>
                        <div style="border: 1px solid #ddd; padding: 20px; border-radius: 10px; width: 250px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            <h3 style="color: #333;"><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p style="color: #666;">Category: <?php echo htmlspecialchars($row['category']); ?></p>
                            <a href="pages/take_quiz.php?quiz_id=<?php echo $row['id']; ?>" class="cta-main" style="display: inline-block; margin-top: 10px; text-decoration: none; font-size: 14px; padding: 8px 15px;">Take Quiz</a>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No quizzes available yet. Start by creating one!</p>";
                }
                ?>
            </div>
        </section>
    </main>

</body>
</html>