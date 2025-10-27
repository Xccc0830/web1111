<?php
session_start();
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit;
}
include("header.php");
?>

<h3>報名成功！</h3>
<p>身分：<?= $_SESSION["user"]["role"] == "teacher" ? "老師" : "學生" ?></p>

<?php
$role = $_SESSION["user"]["role"];
$fee = 0;
$activity = "";
$detail = "";

if (!empty($_POST)) {

  if (isset($_POST["dinner"])) {
    $activity = "迎新茶會";
    $detail = "晚餐：" . $_POST["dinner"];
    if ($role == "student" && $_POST["dinner"] == "需要") {
      $fee = 60;
    }
  } elseif (isset($_POST["session"])) {
    $activity = "資管一日營";
    $sessions = $_POST["session"];
    $detail = "場次：" . implode("、", $sessions);

    if ($role == "student") {
      foreach ($sessions as $s) {
        switch ($s) {
          case "上午": $fee += 150; break;
          case "下午": $fee += 100; break;
          case "午餐": $fee += 50; break;
        }
      }
    }
  } else {
    $activity = "未知活動";
    $detail = "（未提供活動內容）";
  }

  echo "<h5 class='mt-4'>您填寫的內容如下：</h5>";
  echo "<ul>";
  echo "<li>活動名稱：{$activity}</li>";
  echo "<li>{$detail}</li>";
  echo "<li>應繳費用：{$fee} 元</li>";
  echo "</ul>";

} else {
  echo "<p>（目前沒有收到任何表單資料）</p>";
}
?>

<a href="index.php" class="btn btn-primary mt-3">回首頁</a>
<?php include("footer.php"); ?>
