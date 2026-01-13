<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if a search query exists
$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

if ($query != '') {
    $sql = "SELECT quizzes.*, users.full_name 
            FROM quizzes 
            JOIN users ON quizzes.user_id = users.id 
            WHERE title LIKE '%$query%' OR category LIKE '%$query%'
            ORDER BY created_at DESC";
} else {
    $sql = "SELECT quizzes.*, users.full_name 
            FROM quizzes 
            JOIN users ON quizzes.user_id = users.id 
            ORDER BY created_at DESC";
}
$all_quizzes = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Quizzes | LevelUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main style="padding: 40px 5%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px;">
            <div>
                <h2><?php echo ($query != '') ? 'Search Results for: "' . htmlspecialchars($query) . '"' : 'Public Quizzes'; ?></h2>
                <p style="color: #666;">Explore and learn from the community.</p>
            </div>

            <div class="search-container">
                <form action="browse.php" method="GET" id="searchForm">
                    <div class="search-wrapper">
                        <input type="text" name="query" id="dashboardSearch" placeholder="Search quizzes..." class="expanding-search" value="<?php echo htmlspecialchars($query); ?>">
                        <button type="submit" class="search-trigger">🔍</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="quiz-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px;">
            <?php if($all_quizzes->num_rows > 0): ?>
                <?php while($row = $all_quizzes->fetch_assoc()): ?>
                    <div class="quiz-card" style="background: white; padding: 20px; border-radius: 12px; border: 1px solid #eee; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                        <span class="category-tag" style="font-size: 12px; background: #e8f5e9; color: #2e7d32; padding: 4px 8px; border-radius: 10px;">
                            <?php echo htmlspecialchars($row['category']); ?>
                        </span>
                        <h3 style="margin: 10px 0;"><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p style="font-size: 13px; color: #666; margin-bottom: 15px;">Created by: <b><?php echo htmlspecialchars($row['full_name']); ?></b></p>
                        <a href="take_quiz.php?quiz_id=<?php echo $row['id']; ?>" style="color: #4CAF50; font-weight: bold; text-decoration: none;">Take Quiz →</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                    <p style="color: #888; font-size: 18px;">No quizzes found matching your search.</p>
                    <a href="browse.php" style="color: #4CAF50; text-decoration: underline;">View all quizzes</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        const searchWrapper = document.querySelector('.search-wrapper');
        const searchInput = document.querySelector('#dashboardSearch');

        // Keep it expanded if there is already a search query
        if (searchInput.value.length > 0) {
            searchWrapper.classList.add('active');
        }

        searchInput.addEventListener('focus', () => {   
            searchWrapper.classList.add('active');
        });

        document.addEventListener('click', (e) => {
            if (!searchWrapper.contains(e.target)) {
                if (searchInput.value.length === 0) {
                    searchWrapper.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>