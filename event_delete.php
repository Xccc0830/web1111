<?php
session_start();
include("db.php");

// 1️⃣ 權限檢查
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'M') {
    die("❌ 權限不足，只有管理員可以刪除活動。");
}

// 2️⃣ 檢查是否傳入 id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ 缺少活動 ID。");
}

$id = intval($_GET['id']); // 轉成整數，防止 SQL injection

// 3️⃣ 刪除活動
$stmt = $conn->prepare("DELETE FROM event WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // 刪除成功，回首頁
    header("Location: index.php");
    exit;
} else {
    die("❌ 刪除失敗：" . $stmt->error);
}

$stmt->close();
$conn->close();
?>
