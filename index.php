<?php
session_start();
include 'db_connect.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LevelUp | Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-color: #f8f9fa;">
    <?php include 'includes/header.php'; ?>
    
    <main style="display: grid; grid-template-columns: 2.5fr 1fr; gap: 30px; padding: 40px 5%; max-width: 1400px; margin: 0 auto;">
        
        <section>
            <div class="hero" style="background: linear-gradient(135deg, #4CAF50, #2E7D32); color: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h1 style="margin: 0;">Ready to <span style="color: #FFD700;">LevelUp?</span></h1>
                
                <?php if ($user_role == 1): ?>
                    <p style="margin-top: 10px; opacity: 0.9;">Welcome back, Teacher. Manage your classrooms or create new challenges for your students.</p>
                    <div style="margin-top: 20px;">
                        <a href="pages/create_quiz.php" style="background: white; color: #2E7D32; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-right: 10px;">+ Create Quiz</a>
                        <a href="pages/my_groups.php" style="background: rgba(255,255,255,0.2); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; border: 1px solid white;">Manage Groups</a>
                    </div>
                <?php else: ?>
                    <p style="margin-top: 10px; opacity: 0.9;">Select a quiz from your joined classes or explore public topics.</p>
                    <div style="margin-top: 20px;">
                        <a href="pages/join_group.php" style="background: white; color: #2E7D32; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">+ Join a Class</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div id="featured">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="margin: 0; color: #333;">Available Quizzes</h2>
                    <a href="pages/browse.php" style="color: #4CAF50; text-decoration: none; font-size: 14px; font-weight: bold;">Browse All →</a>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                    <?php 
                    /** * LOGIC: 
                     * 1. Show 'open' visibility quizzes.
                     * 2. Show quizzes assigned to a group the student has joined.
                     * 3. Show a teacher their own quizzes (including private/drafts).
                     */
                    $sql = "SELECT DISTINCT q.*, u.full_name as teacher_name 
                            FROM quizzes q
                            JOIN users u ON q.user_id = u.id
                            LEFT JOIN group_members gm ON q.group_id = gm.group_id AND gm.student_id = '$user_id'
                            WHERE q.visibility = 'open' 
                            OR gm.student_id = '$user_id' 
                            OR q.user_id = '$user_id'
                            ORDER BY q.created_at DESC";
                    
                    $quizzes = $conn->query($sql);
                    
                    if($quizzes && $quizzes->num_rows > 0):
                        while($row = $quizzes->fetch_assoc()): ?>
                            <div class="quiz-card" style="background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid #4CAF50; position: relative; transition: transform 0.2s;">
                                
                                <div style="position: absolute; top: 15px; right: 15px; display: flex; gap: 5px;">
                                    <?php if($row['is_published'] == 0): ?>
                                        <span style="font-size: 10px; background: #FFF3E0; color: #EF6C00; padding: 3px 7px; border-radius: 4px; font-weight: bold;">DRAFT</span>
                                    <?php endif; ?>
                                    <?php if($row['visibility'] == 'link'): ?>
                                        <span title="Code Required" style="font-size: 12px;">🔑</span>
                                    <?php endif; ?>
                                </div>

                                <small style="color: #4CAF50; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; font-size: 11px;">
                                    <?php echo htmlspecialchars($row['category']); ?>
                                </small>
                                
                                <h3 style="margin: 10px 0; font-size: 19px; color: #333; min-height: 46px;">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </h3>
                                
                                <p style="color: #999; font-size: 13px; margin-bottom: 20px;">By <?php echo htmlspecialchars($row['teacher_name']); ?></p>
                                
                                <a href="pages/take_quiz.php?quiz_id=<?php echo $row['id']; ?>" class="btn-verify" style="display: block; text-align: center; text-decoration: none; padding: 12px; background: #4CAF50; color: white; border-radius: 8px; font-weight: bold;">
                                    <?php echo ($user_role == 1 && $row['user_id'] == $user_id) ? "Preview" : "Start Quiz"; ?>
                                </a>
                            </div>
                        <?php endwhile; 
                    else: ?>
                        <div style="grid-column: 1 / -1; text-align: center; padding: 50px; background: white; border-radius: 15px; border: 2px dashed #ddd;">
                            <p style="color: #888; font-size: 16px;">No quizzes found for your groups. Join a class to see more!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <aside>
            <div style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #eee;">
                <h3 style="margin-bottom: 20px; color: #333; display: flex; align-items: center; gap: 10px;">
                    📊 Quick Stats
                </h3>
                
                <?php 
                // Fetch stats for the sidebar
                $stats_res = $conn->query("SELECT COUNT(*) as total, AVG(percentage) as avg_p FROM results WHERE user_id = '$user_id'");
                $stats = $stats_res->fetch_assoc();
                ?>

                <div style="margin-bottom: 20px;">
                    <p style="color: #888; font-size: 13px; margin-bottom: 5px;">Quizzes Completed</p>
                    <span style="font-size: 32px; font-weight: bold; color: #4CAF50;"><?php echo $stats['total']; ?></span>
                </div>

                <?php if($user_role == 0): // Students see accuracy ?>
                <div style="margin-bottom: 25px;">
                    <p style="color: #888; font-size: 13px; margin-bottom: 5px;">Average Accuracy</p>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 32px; font-weight: bold; color: #2196F3;"><?php echo round($stats['avg_p'] ?? 0); ?>%</span>
                    </div>
                    <div style="width: 100%; height: 6px; background: #eee; border-radius: 3px; margin-top: 10px;">
                        <div style="width: <?php echo $stats['avg_p'] ?? 0; ?>%; height: 100%; background: #2196F3; border-radius: 3px;"></div>
                    </div>
                </div>
                <?php endif; ?>

                <a href="pages/statistics.php" style="display: block; text-align: center; padding: 12px; background: #f0f2f5; color: #333; text-decoration: none; border-radius: 10px; font-size: 14px; font-weight: bold; transition: background 0.3s;">
                    View Full Report →
                </a>
            </div>

            <?php if($user_role == 1): ?>
                <div style="margin-top: 20px; background: #E8F5E9; padding: 20px; border-radius: 20px; border: 1px solid #C8E6C9;">
                    <h4 style="color: #2E7D32; margin-top: 0;">Teacher Tip 💡</h4>
                    <p style="font-size: 13px; color: #4E342E; line-height: 1.5;">Assign your quizzes to a <strong>Group</strong> to keep them private to your students!</p>
                </div>
            <?php endif; ?>
        </aside>

    </main>

    <?php if (isset($_SESSION['show_welcome'])): ?>
        <script>
            // A more modern welcome instead of alert if you prefer
            console.log("Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!");
            <?php unset($_SESSION['show_welcome']); ?>
        </script>
    <?php endif; ?>
</body>
</html>