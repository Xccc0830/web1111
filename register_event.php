<?php
session_start();
include("db.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$userid = $_SESSION["user"]["account"];
$eventid = intval($_POST["eventid"]);

$stmt = $conn->prepare("INSERT IGNORE INTO registration (userid, eventid) VALUES (?, ?)");
$stmt->bind_param("si", $userid, $eventid);
$stmt->execute();

header("Location: event_detail.php?id=$eventid");
exit;
?>
