<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: index.php?error=unauthorized");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Quiz | LevelUp</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <div class="login-container" style="justify-content: center; align-items: center; display: flex; min-height: 80vh;">
        <form action="save_quiz_title.php" method="POST" class="login-form" style="width: 100%; max-width: 400px; padding: 30px; border: 1px solid #eee; border-radius: 15px; background: white; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
            <h2 style="text-align: center; margin-bottom: 10px;">Step 1: Quiz Details</h2>
            <p style="text-align: center; color: #888; margin-bottom: 25px;">Set your title and category first.</p>
            
            <div class="input-group" style="margin-bottom: 15px; display: flex; flex-direction: column;">
                <label for="quiz_title" style="font-weight: bold; margin-bottom: 5px;">Quiz Title</label>
                <input type="text" name="quiz_title" id="quiz_title" placeholder="e.g., Intro to Python" required style="padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div class="input-group" style="margin-bottom: 25px; display: flex; flex-direction: column;">
                <label for="category" style="font-weight: bold; margin-bottom: 5px;">Category</label>
                <select name="category" id="category" style="padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: white; cursor: pointer;">
                    <option value="Programming">Programming</option>
                    <option value="Science">Science</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="General Knowledge">General Knowledge</option>
                </select>
            </div>

            <button type="submit" class="login-btn" style="width: 100%; padding: 14px; background-color: #4CAF50; color: white; border: none; cursor: pointer; border-radius: 5px; font-size: 16px; font-weight: bold; transition: 0.3s;">
                Next: Add Questions
            </button>
            
            <p style="text-align: center; margin-top: 20px;">
                <a href="pages/my_quizzes.php" style="color: #999; text-decoration: none; font-size: 14px;">← Cancel and go back</a>
            </p>
        </form>
    </div>

</body>
</html>