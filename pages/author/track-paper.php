<?php
require_once "../../admin/config/db.php";

$paperData = null;
$errorMessage = "";
$searchedPaperId = '';  // This will hold the ID to show in input

// 1. Prefer POST (manual form submit) first
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchedPaperId = trim($_POST['paper_id'] ?? '');
}

// 2. If no POST, but ID is in URL (from success page link), use GET
if (empty($searchedPaperId) && isset($_GET['paper_id'])) {
    $searchedPaperId = trim($_GET['paper_id']);
}

// Now process the search logic
if ($_SERVER["REQUEST_METHOD"] == "POST" || (!empty($searchedPaperId) && isset($_GET['paper_id']))) {

    $paperIdToSearch = $searchedPaperId;

    if (!empty($paperIdToSearch)) {

        $stmt = $pdo->prepare("SELECT * FROM papers WHERE paper_id = :paper_id");
        $stmt->execute([':paper_id' => $paperIdToSearch]);

        $paperData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$paperData) {
            $errorMessage = "Paper ID not found. Please check and try again.";
        }

    } else {
        $errorMessage = "Please enter a Paper ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Track Paper - IJFER</title>

<link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../../assets/css/style.css">

<style>
  /* Attractive Pay button - blue theme for accepted */
  .pay-btn {
    background: #0d6efd;              /* Bootstrap primary blue */
    border: none;
    font-weight: 600;
    padding: 0.85rem 2.5rem;
    font-size: 1.15rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
  }

  .pay-btn:hover {
    background: #0b5ed7;              /* darker blue on hover */
    transform: translateY(-3px) scale(1.03);
    box-shadow: 0 10px 24px rgba(13, 110, 253, 0.4);
  }

  .pay-btn:active {
    transform: translateY(0);
  }

  /* Mobile fix: full-width input + button below */
  @media (max-width: 576px) {
    .input-group {
      flex-direction: column !important;
    }
    
    .input-group .form-control {
      width: 100% !important;
      margin-bottom: 0.75rem !important;
    }
    
    .input-group .btn {
      width: 100% !important;
    }
    
    .input-group .form-control,
    .input-group .btn {
      height: 52px;
      font-size: 1.05rem;
    }

    .pay-btn {
      width: 100%;
      padding: 0.9rem;
    }
  }
</style>
</head>

<body>

<?php include __DIR__ . "../../../components/header.php"; ?>
<div id="navbar-placeholder"></div>

<main class="container my-4">
<div class="main-content">

<h1>Track Paper</h1>
<p>Monitor your manuscript status using the Paper ID provided upon submission.</p>

<div class="card">
<div class="card-body">

<form method="POST">

<label class="form-label">Enter Paper ID</label>

<div class="input-group mb-3">
<input type="text"
       class="form-control"
       name="paper_id"
       placeholder="e.g. IJFER_FEBRUARY_2026_001"
       value="<?php echo htmlspecialchars($searchedPaperId); ?>">

<button class="btn btn-primary" type="submit">
Track Status
</button>
</div>

</form>

<p class="small text-muted">
Status options: Under Review | Revision Requested | Accepted | Published | Rejected
</p>

<?php if ($errorMessage): ?>
<div class="alert alert-danger mt-3">
<?php echo $errorMessage; ?>
</div>
<?php endif; ?>

<?php if ($paperData): ?>

<?php
// Decode authors JSON
$authors = json_decode($paperData['authors'], true);

$authorNames = [];
if (is_array($authors)) {
    foreach ($authors as $author) {
        $authorNames[] = $author['name'] ?? '';
    }
}
$authorList = implode(", ", array_filter($authorNames)) ?: 'Not specified';

// Status logic
$status = strtolower(trim($paperData['status'] ?? ''));
$badgeClass = "bg-secondary";
$alertClass = "alert-success"; // default

if (str_contains($status, 'accept') || $status === 'accepted') {
    $badgeClass = "bg-primary";          // ← Changed to blue for accepted
    $alertClass = "alert-primary";
} elseif (str_contains($status, 'review') || $status === 'under review') {
    $badgeClass = "bg-warning text-dark";
    $alertClass = "alert-warning";
} elseif (str_contains($status, 'revision')) {
    $badgeClass = "bg-danger";
    $alertClass = "alert-warning";
} elseif (str_contains($status, 'publish') || $status === 'published') {
    $badgeClass = "bg-primary";
    $alertClass = "alert-primary";
} elseif (str_contains($status, 'reject') || $status === 'rejected') {
    $badgeClass = "bg-danger";
    $alertClass = "alert-danger";
}
?>

<div class="alert <?php echo $alertClass; ?> mt-4">

<h5 class="mb-3">Paper Details</h5>

<p><strong>Paper ID:</strong> <?php echo htmlspecialchars($paperData['paper_id']); ?></p>

<p><strong>Title:</strong> <?php echo htmlspecialchars($paperData['paper_title']); ?></p>

<p><strong>Research Area:</strong> <?php echo htmlspecialchars($paperData['research_area'] ?? '—'); ?></p>

<p><strong>Authors:</strong> <?php echo htmlspecialchars($authorList); ?></p>

<p><strong>Country:</strong> <?php echo htmlspecialchars($paperData['country'] ?? '—'); ?></p>

<p><strong>Submitted On:</strong> 
<?php 
    echo $paperData['submitted_at'] 
        ? date('d M Y, h:i A', strtotime($paperData['submitted_at'])) 
        : '—'; 
?>
</p>

<p>
<strong>Current Status:</strong> 
<span class="badge <?php echo $badgeClass; ?> px-3 py-2 fs-6">
<?php echo htmlspecialchars($paperData['status'] ?: 'Submitted'); ?>
</span>
</p>

<!-- Pay Now button only for accepted papers -->
<?php if (str_contains($status, 'accept') || $status === 'accepted'): ?>
<div class="text-center mt-5 pt-3">
  <a href="payment.php?paper_id=<?php echo urlencode($paperData['paper_id']); ?>" 
     class="btn pay-btn btn-lg">
    <i class="fas fa-credit-card me-2"></i> Pay Publication Fee
  </a>
  <p class="small text-muted mt-3">
    Click to proceed with the payment and complete the publication process.
  </p>
</div>
<?php endif; ?>

</div>

<?php endif; ?>

</div>
</div>

</div>
</main>

<?php include __DIR__ . "../../../components/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/script.js"></script>

</body>
</html>