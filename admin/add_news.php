<?php
require_once("config/db.php");

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heading = trim($_POST['heading'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($heading === '' || $description === '') {
        $errorMessage = "Heading and Description are required.";
    } elseif (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] !== 0) {
        $errorMessage = "Please upload a valid PDF file.";
    } else {
        $ext = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'pdf') {
            $errorMessage = "Only PDF files are allowed.";
        } else {
            $uploadDir = "uploads/news/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);

            $fileName = time() . "_" . preg_replace('/[^a-zA-Z0-9_-]/', '_', basename($_FILES['pdf_file']['name']));
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $filePath)) {
                $stmt = $pdo->prepare("INSERT INTO news (heading, description, pdf_file, created_at) VALUES (:heading, :description, :pdf_file, NOW())");
                $stmt->execute([
                    ':heading' => $heading,
                    ':description' => $description,
                    ':pdf_file' => $filePath
                ]);

                // Keep latest 10 news
                $stmt = $pdo->query("SELECT id, pdf_file FROM news ORDER BY created_at DESC");
                $allNews = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($allNews) > 10) {
                    $toDelete = array_slice($allNews, 10);
                    $ids = array_column($toDelete, 'id');
                    foreach ($toDelete as $news) {
                        if (file_exists($news['pdf_file'])) unlink($news['pdf_file']);
                    }
                    $in = str_repeat('?,', count($ids) - 1) . '?';
                    $stmt = $pdo->prepare("DELETE FROM news WHERE id IN ($in)");
                    $stmt->execute($ids);
                }

                $successMessage = "News added successfully!";
            } else {
                $errorMessage = "Failed to upload PDF.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add News - Admin</title>
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
            <i class="fas fa-newspaper"></i>
            Add News & Announcement
        </h1>
        <p class="page-subtitle">Create new news and announcements for the website</p>
    </div>

    <?php if ($successMessage): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= $successMessage ?>
        </div>
    <?php endif; ?>
    
    <?php if ($errorMessage): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <?= $errorMessage ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h4>
                <i class="fas fa-plus-circle me-2"></i>
                News Details
            </h4>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="form-label">Heading *</label>
                    <input type="text" name="heading" class="form-control" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control" rows="5" required></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">PDF File *</label>
                    <input type="file" name="pdf_file" class="form-control" accept="application/pdf" required>
                    <div class="form-text">Please upload a PDF file for this news item.</div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Add News
                    </button>
                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>