<?php
include("db.php");
include("header.php");

// 讀取活動資料，依 ID 最新排序
$sql = "SELECT * FROM event ORDER BY id DESC";
$result = $conn->query($sql);

if (!$result) {
    die("查詢活動失敗：" . $conn->error);
}
?>

<h3>首頁活動資訊</h3>

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
        </div>
      </div>
    </div>
  <?php endwhile; ?>
<?php endif; ?>
</div>

<?php include("footer.php"); ?>
