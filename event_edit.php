<?php
session_start();
include("db.php");
include("header.php");


if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'M') {
    die("❌ 權限不足，只有管理員可以修改活動。");
}


$eventid = $_GET['eventid'] ?? '';
if (!$eventid) {
    die("❌ 缺少活動 ID。");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $sql = "UPDATE event SET name=?, description=? WHERE id=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $name, $description, $eventid);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        mysqli_close($conn);
        header("Location: /web/index.php");
        exit;
    } else {
        echo "<p class='text-danger'>❌ 更新失敗：" . mysqli_error($conn) . "</p>";
    }
}

$sql = "SELECT * FROM event WHERE id=?";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "i", $eventid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($result);
?>

<div class="container mt-4">
  <h3>編輯活動</h3>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">活動名稱</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($event['name']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">活動描述</label>
      <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($event['description']) ?></textarea>
    </div>
    <input type="submit" class="btn btn-primary" value="更新活動">
    <a href="/web/index.php" class="btn btn-secondary">取消</a>
  </form>
</div>

<?php include("footer.php"); ?>
