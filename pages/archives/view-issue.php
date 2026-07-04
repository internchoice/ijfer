<?php
require_once __DIR__ . "/../../admin/config/db.php";

$volume = $_GET['volume'] ?? '';

if (empty($volume)) {
    die("Invalid issue selected.");
}

try {
    $stmt = $pdo->prepare("
        SELECT * FROM papers
        WHERE volume = ?
        AND status = 'accepted'
        ORDER BY submitted_at DESC
    ");
    $stmt->execute([$volume]);
    $papers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<?php include __DIR__ . "/../../components/header.php"; ?>

<main class="container my-4">
    <div class="main-content">

        <h1><?= htmlspecialchars($volume); ?></h1>

        <?php if (!empty($papers)): ?>

        <div class="table-responsive mt-4">
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
                        <td><?= htmlspecialchars($paper['paper_title']); ?></td>
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
            <div class="alert alert-warning mt-4">
                No papers found in this issue.
            </div>
        <?php endif; ?>

        <p class="mt-4">
            <a href="past-issues.php"
               class="btn btn-outline-primary">
               ← Back to Past Issues
            </a>
        </p>

    </div>
</main>

<?php include __DIR__ . "/../../components/footer.php"; ?>