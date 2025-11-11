<?php
session_start();
if (!isset($_SESSION["user"])) {
    $_SESSION["redirect_to"] = $_SERVER["REQUEST_URI"];
    header("Location: login.php");
    exit;
}
include("header.php");
include("db.php");

$account = $_SESSION["user"]["account"];
$role = $_SESSION["user"]["role"];
$eventid = isset($_POST['eventid']) ? (int)$_POST['eventid'] : 0;

if ($eventid <= 0) {
    echo "<p class='text-danger'>活動不存在！</p>";
    echo "<a href='index.php' class='btn btn-primary'>回首頁</a>";
    include("footer.php");
    exit;
}

// 檢查活動是否存在
$sql_event = "SELECT id, name FROM event WHERE id=?";
$stmt_event = mysqli_prepare($conn, $sql_event);
mysqli_stmt_bind_param($stmt_event, "i", $eventid);
mysqli_stmt_execute($stmt_event);
$result_event = mysqli_stmt_get_result($stmt_event);
$event = mysqli_fetch_assoc($result_event);
mysqli_stmt_close($stmt_event);

if (!$event) {
    echo "<p class='text-danger'>活動不存在，無法報名！</p>";
    echo "<a href='index.php' class='btn btn-primary'>回首頁</a>";
    mysqli_close($conn);
    include("footer.php");
    exit;
}

// 檢查是否已報名
$sql_check = "SELECT id FROM event_registration WHERE account=? AND event_id=?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "si", $account, $eventid);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    echo "<p class='text-warning'>您已經報名過這個活動了！</p>";
    echo "<a href='registration_history.php' class='btn btn-primary'>查看報名紀錄</a>";
    mysqli_stmt_close($stmt_check);
    mysqli_close($conn);
    include("footer.php");
    exit;
}
mysqli_stmt_close($stmt_check);

// 計算費用
$fee = 0;
$detail = "";
if (isset($_POST['session'])) { // 資管一日營
    $sessions = $_POST['session'];
    $detail = "場次：" . implode("、", $sessions);
    if ($role === "S") {
        foreach ($sessions as $s) {
            switch ($s) {
                case "上午": $fee += 150; break;
                case "下午": $fee += 100; break;
                case "午餐": $fee += 50; break;
            }
        }
    }
} elseif (isset($_POST['dinner'])) { // 迎新茶會
    $dinner = $_POST['dinner'];
    $detail = "晚餐：" . $dinner;
    if ($role === "S" && $dinner === "需要") $fee = 60;
}

// 插入報名資料
$sql_insert = "INSERT INTO event_registration (account, event_id) VALUES (?, ?)";
$stmt_insert = mysqli_prepare($conn, $sql_insert);
mysqli_stmt_bind_param($stmt_insert, "si", $account, $eventid);
if (mysqli_stmt_execute($stmt_insert)) {
    echo "<p class='text-success'>報名成功！</p>";
    echo "<ul>";
    echo "<li>活動名稱：" . htmlspecialchars($event['name']) . "</li>";
    echo "<li>$detail</li>";
    echo "<li>應繳費用：$fee 元</li>";
    echo "</ul>";
    echo "<a href='registration_history.php' class='btn btn-primary'>查看報名紀錄</a>";
} else {
    echo "<p class='text-danger'>報名失敗，請聯繫管理員。</p>";
    echo "<a href='index.php' class='btn btn-primary'>回首頁</a>";
}
mysqli_stmt_close($stmt_insert);
mysqli_close($conn);
include("footer.php");
?>
