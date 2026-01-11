<?php
session_start();
include 'db_connect.php';

if (!isset($_GET['quiz_id'])) {
    header("Location: create_quiz.php");
    exit();
}

$quiz_id = $_GET['quiz_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Questions | LevelUp</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container" style="flex-direction: column; padding: 40px; align-items: center;">
        <form action="save_question.php" method="POST" class="login-form" style="max-width: 500px; width: 100%;">
            <h2>Add New Question</h2>
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

            <div class="input-group">
                <label>Question Text</label>
                <input type="text" name="question_text" placeholder="e.g., What does PHP stand for?" required>
            </div>

            <div class="options-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <input type="text" name="option_a" placeholder="Option A" required>
                <input type="text" name="option_b" placeholder="Option B" required>
                <input type="text" name="option_c" placeholder="Option C" required>
                <input type="text" name="option_d" placeholder="Option D" required>
            </div>

            <div class="input-group" style="margin-top: 15px;">
                <label>Correct Answer</label>
                <select name="correct_answer">
                    <option value="A">Option A</option>
                    <option value="B">Option B</option>
                    <option value="C">Option C</option>
                    <option value="D">Option D</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" name="action" value="another" class="cta-sub" style="flex: 1;">Save & Add More</button>
                <button type="submit" name="action" value="finish" class="login-btn" style="flex: 1; background-color: #28a745;">Finish & Publish</button>
            </div>
        </form>
    </div>
</body>
</html>