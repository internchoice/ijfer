<?php
require_once "config/db.php";

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = :id");
$stmt->execute([':id' => $id]);
$message = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$message){
    die("Message not found");
}

if($message['status'] == 'new'){
    $update = $pdo->prepare("UPDATE contact_messages SET status='read' WHERE id=:id");
    $update->execute([':id'=>$id]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message</title>
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
            <i class="fas fa-envelope-open"></i>
            Message Details
        </h1>
        <p class="page-subtitle">View complete message information</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>
                <i class="fas fa-user me-2"></i>
                Sender Information
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><strong>Name:</strong></label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($message['full_name']) ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><strong>Email:</strong></label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($message['email']) ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><strong>Phone:</strong></label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($message['phone']) ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><strong>Inquiry Type:</strong></label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($message['inquiry_type']) ?></p>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label"><strong>Subject:</strong></label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($message['subject']) ?></p>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label"><strong>Message:</strong></label>
                    <div class="p-3 bg-light rounded">
                        <?= nl2br(htmlspecialchars($message['message'])) ?>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><strong>Date:</strong></label>
                    <p class="form-control-plaintext"><?= date("F d, Y g:i A", strtotime($message['created_at'])) ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><strong>Status:</strong></label>
                    <p class="form-control-plaintext">
                        <?php if($message['status'] == 'new'): ?>
                            <span class="badge badge-danger">New</span>
                        <?php elseif($message['status'] == 'read'): ?>
                            <span class="badge badge-warning">Read</span>
                        <?php else: ?>
                            <span class="badge badge-success">Replied</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex gap-2">
                <a href="message.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Messages
                </a>
                <a href="mailto:<?= htmlspecialchars($message['email']) ?>" class="btn btn-primary">
                    <i class="fas fa-reply me-2"></i>
                    Reply via Email
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>