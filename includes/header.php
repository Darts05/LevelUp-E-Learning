<?php
$path_prefix = file_exists('db_connect.php') ? '' : '../';
?>
<style>
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 5%;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .logo { display: flex; align-items: center; gap: 10px; }
    .nav-links { display: flex; list-style: none; align-items: center; gap: 20px; }
    .nav-links a { text-decoration: none; color: #333; transition: 0.3s; }
    .nav-links a:hover { color: #4CAF50; }
    .user-greeting { color: #4CAF50; font-weight: bold; }
    .logout-btn {
        background-color: #ff4b2b;
        color: white !important;
        padding: 8px 15px;
        border-radius: 5px;
    }
    .logout-btn:hover { background-color: #e63e1f; }
</style>

<header>
    <nav class="navbar">
        <div class="logo">
            <img src="<?php echo $path_prefix; ?>assets/LevelUp-Logo.png" alt="LevelUp Logo" height="50">
            <span style="font-size: 24px; font-weight: bold;">LevelUp</span>
        </div>
        <ul class="nav-links">
            <li><a href="<?php echo $path_prefix; ?>index.php">Dashboard</a></li>
            <li><a href="<?php echo $path_prefix; ?>pages/search.html">Search Quizzes</a></li>
            <li><a href="<?php echo $path_prefix; ?>pages/about.html">About</a></li>
            
            <?php if(isset($_SESSION['user_name'])): ?>
                <li><a href="<?php echo $path_prefix; ?>pages/my_quizzes.php">My Quizzes</a></li>
                <li class="user-greeting">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</li>
                <li><a href="<?php echo $path_prefix; ?>logout.php" class="logout-btn">Logout</a></li>
            <?php else: ?>
                <li><a href="<?php echo $path_prefix; ?>pages/login.html">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>