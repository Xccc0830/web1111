<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

include("header.php");
?>

<div class="container mt-4">
    <h2>宿舍管理系統首頁</h2>
    <p>歡迎使用住宿生管理系統。</p>

    <a href="resident_list.php" class="btn btn-primary">住民管理</a>
    <a href="violation_list_all.php" class="btn btn-danger">違規管理</a>
    <a href="checkin_list_all.php" class="btn btn-success">簽到管理</a>
</div>

<?php include("footer.php"); ?>
