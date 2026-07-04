<?php
require_once "../config/db.php";
require_once "../config/auth.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid paper ID");
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM papers WHERE id = ?");
$stmt->execute([$id]);
$paper = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paper) {
    die("Paper not found");
}

$authors = json_decode($paper['authors'], true) ?? [];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_status'])) {
    $new_status = $_POST['new_status'];
    if (in_array($new_status, ['accepted', 'rejected', 'under_review'])) {
        $updateStmt = $pdo->prepare("UPDATE papers SET status = ? WHERE id = ?");
        $updateStmt->execute([$new_status, $id]);
        header("Location: review_paper.php?id=$id");
        exit;
    }
}

$current_status = strtolower($paper['status'] ?? 'submitted');
$isFinal = ($current_status === 'accepted' || $current_status === 'rejected');

// Determine badge class
$badgeClass = 'bg-warning';
if ($current_status === 'accepted') {
    $badgeClass = 'bg-success';
} elseif ($current_status === 'rejected') {
    $badgeClass = 'bg-danger';
} elseif ($current_status === 'under_review') {
    $badgeClass = 'bg-info';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Paper #<?php echo $paper['id']; ?> - IJFER Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-professional.css">
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4">
        <i class="fas fa-file-alt me-2"></i> 
        Review Paper: <?php echo htmlspecialchars($paper['paper_id']); ?>
    </h2>

    <?php if ($isFinal): ?>
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-2"></i>
            This paper is already <strong><?php echo ucfirst($current_status); ?></strong>.
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Basic Information</h5>
        </div>
        <div class="card-body">
            <p><strong>Title:</strong> <?php echo htmlspecialchars($paper['paper_title']); ?></p>
            <p><strong>Research Area:</strong> <?php echo htmlspecialchars($paper['research_area']); ?></p>
            <p><strong>Country:</strong> <?php echo htmlspecialchars($paper['country']); ?></p>
            <p><strong>Submitted:</strong> <?php echo date('d M Y h:i A', strtotime($paper['submitted_at'])); ?></p>
            <p><strong>Current Status:</strong> 
                <span class="badge <?php echo $badgeClass; ?>">
                    <?php echo ucfirst($current_status); ?>
                </span>
            </p>
            <p><strong>Abstract:</strong><br><?php echo nl2br(htmlspecialchars($paper['abstract'] ?? 'No abstract provided')); ?></p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Authors</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($authors)): ?>
                <?php foreach ($authors as $author): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <strong><?php echo htmlspecialchars($author['name'] ?? '—'); ?></strong><br>
                        Email: <?php echo htmlspecialchars($author['email'] ?? '—'); ?><br>
                        Phone: <?php echo htmlspecialchars($author['phone'] ?? '—'); ?><br>
                        Institution: <?php echo htmlspecialchars($author['institution'] ?? '—'); ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No authors recorded.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Manuscript File</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($paper['file_path'])): ?>
                <a href="../../<?php echo $paper['file_path']; ?>" target="_blank" class="btn btn-primary">
                    <i class="fas fa-download me-2"></i> Download Manuscript
                </a>
            <?php else: ?>
                <p class="text-danger">No file uploaded.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="text-center mt-5">
        <form method="POST" class="d-inline">
            <button type="submit" name="new_status" value="under_review" 
                    class="btn btn-info btn-lg mx-2 <?php echo $isFinal ? 'disabled' : ''; ?>" 
                    <?php echo $isFinal ? 'disabled' : ''; ?>>
                <i class="fas fa-clock me-2"></i> Under Review
            </button>
        </form>

        <form method="POST" class="d-inline">
            <button type="submit" name="new_status" value="accepted" 
                    class="btn btn-success btn-lg mx-2 <?php echo $isFinal ? 'disabled' : ''; ?>" 
                    <?php echo $isFinal ? 'disabled' : ''; ?>>
                <i class="fas fa-check me-2"></i> Approve
            </button>
        </form>

        <form method="POST" class="d-inline">
            <button type="submit" name="new_status" value="rejected" 
                    class="btn btn-danger btn-lg mx-2 <?php echo $isFinal ? 'disabled' : ''; ?>" 
                    <?php echo $isFinal ? 'disabled' : ''; ?>>
                <i class="fas fa-times me-2"></i> Reject
            </button>
        </form>

        <a href="manage_paper.php" class="btn btn-secondary btn-lg mx-2 mt-3 mt-md-0">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>