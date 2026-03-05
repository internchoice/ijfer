<?php
require_once "../config/db.php";
require_once "../config/auth.php";

$currentPage = 'manage_paper.php';

// Auto-update status to "under_review" if >24 hours and still "submitted"
$updateStmt = $pdo->prepare("
    UPDATE papers 
    SET status = 'under_review' 
    WHERE status = 'submitted' 
    AND submitted_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)
");
$updateStmt->execute();

// Fetch all papers after update
$stmt = $pdo->query("SELECT * FROM papers ORDER BY submitted_at DESC");
$papers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Papers - IJFER Admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-professional.css">

    <style>
        .table-responsive { max-height: 70vh; overflow: auto; }
        .admin-table thead th {
            position: sticky;
            top: 0;
            background: #0b1f3b;
            color: white;
            z-index: 10;
            border-bottom: 2px solid #1a365d;
        }
        .btn:disabled, .btn.disabled { opacity: 0.55; cursor: not-allowed; pointer-events: none; }
        .btn-group-vertical .btn { margin-bottom: 6px; min-width: 140px; text-align: left; }
        #typedPaperId { user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; }

        /* Search bar styles */
        #searchInput { max-width: 400px; }
        .no-results { text-align: center; padding: 2rem; color: #6c757d; }
    </style>
</head>
<body>

<!-- Sidebar unchanged -->
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
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h4 class="mb-0"><i class="fas fa-list me-2"></i>All Paper Submissions</h4>
            <span class="badge bg-primary"><?php echo count($papers); ?> Total Papers</span>
            
            <!-- Search Bar -->
            <div class="input-group" style="max-width: 400px;">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Search by Paper ID, Title, Area, Country, Authors, Status...">
            </div>
        </div>
            
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="admin-table table table-bordered table-hover" id="papersTable">
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
                        <?php foreach($papers as $paper): 
                            $status = strtolower($paper['status'] ?? 'submitted');
                            $isRejected = ($status === 'rejected');
                            $isAccepted = ($status === 'accepted');
                            // Prepare searchable text for each row
                            $searchable = strtolower(
                                $paper['paper_id'] . ' ' .
                                $paper['paper_title'] . ' ' .
                                $paper['research_area'] . ' ' .
                                $paper['country'] . ' ' .
                                ($paper['status'] ?? 'submitted') . ' ' .
                                implode(' ', array_map(fn($a) => ($a['name'] ?? '') . ' ' . ($a['email'] ?? ''), json_decode($paper['authors'], true) ?? []))
                            );
                        ?>
                            <tr data-search="<?php echo htmlspecialchars($searchable); ?>" data-paper-id="<?php echo $paper['id']; ?>">
                                <td>#<?php echo $paper['id']; ?></td>
                                <td><?php echo htmlspecialchars($paper['paper_id']); ?></td>
                                <td><?php echo htmlspecialchars($paper['research_area']); ?></td>
                                <td title="<?php echo htmlspecialchars($paper['paper_title']); ?>">
                                    <?php echo htmlspecialchars(substr($paper['paper_title'], 0, 50)) . (strlen($paper['paper_title']) > 50 ? '...' : ''); ?>
                                </td>
                                <td><?php echo htmlspecialchars($paper['country']); ?></td>
                                <td><?php echo htmlspecialchars($paper['volume'] ?: 'Not Assigned'); ?></td>
                                <td><?php echo htmlspecialchars($paper['issue'] ?: 'Not Assigned'); ?></td>

                                <td>
                                    <?php 
                                    $authors = json_decode($paper['authors'], true);
                                    if ($authors && is_array($authors)) {
                                        foreach ($authors as $a) {
                                            echo "<strong>" . htmlspecialchars($a['name'] ?? '—') . "</strong><br>";
                                            echo "<small>Email: " . htmlspecialchars($a['email'] ?? '—') . "</small><br>";
                                            echo "<small>Phone: " . htmlspecialchars($a['phone'] ?? '—') . "</small><br>";
                                            echo "<small>Institution: " . htmlspecialchars($a['institution'] ?? '—') . "</small><hr style='margin:4px 0;'>";
                                        }
                                    } else {
                                        echo "<span class='text-muted'>No authors</span>";
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php if (!empty($paper['file_path'])): ?>
                                    <a href="../../<?php echo $paper['file_path']; ?>" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <?php else: ?>
                                    <span class="text-muted">No file</span>
                                    <?php endif; ?>
                                </td>

                                <td><?php echo date("d M Y", strtotime($paper['submitted_at'])); ?></td>

                                <td>
                                    <?php if($status == 'submitted'): ?>
                                        <span class="badge badge-warning">Submitted</span>
                                    <?php elseif($status == 'accepted'): ?>
                                        <span class="badge badge-success">Accepted</span>
                                    <?php elseif($status == 'rejected'): ?>
                                        <span class="badge badge-danger">Rejected</span>
                                    <?php elseif($status == 'under_review'): ?>
                                        <span class="badge badge-info">Under Review</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Pending</span>
                                    <?php endif; ?>
                                </td>

                                <td class="actions">
                                    <div class="btn-group-vertical" data-paper-id="<?php echo $paper['id']; ?>">
                                        <a href="update_status.php?id=<?php echo $paper['id']; ?>&status=accepted"
                                           class="mb-1 action-link <?php echo $isRejected || $isAccepted ? 'disabled' : ''; ?>">
                                            <button class="btn btn-sm btn-success w-100" 
                                                    <?php echo $isRejected || $isAccepted ? 'disabled' : ''; ?>>
                                                <i class="fas fa-check me-1"></i> Approve
                                            </button>
                                        </a>

                                        <a href="review_paper.php?id=<?php echo $paper['id']; ?>" 
                                           class="mb-1 action-link <?php echo $isRejected || $isAccepted ? 'disabled' : ''; ?>">
                                            <button class="btn btn-sm btn-info w-100" 
                                                    <?php echo $isRejected || $isAccepted ? 'disabled' : ''; ?>>
                                                <i class="fas fa-eye me-1"></i> Review
                                            </button>
                                        </a>

                                        <a href="update_status.php?id=<?php echo $paper['id']; ?>&status=rejected"
                                           class="mb-1 action-link <?php echo $isRejected || $isAccepted ? 'disabled' : ''; ?>">
                                            <button class="btn btn-sm btn-danger w-100" 
                                                    <?php echo $isRejected || $isAccepted ? 'disabled' : ''; ?>>
                                                <i class="fas fa-times me-1"></i> Reject
                                            </button>
                                        </a>

                                        <button type="button" class="btn btn-sm btn-outline-danger w-100 delete-paper-btn"
                                                data-id="<?php echo $paper['id']; ?>"
                                                data-paper-id="<?php echo htmlspecialchars($paper['paper_id']); ?>">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
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
                <div id="noResults" class="no-results" style="display:none;">
                    <h5>No matching papers found</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal - Step 1 -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Are you sure you want to delete?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="fw-bold">This will permanently delete the paper and all related data.</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="proceedToTypeBtn">Yes, Proceed</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Step 2: Type Paper ID -->
<div class="modal fade" id="typeConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Type Paper ID to Confirm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Type the exact Paper ID below to confirm deletion:</p>
                <p class="fw-bold text-primary mb-3" id="modalPaperIdDisplay"></p>
                
                <input type="text" class="form-control" id="typedPaperId" placeholder="Type Paper ID here" autocomplete="off" 
                       onpaste="return false;" oncopy="return false;" oncut="return false;" oncontextmenu="return false;">
                <div id="typeError" class="alert alert-danger mt-3" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="finalDeleteBtn">Confirm & Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Delete flow
let currentPaperId = null;
let currentPaperIdText = '';

document.querySelectorAll('.delete-paper-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        currentPaperId = this.getAttribute('data-id');
        currentPaperIdText = this.getAttribute('data-paper-id');

        new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
    });
});

document.getElementById('proceedToTypeBtn').addEventListener('click', function() {
    bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();

    document.getElementById('modalPaperIdDisplay').textContent = currentPaperIdText;
    document.getElementById('typedPaperId').value = '';
    document.getElementById('typeError').style.display = 'none';

    new bootstrap.Modal(document.getElementById('typeConfirmModal')).show();
});

document.getElementById('finalDeleteBtn').addEventListener('click', function() {
    const typed = document.getElementById('typedPaperId').value.trim();

    if (typed === '') {
        document.getElementById('typeError').textContent = 'Please type the Paper ID.';
        document.getElementById('typeError').style.display = 'block';
        return;
    }

    if (typed !== currentPaperIdText) {
        document.getElementById('typeError').textContent = 'Paper ID does not match.';
        document.getElementById('typeError').style.display = 'block';
        return;
    }

    if (confirm('Final warning: Delete paper ' + currentPaperIdText + '?')) {
        window.location.href = 'delete_paper.php?id=' + currentPaperId;
    }
});

// Search bar - client-side filtering
document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#papersTable tbody tr');
    let found = false;

    rows.forEach(row => {
        const searchable = row.getAttribute('data-search') || '';
        if (searchable.includes(filter)) {
            row.style.display = '';
            found = true;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('noResults').style.display = found ? 'none' : 'block';
});
</script>

</body>
</html>