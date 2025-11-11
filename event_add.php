<?php
session_start();
include("db.php");
include("header.php");

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'M') {
    die("❌ 權限不足，只有管理員可以新增活動。");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    $stmt = $conn->prepare("INSERT INTO event (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $description);

    if ($stmt->execute()) {
        header("Location: /web/index.php");
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
      <label class="form-label">活動名稱：</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">活動描述：</label>
      <textarea name="description" class="form-control" rows="5" required></textarea>
    </div>
    <input type="submit" value="新增活動" class="btn btn-success">
    <a href="/web/index.php" class="btn btn-secondary">取消</a>
  </form>
</div>

<?php include("footer.php"); ?>
