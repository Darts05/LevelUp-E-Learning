<?php
session_start();
include '../db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Define $user_id for the SQL query
$user_id = $_SESSION['user_id'];

// Safeguard: Only allow Teachers (Role 1)
if (($_SESSION['role'] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage My Groups | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="background-color: var(--bg-light);">
    <?php $path_prefix = "../"; include '../includes/header.php'; ?>

    <div class="main-container" style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2>My Classroom Groups</h2>
            <a href="create_group.php" class="btn-verify" style="text-decoration: none;">+ New Group</a>
        </div>

        <div class="browse-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            <?php
            $stmt = $conn->prepare("SELECT * FROM groups WHERE teacher_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
                while($group = $result->fetch_assoc()): ?>
                    <div class="browse-card">
    <span class="card-category">Group ID: #<?php echo $group['id']; ?></span>
    <h3 style="margin: 15px 0;"><?php echo htmlspecialchars($group['group_name']); ?></h3>
    <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">
        Invite Code: <strong style="color: var(--primary-dark);"><?php echo $group['group_code']; ?></strong>
    </p>
    
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="group_details.php?id=<?php echo $group['id']; ?>" class="btn-secondary-outline" style="flex: 1; text-align: center;">View Students</a>
        
        <a href="delete_group.php?id=<?php echo $group['id']; ?>" 
           onclick="return confirm('Are you sure you want to delete this group? All student progress in this group will be unlinked.');" 
           style="color: #dc3545; text-decoration: none; padding: 5px 10px; border: 1px solid #dc3545; border-radius: 8px; font-size: 14px;">
           Delete
        </a>
    </div>
</div>
                <?php endwhile;
            else: ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 50px; background: white; border-radius: 20px; border: 2px dashed #ccc;">
                    <p style="color: var(--text-muted);">You haven't created any groups yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>