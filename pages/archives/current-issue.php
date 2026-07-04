<?php
require_once __DIR__ . "/../../admin/config/db.php";

$search = $_GET['search'] ?? '';

try {

    // Get latest volume with accepted papers
    $volumeStmt = $pdo->query("
        SELECT DISTINCT volume 
        FROM papers 
        WHERE status = 'accepted' 
        ORDER BY submitted_at DESC 
        LIMIT 1
    ");

    $currentVolume = $volumeStmt->fetchColumn();

    if ($currentVolume) {

        if (!empty($search)) {
            $stmt = $pdo->prepare("
                SELECT * FROM papers 
                WHERE volume = ? 
                AND status = 'accepted'
                AND paper_title LIKE ?
                ORDER BY submitted_at DESC
            ");
            $stmt->execute([$currentVolume, "%$search%"]);
        } else {
            $stmt = $pdo->prepare("
                SELECT * FROM papers 
                WHERE volume = ? 
                AND status = 'accepted'
                ORDER BY submitted_at DESC
            ");
            $stmt->execute([$currentVolume]);
        }

        $papers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {
        $papers = [];
    }

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Current Issue - IJFER</title>
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

        <h1>Current Issue</h1>

        <?php if (!empty($currentVolume)): ?>
            <div class="issue-header bg-light p-3 rounded mb-4">
                <h3 class="mb-0"><?= htmlspecialchars($currentVolume); ?></h3>
            </div>
        <?php endif; ?>

        <!-- 🔎 SEARCH BAR -->
        <form method="GET" class="mb-4">
            <div class="input-group">
                <span class="input-group-text bg-primary text-white">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Search papers by title..."
                       value="<?= htmlspecialchars($search); ?>">
                <button class="btn btn-primary" type="submit">
                    Search
                </button>
            </div>
        </form>

        <?php if (!empty($papers)): ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th style="width:8%">SR.No</th>
                        <th style="width:62%">Paper Title</th>
                        <th style="width:30%">Download</th>
                    </tr>
                </thead>
                <tbody>

                <?php $i = 1; ?>
                <?php foreach ($papers as $paper): ?>
                    <tr>
                        <td><?= $i++; ?></td>

                        <td>
                            <?= htmlspecialchars($paper['paper_title']); ?>
                        </td>

                        <td>
                            <a href="/ijfer/<?= htmlspecialchars($paper['file_path']); ?>"
                               class="btn btn-sm btn-outline-primary"
                               target="_blank">
                               Download
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <?php else: ?>
            <div class="alert alert-warning">
                No papers found.
            </div>
        <?php endif; ?>

        <p>
            <a href="/ijfer/pages/archives/past-issues.php"
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