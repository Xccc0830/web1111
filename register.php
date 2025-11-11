<?php
include('check_login.php'); // 檢查登入

if (!isset($_GET['event_id'])) {
    header("Location: index.php");
    exit;
}

$event_id = (int)$_GET['event_id'];

if (session_status() === PHP_SESSION_NONE) session_start();
$account = $_SESSION["account"] ?? "";

require_once 'db.php';

// 檢查是否已經報名過這個活動
$sql_check = "SELECT id FROM event_registration WHERE account = ? AND event_id = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "si", $account, $event_id);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($result_check) > 0) {
    // 已經報名過
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