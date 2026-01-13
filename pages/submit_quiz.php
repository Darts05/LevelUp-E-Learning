<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_id = $_POST['quiz_id'];
    $user_id = $_SESSION['user_id'];
    
    // Check if any answers were submitted to avoid errors
    $user_answers = isset($_POST['answer']) ? $_POST['answer'] : [];
    $score = 0;
    
    // Get the actual total number of questions for this quiz from the DB
    $count_sql = "SELECT COUNT(*) as total FROM questions WHERE quiz_id = $quiz_id";
    $count_res = $conn->query($count_sql);
    $count_row = $count_res->fetch_assoc();
    $total_questions = $count_row['total'];

    //Grade the Quiz
    foreach ($user_answers as $question_id => $selected_option) {
        $question_id = $conn->real_escape_string($question_id);
        $selected_option = $conn->real_escape_string($selected_option);
        
        $sql = "SELECT correct_answer FROM questions WHERE id = $question_id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if ($row && $row['correct_answer'] == $selected_option) {
            $score++;
        }
    }

    //Save the result to the Database
    $save_sql = "INSERT INTO results (user_id, quiz_id, score, total_questions) 
                 VALUES ('$user_id', '$quiz_id', '$score', '$total_questions')";
    $conn->query($save_sql);

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Result | LevelUp</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body style="background: #f4f7f6; display: flex; flex-direction: column; min-height: 100vh;">
        
        <?php include '../includes/header.php'; ?>

        <div style="flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px;">
            <div class="result-box" style="padding: 50px; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; max-width: 500px; width: 100%;">
                <h1 style="color: #333;">Quiz Completed!</h1>
                <div style="font-size: 64px; margin: 30px 0; color: #4CAF50; font-weight: bold;">
                    <?php echo $score; ?> <span style="font-size: 24px; color: #999;">/ <?php echo $total_questions; ?></span>
                </div>
                
                <p style="font-size: 18px; color: #666; margin-bottom: 30px;">
                    <?php 
                        if ($score == $total_questions && $total_questions > 0) echo "🏆 Amazing! Perfect score!";
                        elseif ($score >= $total_questions / 2) echo "👏 Good job! Keep it up!";
                        else echo "📚 Practice makes perfect. Try again!";
                    ?>
                </p>

                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <a href="leaderboard.php?id=<?php echo $quiz_id; ?>" class="login-btn" style="text-decoration: none; background: #4CAF50; color: white;">View Leaderboard</a>
                    <a href="progress.php" style="color: #007bff; text-decoration: none; font-weight: bold;">Check My Progress</a>
                    <a href="../index.php" style="color: #666; text-decoration: none; margin-top: 10px;">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    header("Location: ../index.php");
    exit();
}
?>