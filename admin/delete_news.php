<?php
require_once("config/db.php");

if (!isset($_GET['id'])) {
    header("Location: manage_news.php");
    exit;
}

$id = (int) $_GET['id'];

// Get file path first
$stmt = $pdo->prepare("SELECT pdf_file FROM news WHERE id = ?");
$stmt->execute([$id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

if ($news) {
    if (!empty($news['pdf_file']) && file_exists($news['pdf_file'])) {
        unlink($news['pdf_file']);
    }

    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: manage_news.php");
exit;