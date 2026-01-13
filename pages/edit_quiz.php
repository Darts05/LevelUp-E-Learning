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

$quiz_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$quiz_query = "SELECT * FROM quizzes WHERE id = $quiz_id AND user_id = $user_id";
$quiz_res = $conn->query($quiz_query);
$quiz = $quiz_res->fetch_assoc();

if (!$quiz) { 
    die("Unauthorized access or quiz not found."); 
}

$questions = $conn->query("SELECT * FROM questions WHERE quiz_id = $quiz_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Quiz | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="main-container" style="max-width: 800px; margin: 40px auto; padding: 20px;">
        <h2>Edit Quiz Details</h2>
        
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <p style="color: green; font-weight: bold;">Quiz header updated successfully!</p>
        <?php endif; ?>

        <form action="update_quiz_header.php" method="POST" style="background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
            
            <label style="display:block; margin-bottom:5px;">Quiz Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($quiz['title']); ?>" required style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">
            
            <label style="display:block; margin-bottom:5px;">Category</label>
            <select name="category" style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="Programming" <?php if($quiz['category'] == 'Programming') echo 'selected'; ?>>Programming</option>
                <option value="Science" <?php if($quiz['category'] == 'Science') echo 'selected'; ?>>Science</option>
                <option value="Mathematics" <?php if($quiz['category'] == 'Mathematics') echo 'selected'; ?>>Mathematics</option>
                <option value="General Knowledge" <?php if($quiz['category'] == 'General Knowledge') echo 'selected'; ?>>General Knowledge</option>
            </select>
            
            <button type="submit" class="login-btn">Update Header</button>
        </form>

        <hr style="margin: 40px 0;">

        <h2>Questions</h2>
        <?php if ($questions->num_rows > 0): ?>
            <?php while($q = $questions->fetch_assoc()): ?>
                <div style="background: white; border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 5px; display: flex; justify-content: space-between; align-items: center;">
                    <span><?php echo htmlspecialchars($q['question_text']); ?></span>
                    <div>
                        <a href="edit_question.php?id=<?php echo $q['id']; ?>" style="color: #007bff; text-decoration: none; font-weight: bold; margin-right: 15px;">Edit</a>
                        
                        <a href="delete_question.php?id=<?php echo $q['id']; ?>&quiz_id=<?php echo $quiz_id; ?>" 
                           onclick="return confirm('Are you sure you want to delete this question?')" 
                           style="color: #dc3545; text-decoration: none; font-weight: bold;">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #666;">No questions added to this quiz yet.</p>
        <?php endif; ?>
        
        <div style="margin-top: 30px; display: flex; gap: 15px;">
            <a href="../add_questions.php?quiz_id=<?php echo $quiz_id; ?>" class="cta-