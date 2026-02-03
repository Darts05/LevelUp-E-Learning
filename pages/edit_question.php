<?php
session_start();
include '../db_connect.php';

// 1. Security: Check if user is logged in and is a Teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: my_quizzes.php");
    exit();
}

$q_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// 2. Security Query: Verify the question exists AND belongs to a quiz owned by this teacher
$query = "SELECT q.*, qz.user_id as owner_id 
          FROM questions q 
          JOIN quizzes qz ON q.quiz_id = qz.id 
          WHERE q.id = $q_id";

$res = $conn->query($query);
$q = $res->fetch_assoc();

if (!$q || $q['owner_id'] != $user_id) {
    die("Access Denied: You do not have permission to edit this question.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Question | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="background-color: #f8f9fa;">
    <?php $path_prefix = "../"; ?>
    <?php include '../includes/header.php'; ?>

    <div class="main-container" style="max-width: 800px; margin: 40px auto; padding: 0 20px;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="color: #333;">Modify Question</h2>
            <a href="edit_quiz.php?id=<?php echo $q['quiz_id']; ?>" style="color: #666; text-decoration: none; font-weight: 500;">← Back to Quiz Editor</a>
        </div>

        <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <form action="update_question_logic.php" method="POST">
                <input type="hidden" name="question_id" value="<?php echo $q_id; ?>">
                <input type="hidden" name="quiz_id" value="<?php echo $q['quiz_id']; ?>">

                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 10px; color: #444;">Question Prompt</label>
                    <textarea name="question_text" required 
                              style="width: 100%; padding: 15px; border: 2px solid #eee; border-radius: 12px; font-family: inherit; font-size: 16px; min-height: 120px; transition: border-color 0.3s;"
                              onfocus="this.style.borderColor='#4CAF50'"><?php echo htmlspecialchars($q['question_text']); ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                    <div>
                        <label style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 14px; color: #666;">Option A</label>
                        <input type="text" name="option_a" value="<?php echo htmlspecialchars($q['option_a']); ?>" required 
                               style="width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 14px; color: #666;">Option B</label>
                        <input type="text" name="option_b" value="<?php echo htmlspecialchars($q['option_b']); ?>" required 
                               style="width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 14px; color: #666;">Option C</label>
                        <input type="text" name="option_c" value="<?php echo htmlspecialchars($q['option_c']); ?>" required 
                               style="width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 14px; color: #666;">Option D</label>
                        <input type="text" name="option_d" value="<?php echo htmlspecialchars($q['option_d']); ?>" required 
                               style="width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 8px;">
                    </div>
                </div>

                <div style="margin-bottom: 40px; background: #f1f8f4; padding: 25px; border-radius: 15px; border: 1px dashed #4CAF50;">
                    <label style="display: block; font-weight: bold; margin-bottom: 12px; color: #2e7d32;">Identify the Correct Answer</label>
                    <select name="correct_answer" style="width: 100%; padding: 15px; border: 2px solid #fff; border-radius: 10px; font-size: 16px; cursor: pointer;">
                        <option value="A" <?php echo ($q['correct_answer'] == 'A') ? 'selected' : ''; ?>>Option A</option>
                        <option value="B" <?php echo ($q['correct_answer'] == 'B') ? 'selected' : ''; ?>>Option B</option>
                        <option value="C" <?php echo ($q['correct_answer'] == 'C') ? 'selected' : ''; ?>>Option C</option>
                        <option value="D" <?php echo ($q['correct_answer'] == 'D') ? 'selected' : ''; ?>>Option D</option>
                    </select>
                </div>

                <div style="display: flex; gap: 20px;">
                    <button type="submit" class="btn-verify" style="flex: 2; margin: 0; background: #4CAF50;">Update Question</button>
                    <a href="edit_quiz.php?id=<?php echo $q['quiz_id']; ?>" 
                       style="flex: 1; text-align: center; padding: 15px; text-decoration: none; color: #888; border: 2px solid #eee; border-radius: 10px; font-weight: bold; transition: background 0.3s;"
                       onmouseover="this.style.background='#eee'" 
                       onmouseout="this.style.background='transparent'">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>