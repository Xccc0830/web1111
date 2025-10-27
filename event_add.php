<?php
session_start();
include("db.php");
include("header.php");

// 只有管理員可以新增
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'M') {
    die("❌ 權限不足，只有管理員可以新增活動。");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $description = $_POST['description'] ?? '';

    $stmt = $conn->prepare("INSERT INTO event (description) VALUES (?)");
    $stmt->bind_param("s", $description);

    if ($stmt->execute()) {
        header("Location: index.php"); // 新增後回首頁活動列表
        exit;
    } else {
        echo "<p class='text-danger'>新增失敗：" . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<div class="container mt-4">
  <h3>新增活動</h3>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">活動描述：</label>
      <textarea name="description" class="form-control" required></textarea>
    </div>
    <input type="submit" value="新增活動" class="btn btn-success">
  </form>
</div>

<?php include("footer.php"); ?>
