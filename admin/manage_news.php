<?php
require_once("config/db.php");

$stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC");
$newsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage News - Admin</title>
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
            <i class="fas fa-newspaper"></i>
            Manage News
        </h1>
        <p class="page-subtitle">
            Edit or delete existing news and announcements
        </p>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>
                <i class="fas fa-list me-2"></i>
                News List
            </h4>
        </div>

        <div class="card-body">

            <?php if (count($newsList) === 0): ?>
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-info-circle me-2"></i>
                    No news found.
                </div>
            <?php else: ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="70">ID</th>
                            <th>Heading</th>
                            <th width="180">Created Date</th>
                            <th width="200" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($newsList as $news): ?>
                            <tr>
                                <td><?= $news['id'] ?></td>
                                <td><?= htmlspecialchars($news['heading']) ?></td>
                                <td><?= date('d M Y', strtotime($news['created_at'])) ?></td>
                                <td class="text-center">

                                    <a href="edit_news.php?id=<?= $news['id'] ?>" 
                                       class="btn btn-sm btn-primary me-2">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="delete_news.php?id=<?= $news['id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this news?');">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>