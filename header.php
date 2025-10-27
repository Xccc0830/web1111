<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("db.php"); // 🔹 連線資料庫
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>活動報名系統</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light px-3">
  <a class="navbar-brand" href="index.php">首頁</a>

  <div class="navbar-nav">
    <!-- 🔹 自動載入活動清單 -->
    <?php
    $sql = "SELECT id, name FROM event ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0):
      while ($row = $result->fetch_assoc()):
    ?>
      <a class="nav-link" href="event_detail.php?id=<?= $row['id'] ?>">
        <?= htmlspecialchars($row['name']) ?>
      </a>
    <?php endwhile; endif; ?>

    <!-- 🔹 其他固定項目 -->
    <a class="nav-link" href="job.php">職缺列表</a>
  </div>

  <div class="ms-auto">
    <?php if (isset($_SESSION["user"])): ?>
      <span class="me-3">歡迎，<?= htmlspecialchars($_SESSION["user"]["name"]) ?>！</span>
      <a href="logout.php" class="btn btn-outline-danger btn-sm">登出</a>
    <?php else: ?>
      <a href="login.php" class="btn btn-outline-primary btn-sm">登入</a>
    <?php endif; ?>
  </div>
</nav>

<div class="container mt-4">
