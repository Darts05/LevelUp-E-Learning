<?php include '../includes/header.php'; ?>

<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch only quizzes created by this user
$sql = "SELECT * FROM quizzes WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Quizzes | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="main-container" style="max-width: 900px; margin: 40px auto; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>My Published Quizzes</h1>
            <a href="../create_quiz.php" class="login-btn" style="background: #28a745; text-decoration: none;">+ Create New</a>
        </div>
        <hr>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background: white;">
            <thead>
                <tr style="background: #f4f4f4; text-align: left;">
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">Quiz Title</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">Category</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">Created Date</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['title']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['category']); ?></td>
                            <td style="padding: 12px;"><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="edit_quiz.php?id=<?php echo $row['id']; ?>" style="color: #007bff; margin-right: 10px;">Edit</a>
                                <a href="delete_quiz.php?id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('Are you sure? This will delete all questions and scores for this quiz.')" 
                                   style="color: #dc3545; text-decoration: none; font-weight: bold;">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="padding: 20px; text-align: center;">You haven't created any quizzes yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <br>
        <a href="../index.php" style="text-decoration: none; color: #666;">← Back to Dashboard</a>
    </div>
</body>
</html>