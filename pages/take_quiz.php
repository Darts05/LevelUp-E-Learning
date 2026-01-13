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

    <div id="gameOverlay">
        <div class="game-card">
            <h2 class="game-title">🎮 BRAIN BREAK! 🎮</h2>
            <p class="game-instructions">Tap the moving circle 5 times to continue.</p>
            <div id="gameTarget"></div>
            <p style="font-size: 18px; font-weight: bold;">
                Clicks left: <span id="clickCount" style="color: #4CAF50;">5</span>
            </p>
        </div>
    </div>

    <script>
        let currentQ = 0;
        let score = 0;
        let clicksNeeded = 5;
        const totalQ = <?php echo count($q_array); ?>;

        function updateProgress() {
            const percent = ((currentQ) / totalQ) * 100;
            document.getElementById('progress-fill').style.width = percent + '%';
        }

        function checkAnswer(btn, selected, correct, index) {
            let box = document.getElementById('q-' + index);
            let buttons = box.querySelectorAll('.option-btn');
    
            buttons.forEach(b => b.disabled = true);

            if (selected === correct) {
                btn.classList.add('correct');
                score++;
            } else {
                btn.classList.add('wrong');
                const optionMapping = { 'A': 0, 'B': 1, 'C': 2, 'D': 3 };
                buttons[optionMapping[correct]].classList.add('correct');
            }
            setTimeout(() => {
        
                if ((currentQ + 1) % 3 === 0 && currentQ < totalQ - 1) {
                    showMiniGame();
                } else {
                    proceedToNext();
                }
            }, 1000);
        }
        function showMiniGame() {
            const overlay = document.getElementById('gameOverlay');
            const target = document.getElementById('gameTarget');
            const counter = document.getElementById('clickCount');
    
            overlay.style.display = 'flex'; 
    
            clicksNeeded = 5;
            counter.innerText = clicksNeeded;

            target.onclick = function() {
                clicksNeeded--;
                counter.innerText = clicksNeeded;
        
                const x = Math.random() * 200 - 100;
                const y = Math.random() * 200 - 100;
                target.style.transform = `translate(${x}px, ${y}px)`;

                if (clicksNeeded <= 0) {
                    overlay.style.display = 'none';
                    proceedToNext();
                }
            };
        }

        function proceedToNext() {
            let box = document.getElementById('q-' + currentQ);
            box.classList.remove('active');
            currentQ++;
            
            if (currentQ < totalQ) {
                updateProgress();
                document.getElementById('q-' + currentQ).classList.add('active');
            } else {
                document.getElementById('progress-fill').style.width = '100%';
                document.getElementById('score-input').value = score;
                document.getElementById('final-form').submit();
            }
        }

        updateProgress();
    </script>
</body>
</html>