<?php
include("db.php");
include("header.php");

$order = $_POST["order"] ?? ""; // 取得使用者選擇的排序欄位
$sql = "SELECT * FROM job";

if ($order) {
  $sql .= " ORDER BY $order"; // 根據選擇的欄位排序
}

$result = mysqli_query($conn, $sql);
if (!$result) {
  die("查詢失敗：" . mysqli_error($conn));
}
?>

<div class="container mt-4">
  <h3 class="mb-3">職缺列表</h3>

  <form action="job.php" method="post" class="mb-3">
    <select name="order" class="form-select w-auto d-inline-block" aria-label="選擇排序欄位">
      <option value="" <?= ($order == '') ? 'selected' : '' ?>>選擇排序欄位</option>
      <option value="company" <?= ($order == 'company') ? 'selected' : '' ?>>求才廠商</option>
      <option value="content" <?= ($order == 'content') ? 'selected' : '' ?>>求才內容</option>
      <option value="pdate" <?= ($order == 'pdate') ? 'selected' : '' ?>>刊登日期</option>
    </select>
    <input class="btn btn-primary ms-2" type="submit" value="排序">
  </form>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>公司</th>
        <th>職缺內容</th>
        <th>刊登日期</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= htmlspecialchars($row["postid"]) ?></td>
          <td><?= htmlspecialchars($row["company"]) ?></td>
          <td><?= htmlspecialchars($row["content"]) ?></td>
          <td><?= htmlspecialchars($row["pdate"]) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include("footer.php"); ?>
