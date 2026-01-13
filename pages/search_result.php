<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

//Search Logic
$search_query = "";
if (isset($_GET['query'])) {
    $search_query = $conn->real_escape_string($_GET['query']);
}

// Search by title or category
$sql = "SELECT * FROM quizzes WHERE title LIKE '%$search_query%' OR category LIKE '%$search_query%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .quiz-card {
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 12px;
            width: 280px;
            background: #fff;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .quiz-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
            border-color: #4CAF50;
        }
        .category-tag {
            font-size: 12px;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="main-container" style="max-width: 1000px; margin: 40px auto; padding: 20px;">
        <div style="margin-bottom: 30px;">
            <p style="color: #666; margin-bottom: 5px;">Showing results for:</p>
            <h2 style="margin-top: 0;">"<span style="color: #4CAF50;"><?php echo htmlspecialchars($search_query); ?></span>"</h2>
        </div>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 30px;">

        <div style="display: flex; gap: 25px; flex-wrap: wrap;">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="quiz-card">
                        <div>
                            <span class="category-tag"><?php echo htmlspecialchars($row['category']); ?></span>
                            <h3 style="margin: 10px 0;"><?php echo htmlspecialchars($row['title']); ?></h3>
                        </div>
                        <a href="take_quiz.php?quiz_id=<?php echo $row['id']; ?>" class="cta-main" style="display: block; text-align: center; text-decoration: none; margin-top: 15px; background: #4CAF50; color: white; padding: 10px; border-radius: 6px;">Take Quiz</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align: center; width: 100%; padding: 50px;">
                    <p style="font-size: 18px; color: #666;">No quizzes found matching your search.</p>
                    <a href="../index.php" style="color: #4CAF50; font-weight: bold;">Browse all quizzes →</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>