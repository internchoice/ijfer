<?php
require_once "../../admin/config/db.php";

$paperData = null;
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $paperId = trim($_POST['paper_id']);

    if (!empty($paperId)) {

        $stmt = $pdo->prepare("SELECT * FROM papers WHERE paper_id = :paper_id");
        $stmt->bindParam(':paper_id', $paperId);
        $stmt->execute();

        $paperData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$paperData) {
            $errorMessage = "Paper ID not found!";
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
       value="<?php echo isset($_POST['paper_id']) ? htmlspecialchars($_POST['paper_id']) : ''; ?>">

<button class="btn btn-primary" type="submit">
Track Status
</button>
</div>

</form>

<p class="small text-muted">
Status options: Under Review | Revision Requested | Accepted | Published
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
        $authorNames[] = $author['name'];
    }
}
$authorList = implode(", ", $authorNames);

// Status badge color
$status = strtolower($paperData['status']);
$badgeClass = "bg-secondary";

if ($status == "accepted") {
    $badgeClass = "bg-success";
} elseif ($status == "under review") {
    $badgeClass = "bg-warning text-dark";
} elseif ($status == "revision requested") {
    $badgeClass = "bg-danger";
} elseif ($status == "published") {
    $badgeClass = "bg-primary";
}
?>

<div class="alert alert-success mt-4">

<h5 class="mb-3">Paper Details</h5>

<p><strong>Paper ID:</strong> <?php echo htmlspecialchars($paperData['paper_id']); ?></p>

<p><strong>Title:</strong> <?php echo htmlspecialchars($paperData['paper_title']); ?></p>

<p><strong>Research Area:</strong> <?php echo htmlspecialchars($paperData['research_area']); ?></p>

<p><strong>Authors:</strong> <?php echo htmlspecialchars($authorList); ?></p>

<p><strong>Country:</strong> <?php echo htmlspecialchars($paperData['country']); ?></p>

<p><strong>Submitted On:</strong> <?php echo htmlspecialchars($paperData['submitted_at']); ?></p>

<p>
<strong>Status:</strong> 
<span class="badge <?php echo $badgeClass; ?> px-3 py-2">
<?php echo htmlspecialchars($paperData['status']); ?>
</span>
</p>

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