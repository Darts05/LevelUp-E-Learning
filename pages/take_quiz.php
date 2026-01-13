<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['quiz_id'])) {
    header("Location: ../index.php");
    exit();
}

$quiz_id = $conn->real_escape_string($_GET['quiz_id']);
$questions = $conn->query("SELECT * FROM questions WHERE quiz_id = $quiz_id");
$q_array = [];
while($row = $questions->fetch_assoc()) { 
    $q_array[] = $row; 
}

if (count($q_array) == 0) {
    echo "<script>alert('This quiz has no questions yet!'); window.location.href='../index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LevelUp Quiz</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .quiz-container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); text-align: center; }
        .question-box { display: none; }
        .question-box.active { display: block; animation: fadeIn 0.5s; }
        .option-btn { 
            display: block; width: 100%; padding: 15px; margin: 10px 0; 
            border: 2px solid #ddd; border-radius: 10px; cursor: pointer; background: white;
            transition: 0.3s; font-size: 16px; font-family: inherit;
        }
        .option-btn:hover:not(:disabled) { border-color: #4CAF50; background: #f9f9f9; }
        .correct { background-color: #d4edda !important; border-color: #28a745 !important; color: #155724; font-weight: bold; }
        .wrong { background-color: #f8d7da !important; border-color: #dc3545 !important; color: #721c24; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .progress-bar { height: 10px; background: #eee; border-radius: 5px; margin-bottom: 30px; overflow: hidden; }
        #progress-fill { height: 100%; background: #4CAF50; width: 0%; transition: 0.4s; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="quiz-container">
        <div class="progress-bar">
            <div id="progress-fill"></div>
        </div>

        <div id="quiz-area">
            <?php foreach($q_array as $index => $q): ?>
                <div class="question-box <?php echo $index === 0 ? 'active' : ''; ?>" id="q-<?php echo $index; ?>">
                    <p style="color: #888;">Question <?php echo $index + 1; ?> of <?php echo count($q_array); ?></p>
                    <h3 style="font-size: 22px; margin-bottom: 25px;"><?php echo htmlspecialchars($q['question_text']); ?></h3>
                    
                    <button class="option-btn" onclick="checkAnswer(this, 'A', '<?php echo $q['correct_answer']; ?>', <?php echo $index; ?>)"><?php echo htmlspecialchars($q['option_a']); ?></button>
                    <button class="option-btn" onclick="checkAnswer(this, 'B', '<?php echo $q['correct_answer']; ?>', <?php echo $index; ?>)"><?php echo htmlspecialchars($q['option_b']); ?></button>
                    <button class="option-btn" onclick="checkAnswer(this, 'C', '<?php echo $q['correct_answer']; ?>', <?php echo $index; ?>)"><?php echo htmlspecialchars($q['option_c']); ?></button>
                    <button class="option-btn" onclick="checkAnswer(this, 'D', '<?php echo $q['correct_answer']; ?>', <?php echo $index; ?>)"><?php echo htmlspecialchars($q['option_d']); ?></button>
                </div>
            <?php endforeach; ?>
        </div>

        <form id="final-form" action="save_result.php" method="POST" style="display:none;">
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
            <input type="hidden" name="final_score" id="score-input">
            <input type="hidden" name="total_q" value="<?php echo count($q_array); ?>">
        </form>
    </div>

    <script>
        let currentQ = 0;
        let score = 0;
        const totalQ = <?php echo count($q_array); ?>;

        function updateProgress() {
            const percent = ((currentQ + 1) / totalQ) * 100;
            document.getElementById('progress-fill').style.width = percent + '%';
        }

        function checkAnswer(btn, selected, correct, index) {
            let box = document.getElementById('q-' + index);
            let buttons = box.querySelectorAll('.option-btn');
            
            // Disable all buttons so user can't click twice
            buttons.forEach(b => b.disabled = true);

            if (selected === correct) {
                btn.classList.add('correct');
                score++;
            } else {
                btn.classList.add('wrong');
                // Highlight the actual correct answer
                const optionMapping = { 'A': 0, 'B': 1, 'C': 2, 'D': 3 };
                buttons[optionMapping[correct]].classList.add('correct');
            }

            setTimeout(() => {
                box.classList.remove('active');
                currentQ++;
                if (currentQ < totalQ) {
                    updateProgress();
                    document.getElementById('q-' + currentQ).classList.add('active');
                } else {
                    document.getElementById('score-input').value = score;
                    document.getElementById('final-form').submit();
                }
            }, 1500);
        }

        // Initialize progress bar
        updateProgress();
    </script>
</body>
</html>