<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LevelUp | Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .user-greeting {
            color: #4CAF50;
            font-weight: bold;
            margin-right: 15px;
        }
        .logout-btn {
            background-color: #ff4b2b;
            color: white !important;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: #e63e1f;
        }
    </style>
</head>
<body>

    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="assets/LevelUp-Logo.png" alt="LevelUp Logo" height="50">
                <span>LevelUp</span>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="pages/search.html">Search Quizzes</a></li>
                <li><a href="pages/about.html">About</a></li>
                
                <li class="user-greeting">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</li>
                <li><a href="logout.php" class="logout-btn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>Welcome back, <span class="highlight"><?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span></h1>
            <p>Your learning journey continues. Ready to beat your high score today?</p>
            
            <div class="hero-btns">
                <a href="pages/search.html" class="cta-main">Start a Quiz</a>
                <a href="#featured" class="cta-sub">View My Progress</a>
            </div>
        </section>

        <section id="featured" class="content-section" style="padding: 50px; text-align: center;">
            <h2>Recent Quizzes</h2>
            <p>Your database content will appear here soon.</p>
        </section>
    </main>

</body>
</html>