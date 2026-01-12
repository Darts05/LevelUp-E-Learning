<?php
session_start();
include '../db_connect.php';

$q_id = $_GET['id'];
$q_query = "SELECT * FROM questions WHERE id = $q_id";
$q_res = $conn->query($q_query);
$q = $q_res->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
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
                <input type="text" name="option_a" value="<?php echo htmlspecialchars($q['option_a']); ?>" required>
                <input type="text" name="option_b" value="<?php echo htmlspecialchars($q['option_b']); ?>" required>
                <input type="text" name="option_c" value="<?php echo htmlspecialchars($q['option_c']); ?>" required>
                <input type="text" name="option_d" value="<?php echo htmlspecialchars($q['option_d']); ?>" required>
            </div>

            <div class="input-group" style="margin-top: 15px;">
                <label>Correct Answer</label>
                <select name="correct_answer">
                    <option value="A" <?php if($q['correct_answer'] == 'A') echo 'selected'; ?>>Option A</option>
                    <option value="B" <?php if($q['correct_answer'] == 'B') echo 'selected'; ?>>Option B</option>
                    <option value="C" <?php if($q['correct_answer'] == 'C') echo 'selected'; ?>>Option C</option>
                    <option value="D" <?php if($q['correct_answer'] == 'D') echo 'selected'; ?>>Option D</option>
                </select>
            </div>

            <button type="submit" class="login-btn" style="margin-top: 20px;">Save Changes</button>
        </form>
    </div>
</body>
</html>