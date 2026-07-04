<?php
session_start();
require_once "config/db.php";

$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Contact Messages</title>
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
            <i class="fas fa-envelope"></i>
            Contact Messages
        </h1>
        <p class="page-subtitle">Manage and respond to visitor inquiries</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>
                <i class="fas fa-inbox me-2"></i>
                All Messages
            </h4>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($messages as $msg): ?>
                        <tr>
                            <td><?= $msg['id'] ?></td>
                            <td><?= htmlspecialchars($msg['full_name']) ?></td>
                            <td><?= htmlspecialchars($msg['email']) ?></td>
                            <td>
                                <?php if($msg['status']=='new'): ?>
                                <span class="badge badge-danger">New</span>
                                <?php elseif($msg['status']=='read'): ?>
                                <span class="badge badge-warning">Read</span>
                                <?php else: ?>
                                <span class="badge badge-success">Replied</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date("d M Y", strtotime($msg['created_at'])) ?></td>
                            <td>
                                <a href="view_message.php?id=<?= $msg['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>