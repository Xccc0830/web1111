<?php
session_start();
if (!isset($_SESSION["user"])) {
    $_SESSION["redirect_to"] = $_SERVER["REQUEST_URI"];
    header("Location: login.php");
    exit;
}
include("header.php");
include("db.php");

$eventid = 0;
$sql = "SELECT id FROM event WHERE name='迎新茶會' ORDER BY id DESC LIMIT 1";
$res = $conn->query($sql);
if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $eventid = $row['id'];
}
$conn->close();
?>

<h3>迎新茶會報名</h3>

<form method="post" action="success.php">
  <input type="hidden" name="eventid" value="<?= $eventid ?>"> 

  <p>姓名：<?= htmlspecialchars($_SESSION["user"]["name"]) ?></p>
  <p>身分：
      <?php
        switch($_SESSION["user"]["role"]) {
            case "S": echo "學生"; break;
            case "T": echo "老師"; break;
            case "M": echo "管理員"; break;
            default: echo "未知"; break;
        }
      ?>
  </p>

  <div class="mb-3">
    <label class="form-label">是否需要晚餐？</label><br>
    <input type="radio" name="dinner" value="需要" required> 需要<br>
    <input type="radio" name="dinner" value="不需要"> 不需要
  </div>

  <?php
  if ($_SESSION["user"]["role"] == "T" || $_SESSION["user"]["role"] == "M") {
      echo "<p>老師/管理員免費參加。</p>";
  } else {
      echo "<p>學生自費餐點 60 元。</p>";
  }
  ?>

  <input type="submit" value="送出報名" class="btn btn-success">
</form>

<?php include("footer.php"); ?>
