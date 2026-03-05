<?php
require_once "../config/db.php";
require_once "../config/auth.php";

/* ===============================
   HANDLE FORM SUBMISSION (POST)
================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $volume = $_POST['volume'] ?? null;
    $issue = $_POST['issue'] ?? null;

    $allowedStatuses = ['submitted','under_review','accepted','rejected'];

    if (in_array($status, $allowedStatuses)) {

        $stmt = $pdo->prepare("
            UPDATE papers 
            SET status = :status,
                volume = :volume,
                issue = :issue
            WHERE id = :id
        ");

        $stmt->execute([
            ':status' => $status,
            ':volume' => $volume,
            ':issue' => $issue,
            ':id' => $id
        ]);
    }

    header("Location: manage_paper.php");
    exit();
}


/* ===============================
   HANDLE QUICK STATUS UPDATE (GET)
================================= */
if (isset($_GET['id']) && isset($_GET['status'])) {

    $id = (int)$_GET['id'];
    $status = $_GET['status'];

    $allowedStatuses = ['submitted','under_review','accepted','rejected'];

    if (in_array($status, $allowedStatuses) && $status != 'accepted') {

        $stmt = $pdo->prepare("UPDATE papers SET status = :status WHERE id = :id");
        $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);

        header("Location: manage_paper.php");
        exit();
    }
}


/* ===============================
   SHOW ASSIGN FORM (FOR APPROVAL)
================================= */
if (isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM papers WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $paper = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$paper) {
        header("Location: manage_paper.php");
        exit();
    }

} else {
    header("Location: manage_paper.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Volume & Approve - IJFER</title>
    <link rel="stylesheet" href="../assets/css/admin-professional.css">
</head>
<body>

<div class="main-content" style="padding:40px; max-width:600px; margin:auto;">
    <div class="card">
        <div class="card-header">
            <h3>Assign Volume & Issue</h3>
        </div>

        <div class="card-body">
            <form method="POST">

                <input type="hidden" name="id" value="<?php echo $paper['id']; ?>">

                <div class="mb-3">
                    <label>Paper Title</label>
                    <input type="text" class="form-control"
                           value="<?php echo htmlspecialchars($paper['paper_title']); ?>"
                           disabled>
                </div>

                <div class="mb-3">
                    <label>Volume</label>
                    <input type="text"
                           name="volume"
                           class="form-control"
                           value="<?php echo htmlspecialchars($paper['volume'] ?? ''); ?>"
                           placeholder="Example: Volume 7"
                           required>
                </div>

                <div class="mb-3">
                    <label>Issue</label>
                    <input type="text"
                           name="issue"
                           class="form-control"
                           value="<?php echo htmlspecialchars($paper['issue'] ?? ''); ?>"
                           placeholder="Example: Issue 1"
                           required>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="accepted">Accepted</option>
                        <option value="under_review">Under Review</option>
                        <option value="rejected">Rejected</option>
                        <option value="submitted">Submitted</option>
                    </select>
                </div>

                <div style="margin-top:20px;">
                    <button type="submit" class="btn btn-success">
                        Save & Update
                    </button>

                    <a href="manage_paper.php" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>