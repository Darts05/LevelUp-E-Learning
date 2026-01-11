<?php
session_start();
include 'db_connect.php';

// THE GATEKEEPER: Ensure only logged-in users can create quizzes
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
    <title>Create New Quiz | LevelUp</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="login-container" style="justify-content: center; align-items: center; display: flex; height: 100vh;">
        <form action="save_quiz_title.php" method="POST" class="login-form" style="width: 100%; max-width: 400px; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
            <h2 style="text-align: center; margin-bottom: 20px;">Step 1: Quiz Details</h2>
            
            <div class="input-group" style="margin-bottom: 15px; display: flex; flex-direction: column;">
                <label for="quiz_title">Quiz Title</label>
                <input type="text" name="quiz_title" id="quiz_title" placeholder="e.g., Intro to Python" required style="padding: 10px; margin-top: 5px;">
            </div>

            <div class="input-group" style="margin-bottom: 20px; display: flex; flex-direction: column;">
                <label for="category">Category</label>
                <select name="category" id="category" style="padding: 10px; margin-top: 5px; border-radius: 5px;">
                    <option value="Programming">Programming</option>
                    <option value="Science">Science</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="General Knowledge">General Knowledge</option>
                </select>
            </div>

            <button type="submit" class="login-btn" style="width: 100%; padding: 12px; background-color: #4CAF50; color: white; border: none; cursor: pointer; border-radius: 5px;">
                Next: Add Questions
            </button>
            
            <p style="text-align: center; margin-top: 15px;">
                <a href="index.php" style="color: #666; text-decoration: none;">Cancel and go back</a>
            </p>
        </form>
    </div>

</body>
</html>