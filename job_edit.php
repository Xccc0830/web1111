<?php
session_start();
include("db.php");
include("header.php");

// 檢查管理員身分
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'M') {
    die("❌ 權限不足，只有管理員可以修改職缺。");
}

// 取得職缺 ID
$postid = $_GET["postid"] ?? "";
if (!$postid) {
    die("❌ 缺少職缺 ID。");
}

// 如果按下送出（POST），執行更新
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $company = $_POST["company"];
    $content = $_POST["content"];

    $sql = "UPDATE job SET company=?, content=? WHERE postid=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $company, $content, $postid);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        mysqli_close($conn);
        header("Location: job.php");
        exit;
    } else {
        echo "<p class='text-danger'>❌ 更新失敗：" . mysqli_error($conn) . "</p>";
    }
}

// 否則（第一次載入）從資料庫撈資料
$sql = "SELECT * FROM job WHERE postid=?";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "i", $postid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
?>

<div class="container mt-4">
  <h3 class="mb-3">修改職缺資料</h3>

  <form action="job_edit.php?postid=<?= $postid ?>" method="post">
    <div class="mb-3">
      <label class="form-label">求才廠商：</label>
      <input type="text" name="company" class="form-control"
             value="<?= htmlspecialchars($row['company']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">職缺內容：</label>
      <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($row['content']) ?></textarea>
    </div>

    <input type="submit" value="更新職缺" class="btn btn-primary">
    <a href="job.php" class="btn btn-secondary">取消</a>
  </form>
</div>

<?php include("footer.php"); ?>
