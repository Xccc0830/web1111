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
  <?php
    include("db.php");
    $eventid = 0;
    $sql = "SELECT id FROM event WHERE name='資管一日營' ORDER BY id DESC LIMIT 1";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $eventid = $row['id'];
    }
    $conn->close();
  ?>
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
    <label class="form-label">選擇時段：</label><br>
    <input type="checkbox" name="session[]" value="上午"> 上午 (150元)<br>
    <input type="checkbox" name="session[]" value="下午"> 下午 (100元)<br>
    <input type="checkbox" name="session[]" value="午餐"> 午餐 (50元)
  </div>

  <input type="submit" value="送出報名" class="btn btn-success">
</form>

<?php include("footer.php"); ?>
