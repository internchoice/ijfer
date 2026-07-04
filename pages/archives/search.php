<?php
require_once __DIR__ . "/../../admin/config/db.php";

$keyword = $_GET['keyword'] ?? '';
$year = $_GET['year'] ?? '';

$sql = "SELECT * FROM papers WHERE status='accepted'";
$params = [];

// 🔍 Search by keyword (title, research_area, authors JSON)
if (!empty($keyword)) {
    $sql .= " AND (
        paper_title LIKE ? 
        OR research_area LIKE ? 
        OR authors LIKE ?
    )";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}

// 📅 Filter by year (extract year from volume text)
if (!empty($year)) {
    $sql .= " AND volume LIKE ?";
    $params[] = "%$year%";
}

$sql .= " ORDER BY submitted_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$papers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Papers - IJFER</title>
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

<h1>Search Papers</h1>
<p>Find articles by title, author, keyword, publication year, or volume/issue.</p>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-8">
        <input type="search"
               name="keyword"
               class="form-control"
               placeholder="Search by title, author, keyword..."
               value="<?= htmlspecialchars($keyword); ?>">
    </div>

    <div class="col-md-4">
        <div class="d-flex gap-2">
            <select name="year" class="form-select">
                <option value="">All Years</option>
                <option value="2025" <?= ($year=='2025')?'selected':''; ?>>2025</option>
                <option value="2024" <?= ($year=='2024')?'selected':''; ?>>2024</option>
                <option value="2023" <?= ($year=='2023')?'selected':''; ?>>2023</option>
            </select>

            <button class="btn btn-primary" type="submit">
                Search
            </button>
        </div>
    </div>
</form>

<?php if (!empty($papers)): ?>

<div class="table-responsive">
<table class="table table-bordered table-hover">
<thead class="table-primary">
<tr>
    <th>SR.No</th>
    <th>Title</th>
    <th>Volume</th>
    <th>Download</th>
</tr>
</thead>
<tbody>

<?php $i = 1; ?>
<?php foreach ($papers as $paper): ?>
<tr>
    <td><?= $i++; ?></td>
    <td><?= htmlspecialchars($paper['paper_title']); ?></td>
    <td><?= htmlspecialchars($paper['volume']); ?></td>
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

<p class="mt-3">
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