<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. Fetch Global Averages
$global_sql = "SELECT COUNT(*) as total, AVG(percentage) as avg_score FROM results WHERE user_id = '$user_id'";
$global_res = $conn->query($global_sql);
$global_stats = $global_res->fetch_assoc();

// 2. Fetch Recent Quizzes for the Chart (Last 10)
// We explicitly select created_at here as well to ensure data availability
$chart_sql = "SELECT r.percentage, q.title 
              FROM results r 
              JOIN quizzes q ON r.quiz_id = q.id 
              WHERE r.user_id = '$user_id' 
              ORDER BY r.id DESC LIMIT 10";
$chart_res = $conn->query($chart_sql);
$recent_data = [];
while($row = $chart_res->fetch_assoc()) {
    $recent_data[] = $row;
}
$recent_data = array_reverse($recent_data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Growth Analytics | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Integrated Statistics CSS */
        .stats-wrapper {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .chart-box {
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            position: relative;
        }

        .bar-chart {
    display: flex;
    align-items: flex-end; /* Aligns bars to the bottom */
    justify-content: space-around;
    height: 300px;
    margin-top: 40px;
    border-bottom: 2px solid #edf2f7;
    position: relative;
    padding-bottom: 5px;
}

        /* Goal Line at 80% Mastery */
        .goal-line {
            position: absolute;
            bottom: 80%;
            left: 0;
            width: 100%;
            border-top: 2px dashed #4CAF50;
            opacity: 0.3;
            z-index: 1;
        }
        .goal-label {
            position: absolute;
            right: 0;
            bottom: 81%;
            font-size: 10px;
            color: #4CAF50;
            font-weight: 700;
        }

        .bar-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
        }

        .bar-fill {
    width: 60%;
    max-width: 35px;
    /* This color makes the bar visible */
    background: linear-gradient(to top, #4CAF50, #81C784); 
    border-radius: 6px 6px 0 0;
    /* Transition creates the "growing" effect when page loads */
    transition: height 1s ease-in-out; 
    position: relative;
    min-height: 2px; /* Ensures even a 0% score shows a tiny line */
}

        .bar-fill:hover {
    filter: brightness(1.1);
    cursor: pointer;
}

        .tooltip {
            position: absolute;
            top: -35px;
            left: 50%;
            transform: translateX(-50%);
            background: #2d3748;
            color: #fff;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.2s;
            pointer-events: none;
        }

        .bar-fill:hover .tooltip { opacity: 1; }

        .bar-label {
            font-size: 11px;
            color: #a0aec0;
            margin-top: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            text-align: center;
        }

        .mini-card {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .stats-table { width: 100%; border-collapse: collapse; }
        .stats-table th { text-align: left; padding: 12px; border-bottom: 1px solid #edf2f7; color: #a0aec0; font-size: 12px; text-transform: uppercase; }
        .stats-table td { padding: 15px 12px; border-bottom: 1px solid #f8fafc; font-size: 14px; }

        @media (max-width: 850px) {
            .stats-wrapper { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body style="background-color: #f8fafc;">
    <?php $path_prefix = "../"; include '../includes/header.php'; ?>

    <div class="stats-wrapper">
        <section>
            <div class="chart-box">
                <h2 style="color: #1a202c; margin-bottom: 5px;">Growth Journey</h2>
                <p style="color: #718096; font-size: 14px;">Accuracy across your last 10 attempts.</p>

                <div class="bar-chart">
    <div class="goal-line" style="position: absolute; bottom: 80%; width: 100%; border-top: 2px dashed #4CAF50; opacity: 0.3;"></div>

    <?php if (count($recent_data) > 0): ?>
        <?php foreach ($recent_data as $data): ?>
            <div class="bar-group" style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end;">
                
                <div class="bar-fill" style="height: <?php echo $data['percentage']; ?>%;">
                    <span class="tooltip"><?php echo round($data['percentage']); ?>%</span>
                </div>
                
                <span class="bar-label" style="font-size: 11px; margin-top: 10px;">
                    <?php echo htmlspecialchars(substr($data['title'], 0, 8)); ?>..
                </span>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color: #a0aec0; margin: auto;">No quiz data found yet.</p>
    <?php endif; ?>
</div>
            </div>

            <div class="sidebar-box" style="width: 100%; margin-top: 30px; background: white; padding: 30px; border-radius: 20px;">
                <h3 style="margin-bottom: 20px; color: #1a202c;">Detailed History</h3>
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>Quiz Title</th>
                            <th>Date Completed</th>
                            <th>Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // FIXED SQL: Explicitly selecting created_at to avoid undefined index error
                        $history_res = $conn->query("SELECT r.percentage, r.score, r.total_questions, r.created_at, q.title 
                                                    FROM results r 
                                                    JOIN quizzes q ON r.quiz_id = q.id 
                                                    WHERE r.user_id = '$user_id' 
                                                    ORDER BY r.id DESC");
                        
                        if ($history_res && $history_res->num_rows > 0):
                            while($row = $history_res->fetch_assoc()): ?>
                                <tr>
                                    <td style="font-weight: 600; color: #2d3748;"><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td style="color: #718096; font-size: 13px;">
                                        <?php 
                                            // Fallback check to prevent "1970" error if date is empty
                                            echo (!empty($row['created_at'])) ? date('M d, Y', strtotime($row['created_at'])) : "Recent"; 
                                        ?>
                                    </td>
                                    <td>
                                        <span class="score-pill" style="color: <?php echo $row['percentage'] >= 80 ? '#2e7d32' : ($row['percentage'] >= 50 ? '#d97706' : '#dc2626'); ?>; font-weight: bold;">
                                            <?php echo round($row['percentage']); ?>%
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; 
                        else: ?>
                            <tr><td colspan="3" style="text-align: center; color: #a0aec0; padding: 40px;">No history found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <aside>
            <div class="mini-card">
                <span style="color: #718096; font-size: 12px; font-weight: 700; text-transform: uppercase;">Average Mastery</span>
                <h2 style="font-size: 42px; color: #4CAF50; margin: 5px 0;"><?php echo round($global_stats['avg_score'] ?? 0); ?>%</h2>
                <div class="progress-container" style="height: 8px;">
                    <div class="progress-fill" style="width: <?php echo $global_stats['avg_score'] ?? 0; ?>%;"></div>
                </div>
            </div>

            <div class="mini-card">
                <span style="color: #718096; font-size: 12px; font-weight: 700; text-transform: uppercase;">Total Quizzes</span>
                <h2 style="font-size: 42px; color: #2d3748; margin: 5px 0;"><?php echo $global_stats['total']; ?></h2>
                <p style="font-size: 13px; color: #a0aec0;">Assessments completed.</p>
            </div>

            <a href="../index.php" class="btn-outline" style="display: block; text-align: center; text-decoration: none; width: 100%; border: 2px solid #e2e8f0; padding: 12px; border-radius: 12px; color: #718096; font-weight: 600;">Return to Dashboard</a>
        </aside>
    </div>
</body>
</html>