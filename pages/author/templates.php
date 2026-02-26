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

<?php include "../../components/header.php"; ?>

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

<?php include "../../components/footer.php"; ?>