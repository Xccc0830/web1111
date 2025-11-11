<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$account = $_SESSION['user']['account'];
$event_id = $_POST['event_id'] ?? 0;

if ($event_id <= 0) {
    header("Location: index.php?error=" . urlencode("活動 ID 不正確"));
    exit;
}

require_once 'db.php';

// 檢查活動是否存在
$sql_event = "SELECT id FROM event WHERE id=?";
$stmt_event = mysqli_prepare($conn, $sql_event);
mysqli_stmt_bind_param($stmt_event, "i", $event_id);
mysqli_stmt_execute($stmt_event);
mysqli_stmt_store_result($stmt_event);

if (mysqli_stmt_num_rows($stmt_event) === 0) {
    mysqli_stmt_close($stmt_event);
    mysqli_close($conn);
    die("活動不存在！");
}
mysqli_stmt_close($stmt_event);

// 檢查是否已經報名過這個活動
$sql_check = "SELECT id FROM event_registration WHERE account = ? AND event_id = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "si", $account, $event_id);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    mysqli_close($conn);
    header("Location: registration_history.php?msg=" . urlencode("您已經報名過這個活動了"));
    exit;
}
mysqli_stmt_close($stmt_check);

// 插入報名數據
$sql = "INSERT INTO event_registration (account, event_id) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $account, $event_id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: registration_history.php?msg=" . urlencode("報名成功！"));
    exit;
} else {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: index.php?error=" . urlencode("報名失敗，請聯繫管理員"));
    exit;
}
?>
