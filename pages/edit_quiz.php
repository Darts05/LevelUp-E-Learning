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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Edit Quiz Details</h2>
            <a href="my_quizzes.php" style="color: #666; text-decoration: none; font-size: 14px;">← Back to My Quizzes</a>
        </div>
        
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <p style="color: green; font-weight: bold; margin-bottom: 20px; background: #e8f5e9; padding: 10px; border-radius: 5px;">
                ✅ Quiz updated successfully!
            </p>
        <?php endif; ?>

        <form id="editQuizForm" action="update_quiz_header.php" method="POST">
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
            
            <div style="background: #f9f9f9; padding: 25px; border-radius: 12px; border: 1px solid #eee; margin-bottom: 40px;">
                <label style="display:block; margin-bottom:8px; font-weight: bold;">Quiz Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($quiz['title']); ?>" required 
                       style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 6px;">
                
                <label style="display:block; margin-bottom:8px; font-weight: bold;">Category</label>
                <select name="category" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; background: white;">
                    <option value="Programming" <?php if($quiz['category'] == 'Programming') echo 'selected'; ?>>Programming</option>
                    <option value="Science" <?php if($quiz['category'] == 'Science') echo 'selected'; ?>>Science</option>
                    <option value="Mathematics" <?php if($quiz['category'] == 'Mathematics') echo 'selected'; ?>>Mathematics</option>
                    <option value="General Knowledge" <?php if($quiz['category'] == 'General Knowledge') echo 'selected'; ?>>General Knowledge</option>
                </select>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0;">Questions</h3>
                <a href="../add_questions.php?quiz_id=<?php echo $quiz_id; ?>" style="background: #001f3f; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-size: 14px;">+ Add Question</a>
            </div>

            <div style="margin-bottom: 40px;">
                <?php if ($questions->num_rows > 0): ?>
                    <?php while($q = $questions->fetch_assoc()): ?>
                        <div id="qrow-<?php echo $q['id']; ?>" style="background: white; border: 1px solid #eee; padding: 15px; margin-bottom: 12px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: 0.3s;">
                            
                            <span style="color: #333;"><?php echo htmlspecialchars($q['question_text']); ?></span>
                            
                            <div style="display: flex; gap: 15px; align-items: center;">
                                <a href="edit_question.php?id=<?php echo $q['id']; ?>" class="edit-btn-link" style="color: #4CAF50; text-decoration: none; font-weight: 600;">Edit</a>
                                
                                <button type="button" onclick="markForDeletion(<?php echo $q['id']; ?>)" style="color: #ff4b2b; background: none; border: none; font-weight: 600; cursor: pointer; font-family: inherit;">Remove</button>
                                
                                <input type="checkbox" name="delete_ids[]" value="<?php echo $q['id']; ?>" id="delete-<?php echo $q['id']; ?>" style="display: none;">
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color: #999; text-align: center; padding: 20px; background: #fff; border: 1px dashed #ddd; border-radius: 8px;">No questions added yet.</p>
                <?php endif; ?>
            </div>

            <div class="edit-actions-static">
                <p id="saveStatus" style="margin-bottom: 15px; font-size: 14px; color: #888;">Review your changes before saving.</p>
                <div style="display: flex; gap: 15px; align-items: center;">
                    <button type="submit" name="update_quiz" class="save-btn">Save All Changes</button>
                    <a href="my_quizzes.php" class="cancel-link">Cancel and Exit</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        const editForm = document.getElementById('editQuizForm');
        const statusText = document.getElementById('saveStatus');

        // Track regular inputs (title/category)
        editForm.addEventListener('input', (e) => {
            if(e.target.name !== "delete_ids[]") {
                triggerWarning();
            }
        });

        function markForDeletion(id) {
            const row = document.getElementById('qrow-' + id);
            const checkbox = document.getElementById('delete-' + id);
            
            // Toggle the visual state
            if (!checkbox.checked) {
                row.style.opacity = "0.3";
                row.style.background = "#fff5f5";
                row.style.transform = "scale(0.98)";
                checkbox.checked = true;
            } else {
                row.style.opacity = "1";
                row.style.background = "white";
                row.style.transform = "scale(1)";
                checkbox.checked = false;
            }
            
            triggerWarning();
        }

        function triggerWarning() {
            statusText.innerText = "⚠️ You have unsaved changes!";
            statusText.style.color = "#e67e22";
            statusText.style.fontWeight = "bold";
        }
    </script>
</body>
</html>