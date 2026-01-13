<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $q_id = $_GET['id'];
    $q_query = "SELECT * FROM questions WHERE id = $q_id";
    $q_res = $conn->query($q_query);
    $q = $q_res->fetch_assoc();

    if (!$q) {
        die("Question not found.");
    }
} else {
    header("Location: my_quizzes.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Question | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="login-container" style="flex-direction: column; align-items: center; padding: 40px;">
        <form action="update_question_logic.php" method="POST" class="login-form" style="max-width: 500px; width: 100%;">
            <h2>Update Question</h2>
            
            <input type="hidden" name="q_id" value="<?php echo $q_id; ?>">
            <input type="hidden" name="quiz_id" value="<?php echo $q['quiz_id']; ?>">

            <div class="input-group">
                <label>Question Text</label>
                <input type="text" name="question_text" value="<?php echo htmlspecialchars($q['question_text']); ?>" required>
            </div>

            <div class="options-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div>
                    <label>Option A</label>
                    <input type="text" name="option_a" value="<?php echo htmlspecialchars($q['option_a']); ?>" required>
                </div>
                <div>
                    <label>Option B</label>
                    <input type="text" name="option_b" value="<?php echo htmlspecialchars($q['option_b']); ?>" required>
                </div>
                <div>
                    <label>Option C</label>
                    <input type="text" name="option_c" value="<?php echo htmlspecialchars($q['option_c']); ?>" required>
                </div>
                <div>
                    <label>Option D</label>
                    <input type="text" name="option_d" value="<?php echo htmlspecialchars($q['option_d']); ?>" required>
                </div>
            </div>

            <div class="input-group" style="margin-top: 15px;">
                <label>Correct Answer</label>
                <select name="correct_answer">
                    <option value="A" <?php echo ($q['correct_answer'] == 'A') ? 'selected' : ''; ?>>Option A</option>
                    <option value="B" <?php echo ($q['correct_answer'] == 'B') ? 'selected' : ''; ?>>Option B</option>
                    <option value="C" <?php echo ($q['correct_answer'] == 'C') ? 'selected' : ''; ?>>Option C</option>
                    <option value="D" <?php echo ($q['correct_answer'] == 'D') ? 'selected' : ''; ?>>Option D</option>
                </select>
            </div>

            <button type="submit" class="login-btn" style="margin-top: 20px;">Save Changes</button>
            <a href="edit_quiz.php?id=<?php echo $q['quiz_id']; ?>" style="display:block; text-align:center; margin-top:15px; text-decoration:none; color:#666;">Cancel</a>
        </form>
    </div>
</body>
</html>