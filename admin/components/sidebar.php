<?php
// Get current page for active state
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <div class="sidebar-header">
        <img src="../assets/images/ijfer.png" class="logo" alt="IJFER Logo">
        <span class="logo-text">IJFER</span>
    </div>
    
    <ul class="sidebar-menu">
        <li class="<?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>">
            <a href="dashboard.php">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li class="<?php echo ($currentPage == 'add_news.php') ? 'active' : ''; ?>">
            <a href="add_news.php">
                <i class="fas fa-newspaper"></i>
                <span>Add News</span>
            </a>
        </li>

        <li class="<?php echo ($currentPage == 'manage_news.php') ? 'active' : ''; ?>">
            <a href="manage_news.php">
                <i class="fas fa-newspaper"></i>
                <span>Manage News</span>
            </a>
        </li>
        
        <li class="<?php echo ($currentPage == 'manage-editorial.php') ? 'active' : ''; ?>">
            <a href="manage-editorial.php">
                <i class="fas fa-users"></i>
                <span>Editorial Board</span>
            </a>
        </li>
        
        <li>
            <a href="author/manage_paper.php">
                <i class="fas fa-file-alt"></i>
                <span>Manage Papers</span>
            </a>
        </li>
        
        <li class="<?php echo ($currentPage == 'message.php' || $currentPage == 'view_message.php') ? 'active' : ''; ?>">
            <a href="message.php">
                <i class="fas fa-envelope"></i>
                <span>Messages</span>
            </a>
        </li>
        
        <li>
            <a href="config/logout.php" onclick="return confirm('Are you sure you want to logout?')">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>