<?php
session_start();
include("db.php");
include("header.php");

// 讀取所有活動
$sql = "SELECT * FROM event ORDER BY id DESC";
$result = $conn->query($sql);
if (!$result) {
    die("查詢活動失敗：" . $conn->error);
}
?>

<h3>首頁活動資訊</h3>

<!-- 管理員才顯示新增活動按鈕 -->
<?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'M'): ?>
  <div class="mb-3">
    <a href="/web/event_add.php" class="btn btn-success">➕ 新增活動</a>
  </div>
<?php endif; ?>

<div class="row">
<?php if ($result->num_rows == 0): ?>
  <p class="text-muted">目前沒有活動資料</p>
<?php else: ?>
  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="col-md-6">
      <div class="card mb-3 border-primary shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
          <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>

          <!-- 管理員才顯示編輯 & 刪除按鈕 -->
          <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'M'): ?>
            <a href="/web/event_edit.php?eventid=<?= $row['id'] ?>" class="btn btn-warning btn-sm me-1">編輯活動</a>
            <a href="/web/event_delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
               onclick="return confirm('確定要刪除這個活動嗎？');">刪除活動</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
<?php endif; ?>
</div>

<?php include("footer.php"); ?>
