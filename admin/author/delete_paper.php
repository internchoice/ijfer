<?php
require_once "../config/db.php";
require_once "../config/auth.php";

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // First get the file path to delete the actual file
    $stmt = $pdo->prepare("SELECT file_path FROM papers WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $paper = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($paper) {
        // Delete the actual file from server
        $filePath = "../../" . $paper['file_path'];
        if(file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM papers WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}

header("Location: manage_paper.php");
exit();
?>