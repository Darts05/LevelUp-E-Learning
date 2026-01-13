<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['quiz_id'])) {
    header("Location: create_quiz.php");
    exit();
}

$quiz_id = $conn->real_escape_string($_GET['quiz_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Questions | LevelUp</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="login-container" style="flex-direction: column; padding: 40px; align-items: center; min-height: 80vh;">
        <form action="save_question.php" method="POST" class="login-form" style="max-width: 500px; width: 100%; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <h2 style="text-align: center; color: #333;">Add New Question</h2>
            <p style="text-align: center; color: #666; margin-bottom: 25px;">Building Quiz ID: #<?php echo $quiz_id; ?></p>
            
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

            <div class="input-group" style="margin-bottom: 15px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Question Text</label>
                <input type="text" name="question_text" placeholder="e.g., What does PHP stand for?" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Options</label>
            <div class="options-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                <input type="text" name="option_a" placeholder="Option A" required style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <input type="text" name="option_b" placeholder="Option B" required style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <input type="text" name="option_c" placeholder="Option C" required style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <input type="text" name="option_d" placeholder="Option D" required style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div class="input-group" style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Correct Answer</label>
                <select name="correct_answer" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background: white;">
                    <option value="A">Option A</option>
                    <option value="B">Option B</option>
                    <option value="C">Option C</option>
                    <option value="D">Option D</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" name="action" value="another" class="cta-sub" style="flex: 1; padding: 12px; border-radius: 5px; cursor: pointer; border: 1px solid #4CAF50; color: #4CAF50; background: white; font-weight: bold;">Save & Add More</button>
                
                <button type="submit" name="action" value="finish" class="login-btn" style="flex: 1; background-color: #28a745; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; font-weight: bold;">Finish & Publish</button>
            </div>
        </form>
    </div>
</body>
</html>