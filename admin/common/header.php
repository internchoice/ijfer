<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="IJFER Admin Dashboard">
    <meta name="author" content="IJFER">

    <title>IJFER Admin Panel</title>

    <!-- Vendor CSS -->
    <link href="assets/lib/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="assets/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="assets/lib/typicons.font/typicons.css" rel="stylesheet">
    <link href="assets/lib/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">

    <!-- Azia CSS -->
    <link rel="stylesheet" href="assets/css/azia.css">

</head>
<body>

<!-- =========================
     ADMIN NAVBAR
========================= -->

<div class="az-header">
    <div class="container">

        <!-- Logo -->
        <div class="az-header-left">
            <a href="dashboard.php" class="az-logo">
                IJFER<span></span>
            </a>
        </div>

        <!-- Navigation Menu -->
        <div class="az-header-menu">
            <ul class="nav">

                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">
                        <i class="typcn typcn-chart-bar-outline"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="add_news.php" class="nav-link">
                        <i class="typcn typcn-chart-bar-outline"></i> News
                    </a>
                </li>

                <li class="nav-item">
                    <a href="manage-editorial.php" class="nav-link">
                        <i class="typcn typcn-chart-bar-outline"></i> Editorial Board
                    </a>
                </li>

                <li class="nav-item">
                    <a href="author/manage_paper.php" class="nav-link">
                        <i class="typcn typcn-folder"></i> Manage Papers
                    </a>
                </li>

                <li class="nav-item">
                    <a href="message.php" class="nav-link">
                        <i class="typcn typcn-mail"></i> Messages
                    </a>
                </li>

            </ul>
        </div>

        <!-- User Dropdown -->
        <div class="az-header-right">
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin'; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="config/logout.php" class="dropdown-item">
                        <i class="typcn typcn-power"></i> Logout
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>