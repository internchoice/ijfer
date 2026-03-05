<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../admin/config/db.php";

// Fetch templates
try {
    $stmt = $pdo->query("SELECT * FROM paper_templates ORDER BY uploaded_at DESC");
    $templates = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sample Paper Format - IJFER</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
    <?php include "../../components/header.php"; ?>
    <div id="navbar-placeholder"></div>

    <main class="container my-4">
    <div class="main-content">

        <h1>Sample Paper Format</h1>

        <p>
            Templates and guidelines for preparing your manuscript:
            font type, spacing, headings, tables, figures, and references.
        </p>

        <?php if (!empty($templates)): ?>

            <?php foreach ($templates as $template): ?>

                <div class="mb-3">
                    <a href="/ijfer/<?= htmlspecialchars($template['file_path']); ?>"
                       class="btn btn-outline-primary"
                       download="<?= htmlspecialchars($template['file_name']); ?>">
                        Download <?= htmlspecialchars($template['title']); ?> (DOCX)
                    </a>
                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p class="text-danger">
                No templates available at the moment.
            </p>

        <?php endif; ?>

    </div>
</main>

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/script.js"></script>

<?php include "../../components/footer.php"; ?>
</body>
</html>