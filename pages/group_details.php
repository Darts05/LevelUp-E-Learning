<?php
session_start();
include '../db_connect.php';

// 1. Security Guard
if (!isset($_GET['id']) || ($_SESSION['role'] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

$group_id = intval($_GET['id']);
$teacher_id = $_SESSION['user_id'];

// 2. Verify teacher owns this group
$stmt = $conn->prepare("SELECT * FROM groups WHERE id = ? AND teacher_id = ?");
$stmt->bind_param("ii", $group_id, $teacher_id);
$stmt->execute();
$group_info = $stmt->get_result()->fetch_assoc();

if (!$group_info) { 
    die("Access Denied or Group not found."); 
}

// 3. Fetch Students & Their Performance
// Using the combined logic to show quiz counts and average scores
$sql = "SELECT u.full_name, u.email, 
               COUNT(r.id) as quizzes_done, 
               AVG(r.percentage) as avg_score
        FROM group_members gm
        JOIN users u ON gm.student_id = u.id
        LEFT JOIN results r ON u.id = r.user_id
        WHERE gm.group_id = ?
        GROUP BY u.id
        ORDER BY avg_score DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $group_id);
$stmt->execute();
$members = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($group_info['group_name']); ?> | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="background-color: var(--bg-light);">
    <?php $path_prefix = "../"; include '../includes/header.php'; ?>

    <div class="main-container" style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
        
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
            <div>
                <h1 style="color: var(--text-main);"><?php echo htmlspecialchars($group_info['group_name']); ?></h1>
                <p style="color: var(--text-muted);">Class Invite Code: <strong style="color: var(--primary); font-size: 20px;"><?php echo $group_info['group_code']; ?></strong></p>
            </div>
            <a href="my_groups.php" class="btn-secondary-outline" style="text-decoration: none;">← Back to My Groups</a>
        </div>

        
        <div class="sidebar-box" style="width: 100%; padding: 0; overflow: hidden;">
            <table class="stats-table">
                <thead>
                    <tr>
                        <th style="padding: 20px;">Student Information</th>
                        <th style="padding: 20px; text-align: center;">Completed</th>
                        <th style="padding: 20px; text-align: center;">Mastery (Avg)</th>
                        <th style="padding: 20px; text-align: right;">Performance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($members->num_rows > 0): ?>
                        <?php while($row = $members->fetch_assoc()): ?>
                            <tr style="border-top: 1px solid #f0f0f0;">
                                <td style="padding: 20px;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div class="author-avatar"><?php echo substr($row['full_name'], 0, 1); ?></div>
                                        <div>
                                            <strong style="color: var(--text-main); display: block;"><?php echo htmlspecialchars($row['full_name']); ?></strong>
                                            <small style="color: var(--text-muted);"><?php echo htmlspecialchars($row['email']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 20px; text-align: center; font-weight: 600; color: var(--text-main);">
                                    <?php echo $row['quizzes_done']; ?>
                                </td>
                                <td style="padding: 20px; text-align: center;">
                                    <span class="score-pill" style="color: <?php echo ($row['avg_score'] >= 80) ? 'var(--primary-dark)' : (($row['avg_score'] >= 50) ? '#d97706' : 'var(--danger)'); ?>;">
                                        <?php echo $row['avg_score'] !== null ? round($row['avg_score']) . '%' : '0%'; ?>
                                    </span>
                                </td>
                                <td style="padding: 20px; text-align: right;">
                                    <?php if($row['avg_score'] >= 80): ?> 
                                        <span style="background: #E8F5E9; color: #2E7D32; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">⭐ Top Performer</span>
                                    <?php elseif($row['avg_score'] >= 50): ?>
                                        <span style="color: #d97706; font-size: 12px; font-weight: bold;">Growing</span>
                                    <?php else: ?>
                                        <span style="color: #a0aec0; font-size: 12px;">Developing</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="padding: 60px; text-align: center;">
                                <div style="font-size: 40px; margin-bottom: 10px;">👤</div>
                                <p style="color: var(--text-muted);">No students have joined this group yet.</p>
                                <p style="font-size: 13px; color: #cbd5e0;">Share your code <strong><?php echo $group_info['group_code']; ?></strong> to get started.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>