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

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

$quiz_id = $conn->real_escape_string($_GET['quiz_id']);

$source = isset($_GET['from']) ? $_GET['from'] : 'create';

$back_url = ($source === 'edit') ? "pages/edit_quiz.php?id=$quiz_id" : "pages/my_quizzes.php";

$count_query = "SELECT COUNT(*) as total FROM questions WHERE quiz_id = '$quiz_id'";
$count_res = $conn->query($count_query);
$count_data = $count_res->fetch_assoc();
$current_count = $count_data['total'] + 1;
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

    <div class="main-container" style="max-width: 700px; margin: 40px auto; padding: 20px;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="color: #001f3f;">Add New Question</h2>
            <div style="background: #4CAF50; color: white; padding: 8px 18px; border-radius: 50px; font-weight: bold; box-shadow: 0 4px 10px rgba(76, 175, 80, 0.2);">
                Question #<span id="questionCount"><?php echo $current_count; ?></span>
            </div>
        </div>

        <form action="save_question.php" method="POST" class="quiz-form">
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
            <input type="hidden" name="source" value="<?php echo $source; ?>">

            <div style="background: white; padding: 30px; border-radius: 15px; border: 1px solid #eee; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                
                <div class="input-group" style="margin-bottom: 25px;">
                    <label style="font-weight: 600; display: block; margin-bottom: 10px;">Question Text</label>
                    <textarea name="question_text" placeholder="e.g., What does PHP stand for?" required 
                        style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 10px; font-family: inherit; height: 100px; font-size: 16px;"></textarea>
                </div>

                <label style="font-weight: 600; display: block; margin-bottom: 10px;">Answer Options</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                    <div class="input-group">
                        <input type="text" name="option_a" placeholder="Option A" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                    <div class="input-group">
                        <input type="text" name="option_b" placeholder="Option B" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                    <div class="input-group">
                        <input type="text" name="option_c" placeholder="Option C" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                    <div class="input-group">
                        <input type="text" name="option_d" placeholder="Option D" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                </div>

                <div class="input-group" style="margin-bottom: 30px; background: #f8fcf9; padding: 20px; border-radius: 12px; border: 1px solid #e1f0e5;">
                    <label style="font-weight: bold; display: block; margin-bottom: 10px; color: #2e7d32;">Identify Correct Answer</label>
                    <select name="correct_answer" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: white; font-size: 16px; cursor: pointer;">
                        <option value="A">Option A</option>
                        <option value="B">Option B</option>
                        <option value="C">Option C</option>
                        <option value="D">Option D</option>
                    </select>
                </div>

                <div style="display: flex; gap: 15px;">
                    <button type="submit" name="action" value="another" class="cta-sub" 
                        style="flex: 1; padding: 15px; border-radius: 10px; cursor: pointer; border: 2px solid #4CAF50; color: #4CAF50; background: white; font-weight: bold; transition: 0.3s;">
                        Save & Add Another
                    </button>
                    
                    <button type="submit" name="action" value="finish" class="login-btn" 
                        style="flex: 1; background-color: #28a745; color: white; border: none; padding: 15px; border-radius: 10px; cursor: pointer; font-weight: bold; transition: 0.3s;">
                        Complete Quiz
                    </button>
                </div>

                <div style="text-align: center; margin-top: 20px;">
                    <a href="<?php echo $back_url; ?>" style="color: #888; text-decoration: none; font-size: 14px;">Cancel and Return</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>