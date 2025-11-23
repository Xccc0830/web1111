<?php
session_start();
include("header.php");
?>

<h1>歡迎來到住民資料管理系統</h1>

<?php if (!empty($_SESSION["user"])): ?>
    <p>你已登入，可以從上方選單管理住民資料。</p>
<?php else: ?>
    <p>請先登入以開始使用系統。</p>
<?php endif; ?>

<?php include("footer.php"); ?>
