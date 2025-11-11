<?php
session_start();
include("db.php");
include("header.php");

$order = $_POST["order"] ?? ""; 
$sql = "SELECT * FROM job";

if ($order && in_array($order, ['company', 'content', 'pdate'])) {
    $sql .= " ORDER BY $order"; 
}

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("查詢失敗：" . mysqli_error($conn));
}
?>

<div class="container mt-4">
  <h3 class="mb-3">職缺列表</h3>

  <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'M'): ?>
    <div class="mb-3">
      <a href="job_add.php" class="btn btn-success">新增職缺</a>
    </div>
  <?php endif; ?>

  <form action="job.php" method="post" class="mb-3">
    <select name="order" class="form-select w-auto d-inline-block" aria-label="選擇排序欄位">
      <option value="" <?= ($order == '') ? 'selected' : '' ?>>選擇排序欄位</option>
      <option value="company" <?= ($order == 'company') ? 'selected' : '' ?>>求才廠商</option>
      <option value="content" <?= ($order == 'content') ? 'selected' : '' ?>>求才內容</option>
      <option value="pdate" <?= ($order == 'pdate') ? 'selected' : '' ?>>刊登日期</option>
    </select>
    <input class="btn btn-primary ms-2" type="submit" value="排序">
  </form>

  <table class="table table-bordered table-striped align-middle">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>公司</th>
        <th>職缺內容</th>
        <th>刊登日期</th>
        <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'M'): ?>
          <th>操作</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= htmlspecialchars($row["postid"]) ?></td>
          <td><?= htmlspecialchars($row["company"]) ?></td>
          <td><?= htmlspecialchars($row["content"]) ?></td>
          <td><?= htmlspecialchars($row["pdate"]) ?></td>
          <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'M'): ?>
            <td>

              <a href="job_edit.php?postid=<?= $row['postid'] ?>" class="btn btn-warning btn-sm me-1">修改</a>
              <a href="job_delete.php?id=<?= $row['postid'] ?>" class="btn btn-danger btn-sm"
                 onclick="return confirm('確定要刪除這個職缺嗎？');">刪除</a>
            </td>
          <?php endif; ?>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include("footer.php"); ?>
