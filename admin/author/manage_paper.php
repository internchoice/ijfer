<?php
require_once "../config/db.php";
require_once "../config/auth.php";

$currentPage = 'manage_paper.php';

/* Fetch all papers */
$stmt = $pdo->query("SELECT * FROM papers ORDER BY submitted_at DESC");
$papers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Papers - IJFER Admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-professional.css">
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <img src="../assets/img/ijfer.png" class="logo" alt="IJFER Logo">
        <span class="logo-text">IJFER</span>
    </div>
    
    <ul class="sidebar-menu">
        <li class="<?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>">
            <a href="../dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a>
        </li>
        <li><a href="../add_news.php"><i class="fas fa-newspaper"></i><span>Add News</span></a></li>
        <li><a href="../manage_news.php"><i class="fas fa-newspaper"></i><span>Manage News</span></a></li>
        <li><a href="../manage-editorial.php"><i class="fas fa-users"></i><span>Editorial Board</span></a></li>
        <li class="active">
            <a href="manage_paper.php"><i class="fas fa-file-alt"></i><span>Manage Papers</span></a>
        </li>
        <li><a href="../message.php"><i class="fas fa-envelope"></i><span>Messages</span></a></li>
        <li>
            <a href="../config/logout.php" onclick="return confirm('Are you sure you want to logout?')">
                <i class="fas fa-sign-out-alt"></i><span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-file-alt me-3"></i>Manage Papers</h1>
        <p class="page-subtitle">Review, approve, and manage research paper submissions</p>
    </div>
        
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-list me-2"></i>All Paper Submissions</h4>
            <span class="badge badge-primary"><?php echo count($papers); ?> Total Papers</span>
        </div>
            
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paper ID</th>
                            <th>Research Area</th>
                            <th>Title</th>
                            <th>Country</th>
                            <th>Volume</th>
                            <th>Issue</th>
                            <th>Authors</th>
                            <th>Download</th>
                            <th>Submitted At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php if(count($papers) > 0): ?>
                        <?php foreach($papers as $paper): ?>
                            <tr>
                                <td>#<?php echo $paper['id']; ?></td>
                                <td><?php echo htmlspecialchars($paper['paper_id']); ?></td>
                                <td><?php echo htmlspecialchars($paper['research_area']); ?></td>
                                <td><?php echo htmlspecialchars(substr($paper['paper_title'], 0, 50)) . (strlen($paper['paper_title']) > 50 ? '...' : ''); ?></td>
                                <td><?php echo htmlspecialchars($paper['country']); ?></td>

                                <!-- NEW VOLUME COLUMN -->
                                <td>
                                    <?php echo !empty($paper['volume']) 
                                        ? htmlspecialchars($paper['volume']) 
                                        : '<span class="text-muted">Not Assigned</span>'; ?>
                                </td>

                                <!-- NEW ISSUE COLUMN -->
                                <td>
                                    <?php echo !empty($paper['issue']) 
                                        ? htmlspecialchars($paper['issue']) 
                                        : '<span class="text-muted">Not Assigned</span>'; ?>
                                </td>

                                <td>
                                    <?php 
                                        $authors = json_decode($paper['authors'], true);
                                        if($authors) {
                                            foreach($authors as $a) {
                                                echo "<strong>Name:</strong> " . htmlspecialchars($a['name']) . "<br>";
                                                echo "<strong>Email:</strong> " . htmlspecialchars($a['email']) . "<br>";
                                                echo "<strong>Phone:</strong> " . htmlspecialchars($a['phone']) . "<br>";
                                                echo "<strong>Institution:</strong> " . htmlspecialchars($a['institution']) . "<hr>";
                                            }
                                        }
                                    ?>
                                </td>

                                <td>
                                    <a href="../../<?php echo $paper['file_path']; ?>" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                </td>

                                <td><?php echo date("d M Y", strtotime($paper['submitted_at'])); ?></td>

                                <td>
                                    <?php if($paper['status'] == 'submitted'): ?>
                                        <span class="badge badge-warning">Submitted</span>
                                    <?php elseif($paper['status'] == 'accepted'): ?>
                                        <span class="badge badge-success">Accepted</span>
                                    <?php elseif($paper['status'] == 'rejected'): ?>
                                        <span class="badge badge-danger">Rejected</span>
                                    <?php elseif($paper['status'] == 'under_review'): ?>
                                        <span class="badge badge-info">Under Review</span>
                                    <?php endif; ?>
                                </td>

                                <td class="actions">
                                    <div class="btn-group-vertical">

                                        <!-- UPDATED APPROVE BUTTON -->
                                        <a href="update_status.php?id=<?php echo $paper['id']; ?>" class="mb-1">
                                            <button class="btn btn-sm btn-success w-100">
                                                <i class="fas fa-check me-1"></i>
                                                Assign & Approve
                                            </button>
                                        </a>

                                        <a href="update_status.php?id=<?php echo $paper['id']; ?>&status=under_review" class="mb-1">
                                            <button class="btn btn-sm btn-info w-100">
                                                <i class="fas fa-clock me-1"></i>Review
                                            </button>
                                        </a>

                                        <a href="update_status.php?id=<?php echo $paper['id']; ?>&status=rejected" class="mb-1">
                                            <button class="btn btn-sm btn-danger w-100">
                                                <i class="fas fa-times me-1"></i>Reject
                                            </button>
                                        </a>

                                        <a href="delete_paper.php?id=<?php echo $paper['id']; ?>"
                                           onclick="return confirm('Are you sure you want to delete this paper?');">
                                            <button class="btn btn-sm btn-outline-danger w-100">
                                                <i class="fas fa-trash me-1"></i>Delete
                                            </button>
                                        </a>

                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="text-center py-5">
                                <h5 class="text-muted">No Papers Found</h5>
                            </td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>