<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

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
    <?php $path_prefix = "../"; ?>
    <?php include '../includes/header.php'; ?>

    <div class="main-container" style="max-width: 900px; margin: 40px auto; padding: 20px;">
        
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div style="background: #fee2e2; color: #dc2626; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                Quiz successfully deleted.
            </div>
        <?php endif; ?>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>My Published Quizzes</h1>
            <a href="../create_quiz.php" class="login-btn" style="background: #28a745; text-decoration: none; padding: 10px 20px; color: white; border-radius: 5px;">+ Create New</a>
        </div>
        <hr>

        <?php if ($result->num_rows == 0): ?>
            <div style="text-align: center; padding: 60px; background: #fff; border: 2px dashed #ddd; border-radius: 10px; margin-top: 20px;">
                <p style="color: #888; font-size: 18px;">You haven't created any quizzes yet!</p>
                <p style="color: #aaa; margin-bottom: 20px;">Share your knowledge with others by creating your first quiz.</p>
                <a href="../create_quiz.php" class="cta-main" style="text-decoration: none; background: #4CAF50; color: white; padding: 10px 25px; border-radius: 5px;">Get Started</a>
            </div>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                <thead>
                    <tr style="background: #f4f4f4; text-align: left;">
                        <th style="padding: 15px; border-bottom: 2px solid #ddd;">Quiz Title</th>
                        <th style="padding: 15px; border-bottom: 2px solid #ddd;">Category</th>
                        <th style="padding: 15px; border-bottom: 2px solid #ddd;">Created Date</th>
                        <th style="padding: 15px; border-bottom: 2px solid #ddd; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px; font-weight: 500;"><?php echo htmlspecialchars($row['title']); ?></td>
                            <td style="padding: 15px;"><span style="background: #e9ecef; padding: 4px 10px; border-radius: 12px; font-size: 12px;"><?php echo htmlspecialchars($row['category']); ?></span></td>
                            <td style="padding: 15px; color: #666;"><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="edit_quiz.php?id=<?php echo $row['id']; ?>" style="color: #007bff; text-decoration: none; margin-right: 15px; font-weight: bold;">Edit</a>
                                <a href="delete_quiz.php?id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('Are you sure? This will delete all questions and scores for this quiz.')" 
                                   style="color: #dc3545; text-decoration: none; font-weight: bold;">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <br>
        <a href="../index.php" style="text-decoration: none; color: #666; font-size: 14px;">← Back to Dashboard</a>
    </div>
</body>
</html>