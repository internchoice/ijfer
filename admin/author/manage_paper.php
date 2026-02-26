<?php
require_once "../config/db.php";
require_once "../config/auth.php";

// Set current page for sidebar
$currentPage = 'manage_paper.php';

/* Fetch all papers */
$stmt = $pdo->query("SELECT * FROM papers ORDER BY submitted_at DESC");
$papers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Papers - IJFER Admin</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Professional Admin CSS -->
    <link rel="stylesheet" href="../assets/css/admin-professional.css">
</head>
<body>
    <?php include '../components/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-file-alt me-3"></i>
                Manage Papers
            </h1>
            <p class="page-subtitle">Review, approve, and manage research paper submissions</p>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-list me-2"></i>All Paper Submissions</h4>
                <div class="d-flex align-items-center">
                    <span class="badge badge-primary me-3"><?php echo count($papers); ?> Total Papers</span>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-sync me-1"></i>Refresh
                        </button>
                    </div>
                </div>
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
                                            <?php else: ?>
                                                <span class="badge badge-secondary"><?php echo ucfirst($paper['status']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td class="actions">
                                            <div class="btn-group-vertical" role="group">
                                                <a href="update_status.php?id=<?php echo $paper['id']; ?>&status=accepted" class="mb-1">
                                                    <button class="btn btn-sm btn-success w-100">
                                                        <i class="fas fa-check me-1"></i>Approve
                                                    </button>
                                                </a>
                                                
                                                <a href="update_status.php?id=<?php echo $paper['id']; ?>&status=rejected" class="mb-1">
                                                    <button class="btn btn-sm btn-danger w-100">
                                                        <i class="fas fa-times me-1"></i>Reject
                                                    </button>
                                                </a>
                                                
                                                <a href="update_status.php?id=<?php echo $paper['id']; ?>&status=under_review" class="mb-1">
                                                    <button class="btn btn-sm btn-info w-100">
                                                        <i class="fas fa-clock me-1"></i>Review
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
                                    <td colspan="10" class="text-center py-5">
                                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Papers Found</h5>
                                        <p class="text-muted">There are no paper submissions at this time.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <?php if(count($papers) > 0): ?>
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="stat-card blue">
                    <div class="card-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h4 class="card-title">Total Papers</h4>
                    <h2 class="card-value"><?php echo count($papers); ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card yellow">
                    <div class="card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4 class="card-title">Pending Review</h4>
                    <h2 class="card-value"><?php echo count(array_filter($papers, function($p) { return $p['status'] == 'submitted' || $p['status'] == 'under_review'; })); ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card green">
                    <div class="card-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h4 class="card-title">Accepted</h4>
                    <h2 class="card-value"><?php echo count(array_filter($papers, function($p) { return $p['status'] == 'accepted'; })); ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card red">
                    <div class="card-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h4 class="card-title">Rejected</h4>
                    <h2 class="card-value"><?php echo count(array_filter($papers, function($p) { return $p['status'] == 'rejected'; })); ?></h2>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
