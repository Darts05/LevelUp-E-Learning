<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: my_quizzes.php");
    exit();
}

$q_id = intval($_GET['id']);

// Fetch existing question data
$query = "SELECT * FROM questions WHERE id = $q_id";
$res = $conn->query($query);
$q = $res->fetch_assoc();

if (!$q) {
    die("Question not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Question | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="main-container" style="max-width: 700px; margin: 40px auto; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2>Edit Question</h2>
            <a href="edit_quiz.php?id=<?php echo $q['quiz_id']; ?>" style="color: #666; text-decoration: none;">← Back to Quiz</a>
        </div>

        <form action="update_question_process.php" method="POST" class="quiz-form">
            <input type="hidden" name="question_id" value="<?php echo $q_id; ?>">
            <input type="hidden" name="quiz_id" value="<?php echo $q['quiz_id']; ?>">

            <div class="input-group" style="margin-bottom: 25px;">
                <label style="font-weight: bold; display: block; margin-bottom: 10px;">Question Text</label>
                <textarea name="question_text" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; height: 100px;"><?php echo htmlspecialchars($q['question_text']); ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div class="input-group">
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;">Option A</label>
                    <input type="text" name="option_a" value="<?php echo htmlspecialchars($q['option_a']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                <div class="input-group">
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;">Option B</label>
                    <input type="text" name="option_b" value="<?php echo htmlspecialchars($q['option_b']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                <div class="input-group">
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;">Option C</label>
                    <input type="text" name="option_c" value="<?php echo htmlspecialchars($q['option_c']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                <div class="input-group">
                    <label style="font-weight: bold; display: block; margin-bottom: 8px;">Option D</label>
                    <input type="text" name="option_d" value="<?php echo htmlspecialchars($q['option_d']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
            </div>

            <div class="input-group" style="margin-bottom: 35px; background: #f0f7f4; padding: 20px; border-radius: 10px; border: 1px solid #c8e6c9;">
                <label style="font-weight: bold; display: block; margin-bottom: 10px; color: #2e7d32;">Correct Answer</label>
                <select name="correct_answer" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; background: white;">
                    <option value="A" <?php if($q['correct_answer'] == 'A') echo 'selected'; ?>>Option A</option>
                    <option value="B" <?php if($q['correct_answer'] == 'B') echo 'selected'; ?>>Option B</option>
                    <option value="C" <?php if($q['correct_answer'] == 'C') echo 'selected'; ?>>Option C</option>
                    <option value="D" <?php if($q['correct_answer'] == 'D') echo 'selected'; ?>>Option D</option>
                </select>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="login-btn" style="flex: 2;">Save Question Changes</button>
                <a href="edit_quiz.php?id=<?php echo $q['quiz_id']; ?>" style="flex: 1; text-align: center; padding: 12px; text-decoration: none; color: #666; border: 1px solid #ddd; border-radius: 6px;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>