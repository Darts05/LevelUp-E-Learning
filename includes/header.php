<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$path_prefix = file_exists('db_connect.php') ? '' : '../';

if (!isset($conn)) {
    include $path_prefix . 'db_connect.php';
}
?>

<link rel="stylesheet" href="<?php echo $path_prefix; ?>css/style.css">

<header>
    <nav class="navbar">
        <div class="logo">
            <a href="<?php echo $path_prefix; ?>index.php">
                <img src="<?php echo $path_prefix; ?>assets/LevelUp-Logo.png" alt="LevelUp Logo" height="50">
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="<?php echo $path_prefix; ?>index.php">Dashboard</a></li>
    
            <?php if(isset($_SESSION['user_name'])): ?>
                <li><a href="<?php echo $path_prefix; ?>pages/my_quizzes.php">My Quizzes</a></li>
                <li><a href="<?php echo $path_prefix; ?>pages/browse.php">Browse Quizzes</a></li>
                <li><a href="<?php echo $path_prefix; ?>pages/leaderboard.php">Leaderboard</a></li>
        
                <li class="user-dropdown">
                    <button class="dropbtn">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! ▾</button>
                    <div class="dropdown-content">
                        <a href="<?php echo $path_prefix; ?>logout.php">Logout</a>
                        <a href="<?php echo $path_prefix; ?>delete_account.php" 
                           class="delete-acc"
                           onclick="return confirm('CRITICAL WARNING: This will permanently delete your account and all your quizzes. Proceed?')">
                           Delete Account
                        </a>
                    </div>
                </li>
            <?php else: ?>
                <li><a href="<?php echo $path_prefix; ?>login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>