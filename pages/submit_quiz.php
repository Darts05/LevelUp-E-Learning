<?php include '../includes/header.php'; ?>

<?php
session_start();
include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_id = $_POST['quiz_id'];
    $user_answers = $_POST['answer'];
    $score = 0;
    $total_questions = count($user_answers);

    foreach ($user_answers as $question_id => $selected_option) {
        $sql = "SELECT correct_answer FROM questions WHERE id = $question_id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if ($row['correct_answer'] == $selected_option) {
            $score++;
        }
    }

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Result | LevelUp</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body style="display: flex; justify-content: center; align-items: center; height: 100vh; text-align: center;">
        <div class="result-box" style="padding: 40px; background: white; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.1);">
            <h1>Quiz Completed!</h1>
            <p style="font-size: 48px; margin: 20px 0; color: #4CAF50;"><strong><?php echo $score; ?> / <?php echo $total_questions; ?></strong></p>
            <p><?php echo ($score == $total_questions) ? "Amazing! Perfect score!" : "Good job! Keep learning."; ?></p>
            <br>
            <a href="../index.php" class="login-btn" style="text-decoration: none; padding: 10px 20px; background: #007bff; color: white; border-radius: 5px;">Back to Dashboard</a>
        </div>
    </body>
    </html>
    <?php
}
?>