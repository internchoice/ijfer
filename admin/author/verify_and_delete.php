<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$paper_id = (int)($_POST['paper_id'] ?? 0);
$entered_password = $_POST['password'] ?? '';

if ($paper_id <= 0 || empty($entered_password)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Get admin password hash
$stmt = $pdo->prepare("SELECT password FROM admins WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    echo json_encode(['success' => false, 'message' => 'Admin not found']);
    exit;
}

// Verify password (change to === if plain text – not recommended)
if (!password_verify($entered_password, $admin['password'])) {
    echo json_encode(['success' => false, 'message' => 'Incorrect password']);
    exit;
}

// Delete paper
try {
    $stmt = $pdo->prepare("DELETE FROM papers WHERE id = ?");
    $stmt->execute([$paper_id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>