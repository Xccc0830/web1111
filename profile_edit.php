<?php
session_start();
include("db.php");
include("header.php"); 

if (!isset($_SESSION['user'])) {
    die("請先登入");
}

$account = $_SESSION['user']['account'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $stmt = $conn->prepare("SELECT password FROM user WHERE account = ?");
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("s", $account);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    $errors = [];
    if ($new_password) {
        if ($old_password !== $user['password']) { 
            $errors[] = "舊密碼不正確！";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "新密碼與確認密碼不一致！";
        }
    }

    if (empty($errors)) {
        if ($new_password) {
            $stmt = $conn->prepare("UPDATE user SET name=?, password=? WHERE account=?");
            if (!$stmt) die("Prepare failed: " . $conn->error);
            $stmt->bind_param("sss", $name, $new_password, $account); 
        } else {
            $stmt = $conn->prepare("UPDATE user SET name=? WHERE account=?");
            if (!$stmt) die("Prepare failed: " . $conn->error);
            $stmt->bind_param("ss", $name, $account);
        }
        $stmt->execute();
        $stmt->close();

        $_SESSION['user']['name'] = $name;

        echo "<div class='alert alert-success'>資料已更新成功！</div>";
    } else {
        foreach ($errors as $e) {
            echo "<div class='alert alert-danger'>❌ $e</div>";
        }
    }
}

$stmt = $conn->prepare("SELECT name FROM user WHERE account = ?");
$stmt->bind_param("s", $account);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<div class="container mt-4">
  <h3>修改個人資料</h3>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">姓名</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">舊密碼（若要修改密碼請輸入）</label>
      <input type="password" name="old_password" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">新密碼</label>
      <input type="password" name="new_password" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">確認新密碼</label>
      <input type="password" name="confirm_password" class="form-control">
    </div>
    <input type="submit" class="btn btn-primary" value="更新資料">
  </form>
</div>

<?php include("footer.php");  ?>
