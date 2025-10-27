<?php
session_start();
if (!isset($_SESSION["user"])) {
  $_SESSION["redirect_to"] = $_SERVER["REQUEST_URI"];
  header("Location: login.php");
  exit;
}
include("header.php");
?>

<h3>迎新茶會報名</h3>

<form method="post" action="success.php">
  <input type="hidden" name="eventid" value="2"> <!-- 活動 ID -->

  <p>姓名：<?= htmlspecialchars($_SESSION["user"]["name"]) ?></p>
  <p>身分：<?= $_SESSION["user"]["role"] == "teacher" ? "老師" : "學生" ?></p>

  <div class="mb-3">
    <label class="form-label">是否需要晚餐？</label><br>
    <input type="radio" name="dinner" value="需要" required> 需要<br>
    <input type="radio" name="dinner" value="不需要"> 不需要
  </div>

  <?php
  if ($_SESSION["user"]["role"] == "teacher") {
    echo "<p>老師免費參加。</p>";
  } else {
    echo "<p>學生自費餐點 60 元。</p>";
  }
  ?>

  <input type="submit" value="送出報名" class="btn btn-success">
</form>

<?php include("footer.php"); ?>
