<?php
require_once "../config/db.php";
require_once "../config/auth.php";

if(isset($_GET['id']) && isset($_GET['status'])) {

    $id = (int)$_GET['id'];
    $status = $_GET['status'];

    $allowedStatuses = ['submitted','under_review','accepted','rejected'];

    if(in_array($status, $allowedStatuses)) {

        $stmt = $pdo->prepare("UPDATE papers SET status = :status WHERE id = :id");
        $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }
}

header("Location: manage_paper.php");
exit();
