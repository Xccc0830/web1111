<?php
session_start();
include("db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account = $_POST["account"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE account='$account' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $_SESSION["user"] = $account;
        header("Location: index.php");
        exit;
    } else {
        $error = "帳號或密碼錯誤";
    }
}
?>

<?php include("header.php"); ?>
<h2>登入</h2>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST" class="mt-3">
    <div class="mb-3">
        <label>帳號</label>
        <input type="text" name="account" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>密碼</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button class="btn btn-primary">登入</button>
</form>

<?php include("footer.php"); ?>
