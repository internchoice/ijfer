<?php
require_once("config/db.php");

if (!isset($_GET['id'])) {
    header("Location: manage_news.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    header("Location: manage_news.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heading = trim($_POST['heading']);
    $description = trim($_POST['description']);
    $link = trim($_POST['news_link']);

    $stmt = $pdo->prepare("UPDATE news SET heading=?, description=?, news_link=? WHERE id=?");
    $stmt->execute([$heading, $description, $link ?: null, $id]);

    header("Location: manage_news.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit News - Admin</title>
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

<div class="main-content">

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            Edit News
        </h1>
        <p class="page-subtitle">
            Update existing news and announcement details
        </p>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>
                <i class="fas fa-pen me-2"></i>
                News Information
            </h4>
        </div>

        <div class="card-body">
            <form method="POST">

                <div class="mb-4">
                    <label class="form-label">Heading *</label>
                    <input type="text" 
                           name="heading" 
                           class="form-control" 
                           value="<?= htmlspecialchars($news['heading']) ?>" 
                           required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Description *</label>
                    <textarea name="description" 
                              class="form-control" 
                              rows="5" 
                              required><?= htmlspecialchars($news['description']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">News Link (Optional)</label>
                    <input type="url" 
                           name="news_link" 
                           class="form-control"
                           placeholder="https://example.com"
                           value="<?= htmlspecialchars($news['news_link']) ?>">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>
                        Update News
                    </button>

                    <a href="manage_news.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>