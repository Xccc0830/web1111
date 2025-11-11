<?php
session_start();
include("db.php");

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'M') {
    die("❌ 權限不足，只有管理員可以刪除活動。");
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ 缺少活動 ID。");
}

$id = intval($_GET['id']); 

$stmt = $conn->prepare("DELETE FROM event WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: /web/index.php"); 
    exit;
} else {
    die("❌ 刪除失敗：" . $stmt->error);
}

$stmt->close();
$conn->close();
?>
