<?php
require_once __DIR__ . "/../../admin/config/db.php";

try {

    // Get latest volume (Current Issue)
    $currentStmt = $pdo->query("
        SELECT DISTINCT volume 
        FROM papers 
        WHERE status = 'accepted'
        ORDER BY submitted_at DESC
        LIMIT 1
    ");

    $currentVolume = $currentStmt->fetchColumn();

    // Get all past volumes (excluding current)
    $pastStmt = $pdo->prepare("
        SELECT DISTINCT volume 
        FROM papers 
        WHERE status = 'accepted'
        AND volume != ?
        ORDER BY submitted_at DESC
    ");

    $pastStmt->execute([$currentVolume]);
    $pastVolumes = $pastStmt->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Past Issues - IJFER</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <?php include __DIR__ . "/../../components/header.php"; ?>
  <div id="navbar-placeholder"></div>

<main class="container my-4">
    <div class="main-content">

        <h1>Past Issues</h1>
        <p class="lead">
            Browse through our archive of previously published volumes and issues.
        </p>

        <?php if (!empty($pastVolumes)): ?>

            <div class="list-group mt-4">

                <?php foreach ($pastVolumes as $volume): ?>
                    <a href="view-issue.php?volume=<?= urlencode($volume); ?>"
                       class="list-group-item list-group-item-action">
                        <?= htmlspecialchars($volume); ?>
                    </a>
                <?php endforeach; ?>

            </div>

        <?php else: ?>
            <div class="alert alert-warning mt-4">
                No past issues available.
            </div>
        <?php endif; ?>

        <p class="mt-4">
            <a href="/ijfer/pages/archives/current-issue.php"
               class="btn btn-outline-primary">
               ← Back to Archives
            </a>
        </p>

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include __DIR__ . "/../../components/footer.php"; ?>
</body>
</html>