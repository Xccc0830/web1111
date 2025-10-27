<?php
session_start();
include("db.php");

// 1️⃣ 先檢查管理員權限
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'M') {
    die("❌ 權限不足，只有管理員可以刪除職缺。");
}

// 2️⃣ 檢查是否有傳入 id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ 缺少職缺 ID。");
}

$id = intval($_GET['id']); // 轉成整數，防止 SQL injection

// 3️⃣ 刪除職缺
$stmt = $conn->prepare("DELETE FROM job WHERE postid = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // 刪除成功，回列表頁
    header("Location: job.php");
    exit;
} else {
    die("❌ 刪除失敗：" . $stmt->error);
}

$stmt->close();
$conn->close();
?>
