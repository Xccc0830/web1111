<?php
session_start();

// 預設帳號密碼
$correct_user = "admin";
$correct_pass = "1234";

$account = $_POST["account"] ?? "";
$password = $_POST["password"] ?? "";

// 驗證帳密
if ($account === $correct_user && $password === $correct_pass) {
    $_SESSION["user"] = $account;
    header("Location: index.php");
    exit;
} else {
    $_SESSION["login_error"] = "帳號或密碼錯誤！";
    header("Location: login.php");
    exit;
}
?>
