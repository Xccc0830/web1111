<?php
session_start();
if (!isset($_SESSION["user"])) {
    $_SESSION["redirect_to"] = $_SERVER["REQUEST_URI"];
    header("Location: login.php");
    exit;
}

include("header.php");
include("db.php");

$userid = $_SESSION["user"]["account"];
$role = $_SESSION["user"]["role"];
$fee = 0;
$activity = "";
$detail = "";
$msg = "";
$sessions_str = "";
$dinner = "";
$eventid = 0;

if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $eventid = (int)$_GET['cancel'];
    $sql_delete = "DELETE FROM registration WHERE userid=? AND eventid=?";
    $stmt_delete = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt_delete, $sql_delete)) {
        mysqli_stmt_bind_param($stmt_delete, "si", $userid, $eventid);
        mysqli_stmt_execute($stmt_delete);
        $msg = "<p class='text-success'>已成功取消報名！</p>";
        mysqli_stmt_close($stmt_delete);
    }
}

if (!empty($_POST)) {
    $eventid = (int)$_POST['eventid'];

    if (isset($_POST['dinner'])) { 
        $activity = "迎新茶會";
        $dinner = $_POST['dinner'];
        $detail = "晚餐：" . $dinner;
        if ($role === "S" && $dinner === "需要") $fee = 60;

    } elseif (isset($_POST['session'])) { 
        $activity = "資管一日營";
        $sessions = $_POST['session'];
        $sessions_str = implode("、", $sessions);
        $detail = "場次：" . $sessions_str;
        if ($role === "S") {
            foreach ($sessions as $s) {
                switch ($s) {
                    case "上午": $fee += 150; break;
                    case "下午": $fee += 100; break;
                    case "午餐": $fee += 50; break;
                }
            }
        }
    }

    $sql_check = "SELECT * FROM registration WHERE userid=? AND eventid=?";
    $stmt_check = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt_check, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, "si", $userid, $eventid);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $msg .= "<p class='text-warning'>您已經報名過這個活動了！</p>";
        } else {
            $sql_insert = "INSERT INTO registration (userid, eventid, sessions, dinner, fee) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt_insert, $sql_insert)) {
                mysqli_stmt_bind_param($stmt_insert, "sissi", $userid, $eventid, $sessions_str, $dinner, $fee);
                mysqli_stmt_execute($stmt_insert);
                $msg .= "<p class='text-success'>報名成功！</p>";
                mysqli_stmt_close($stmt_insert);
            }
        }
        mysqli_stmt_close($stmt_check);
    }
}

echo "<h3>報名資訊</h3>";
echo "<ul>";
echo "<li>姓名：" . htmlspecialchars($_SESSION["user"]["name"]) . "</li>";
echo "<li>身分：" . ($role === "S" ? "學生" : ($role === "T" ? "老師" : "管理員")) . "</li>";

if (!empty($activity)) {
    echo "<li>活動名稱：{$activity}</li>";
    echo "<li>{$detail}</li>";
    echo "<li>應繳費用：{$fee} 元</li>";

    $sql_check2 = "SELECT * FROM registration WHERE userid=? AND eventid=?";
    $stmt_check2 = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt_check2, $sql_check2)) {
        mysqli_stmt_bind_param($stmt_check2, "si", $userid, $eventid);
        mysqli_stmt_execute($stmt_check2);
        mysqli_stmt_store_result($stmt_check2);
        if (mysqli_stmt_num_rows($stmt_check2) > 0) {
            echo "<p><a href='success.php?cancel={$eventid}' class='btn btn-warning'>取消報名</a></p>";
        }
        mysqli_stmt_close($stmt_check2);
    }
}

echo "</ul>";
echo $msg;
mysqli_close($conn);
?>

<a href="index.php" class="btn btn-primary mt-3">回首頁</a>
<?php include("footer.php"); ?>
