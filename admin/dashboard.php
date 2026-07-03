<?php
require_once "config/db.php";
require_once "config/auth.php";

// Set current page for sidebar
$currentPage = 'dashboard.php';

/* =========================
   PAPERS COUNT
========================= */
$stmt = $pdo->query("SELECT COUNT(*) FROM papers");
$totalPapers = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM papers WHERE status = 'Pending'");
$pendingPapers = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM papers WHERE status = 'Accepted'");
$approvedPapers = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM papers WHERE status = 'Rejected'");
$rejectedPapers = $stmt->fetchColumn();

/* =========================
   CONTACT MESSAGES COUNT
========================= */
$stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages");
$totalMessages = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'New'");
$newMessages = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
$latestMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - IJFER</title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon.png">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Professional Admin CSS -->
    <link rel="stylesheet" href="assets/css/admin-professional.css">
</head>
<body>
    <?php include 'components/sidebar.php'; ?>



<!-- ===================== MAIN CONTENT ===================== -->
<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-chart-line"></i>
            Dashboard Overview
        </h1>
        <p class="page-subtitle">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>👋</p>
    </div>

    <?php if ($newMessages > 0): ?>
        <div class="alert alert-danger">
            <i class="fas fa-bell"></i>
            You have <?php echo $newMessages; ?> new enquiries!
        </div>
    <?php endif; ?>

    <!-- ========== DASHBOARD CARDS ========== -->
    <div class="dashboard-cards">
        <div class="stat-card primary">
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="count"><?php echo $totalPapers; ?></div>
            <div class="label">Total Papers</div>
        </div>

        <div class="stat-card warning">
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="count"><?php echo $pendingPapers; ?></div>
            <div class="label">Pending</div>
        </div>

        <div class="stat-card success">
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="count"><?php echo $approvedPapers; ?></div>
            <div class="label">Approved</div>
        </div>

        <div class="stat-card danger">
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="count"><?php echo $rejectedPapers; ?></div>
            <div class="label">Rejected</div>
        </div>

        <div class="stat-card primary">
            <div class="icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="count"><?php echo $totalMessages; ?></div>
            <div class="label">Total Messages</div>
        </div>

        <div class="stat-card warning">
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="count"><?php echo $newMessages; ?></div>
            <div class="label">New Messages</div>
        </div>
    </div>

    <!-- ========== LATEST MESSAGES TABLE ========== -->
    <div class="card">
        <div class="card-header">
            <h4>
                <i class="fas fa-list me-2"></i>
                Latest Enquiries
            </h4>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($latestMessages) > 0): ?>
                            <?php foreach ($latestMessages as $message): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($message['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($message['email']); ?></td>
                                    <td>
                                        <?php if ($message['status'] == 'New'): ?>
                                            <span class="badge badge-danger">New</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Read</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date("d M Y", strtotime($message['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No messages found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> <!-- /.main-content -->

</body>
</html>