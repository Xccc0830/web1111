<?php
session_start();
include("db.php");
include("header.php");

// 只有管理員可進入
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'M') {
    die("❌ 權限不足，只有管理員可以新增職缺。");
}

// 處理表單送出
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $company = $_POST['company'] ?? '';
    $content = $_POST['content'] ?? '';
    $pdate = $_POST['pdate'] ?? '';

    $stmt = $conn->prepare("INSERT INTO job (company, content, pdate) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $company, $content, $pdate);

    if ($stmt->execute()) {
        echo "<p class='text-success'>✅ 職缺新增成功！</p>";
        header("Location: job.php"); // 新增完成回列表
        exit;
    } else {
        echo "<p class='text-danger'>❌ 新增失敗：" . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<div class="container mt-4">
  <h3>新增職缺</h3>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">求才廠商：</label>
      <input type="text" name="company" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">職缺內容：</label>
      <textarea name="content" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">刊登日期：</label>
      <input type="date" name="pdate" class="form-control" required>
    </div>
    <input type="submit" value="新增職缺" class="btn btn-success">
  </form>
</div>

<?php include("footer.php"); ?>
