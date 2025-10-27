<?php
session_start();
include("db.php");
include("header.php");

// 檢查是否有傳入活動 ID
if (!isset($_GET['id'])) {
    echo "<p class='text-danger'>未指定活動。</p>";
    include("footer.php");
    exit;
}

$event_id = intval($_GET['id']);

// 讀取活動資料
$sql = "SELECT * FROM event WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p class='text-danger'>找不到該活動。</p>";
    include("footer.php");
    exit;
}

$event = $result->fetch_assoc();
?>

<h3><?= htmlspecialchars($event['name']) ?></h3>
<p><?= nl2br(htmlspecialchars($event['description'])) ?></p>

<!-- 🔹 如果已登入，就顯示報名或取消報名按鈕 -->
<?php if (isset($_SESSION['user'])): ?>
    <?php
    // 檢查是否已報名
    $check_sql = "SELECT * FROM registration WHERE userid = ? AND eventid = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $_SESSION['user']['account'], $event_id);
    $check_stmt->execute();
    $registered = $check_stmt->get_result()->num_rows > 0;
    ?>

    <?php if ($registered): ?>
        <form method="post" action="cancel_registration.php">
            <input type="hidden" name="eventid" value="<?= $event_id ?>">
            <button type="submit" class="btn btn-danger">取消報名</button>
        </form>
    <?php else: ?>
        <form method="post" action="register_event.php">
            <input type="hidden" name="eventid" value="<?= $event_id ?>">
            <button type="submit" class="btn btn-success">我要報名</button>
        </form>
    <?php endif; ?>

<?php else: ?>
    <p>請先 <a href="login.php">登入</a> 後再報名。</p>
<?php endif; ?>

<?php include("footer.php"); ?>
