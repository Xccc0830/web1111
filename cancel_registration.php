<?php
session_start();
include("db.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$userid = $_SESSION["user"]["account"];
$eventid = intval($_POST["eventid"]);

$stmt = $conn->prepare("DELETE FROM registration WHERE userid = ? AND eventid = ?");
$stmt->bind_param("si", $userid, $eventid);
$stmt->execute();

header("Location: event_detail.php?id=$eventid");
exit;
?>
