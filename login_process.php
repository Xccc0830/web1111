<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $account = $_POST["account"] ?? '';
    $password = $_POST["password"] ?? '';

    $stmt = $conn->prepare("SELECT account, password, name, role FROM user WHERE account = ?");
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("s", $account);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($row['password'] === $password) {
            $_SESSION['user'] = [
                'account' => $row['account'],
                'name' => $row['name'],
                'role' => $row['role'] // S, T, M
            ];
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error'] = '密碼錯誤';
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['error'] = '帳號不存在';
        header("Location: login.php");
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php");
    exit;
}
?>
