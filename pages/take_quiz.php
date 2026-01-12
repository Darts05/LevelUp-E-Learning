<?php include '../includes/header.php'; ?>

<?php
session_start();
include '../db_connect.php';

$quiz_id = $_GET['quiz_id'];
$questions = $conn->query("SELECT * FROM questions WHERE quiz_id = $quiz_id");
$q_array = [];
while($row = $questions->fetch_assoc()) { $q_array[] = $row; }
?>

<!DOCTYPE html>
<html>
<head>
    <title>LevelUp Quiz</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .quiz-container { max-width: 600px; margin: 50px auto; text-align: center; }
        .question-box { display: none; }
        .question-box.active { display: block; animation: fadeIn 0.5s; }
        .option-btn { 
            display: block; width: 100%; padding: 15px; margin: 10px 0; 
            border: 2px solid #ddd; border-radius: 10px; cursor: pointer; background: white;
            transition: 0.3s; font-size: 16px;
        }
        .correct { background-color: #d4edda !important; border-color: #28a745 !important; color: #155724; }
        .wrong { background-color: #f8d7da !important; border-color: #dc3545 !important; color: #721c24; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body>
    <div class="quiz-container">
        <div id="quiz-area">
            <?php foreach($q_array as $index => $q): ?>
                <div class="question-box <?php echo $index === 0 ? 'active' : ''; ?>" id="q-<?php echo $index; ?>">
                    <h3>Question <?php echo $index + 1; ?></h3>
                    <p style="font-size: 20px; margin-bottom: 20px;"><?php echo htmlspecialchars($q['question_text']); ?></p>
                    
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

        function checkAnswer(btn, selected, correct, index) {
            let box = document.getElementById('q-' + index);
            let buttons = box.querySelectorAll('.option-btn');
            buttons.forEach(b => b.disabled = true);

            if (selected === correct) {
                btn.classList.add('correct');
                score++;
            } else {
                btn.classList.add('wrong');
                buttons.forEach(b => {
                    if(b.innerText.trim() === getCorrectText(box, correct)) b.classList.add('correct');
                });
            }

            setTimeout(() => {
                box.classList.remove('active');
                currentQ++;
                if (currentQ < totalQ) {
                    document.getElementById('q-' + currentQ).classList.add('active');
                } else {
                    document.getElementById('score-input').value = score;
                    document.getElementById('final-form').submit();
                }
            }, 1500);
        }

        function getCorrectText(box, letter) {
            if(letter === 'A') return box.querySelectorAll('.option-btn')[0].innerText;
            if(letter === 'B') return box.querySelectorAll('.option-btn')[1].innerText;
            if(letter === 'C') return box.querySelectorAll('.option-btn')[2].innerText;
            if(letter === 'D') return box.querySelectorAll('.option-btn')[3].innerText;
        }
    </script>
</body>
</html>