<?php
session_start();
if (!isset($_SESSION["user"])) {
  $_SESSION["redirect_to"] = $_SERVER["REQUEST_URI"];
  header("Location: login.php");
  exit;
}
include("header.php");
?>

<h3>資管一日營報名</h3>

<form method="post" action="success.php">
  <input type="hidden" name="eventid" value="1"> <!-- 活動 ID -->
  
  <p>姓名：<?= htmlspecialchars($_SESSION["user"]["name"]) ?></p>
  <p>身分：<?= $_SESSION["user"]["role"] == "teacher" ? "老師" : "學生" ?></p>

  <div class="mb-3">
    <label class="form-label">選擇時段：</label><br>
    <input type="checkbox" name="session[]" value="上午"> 上午 (150元)<br>
    <input type="checkbox" name="session[]" value="下午"> 下午 (100元)<br>
    <input type="checkbox" name="session[]" value="午餐"> 午餐 (50元)
  </div>

  <input type="submit" value="送出報名" class="btn btn-success">
</form>

<?php include("footer.php"); ?>
