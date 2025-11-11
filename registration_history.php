<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$account = $_SESSION['user']['account'];

require_once 'db.php';
include('header.php'); // 加入 header
?>

<div class="container mt-4">
    <h3>我的報名記錄</h3>

    <?php
    // 取得報名紀錄
    $sql = "SELECT er.*, e.name as event_name, e.description as event_description
            FROM event_registration er
            JOIN event e ON er.event_id = e.id
            WHERE er.account = ?
            ORDER BY er.registered_at DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $account);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    ?>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <p class="text-muted">您還沒有任何報名記錄。</p>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>活動名稱</th>
                    <th>活動說明</th>
                    <th>報名時間</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['event_name']) ?></td>
                        <td><?= htmlspecialchars($row['event_description']) ?></td>
                        <td><?= $row['registered_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
mysqli_close($conn);
include('footer.php'); // 加入 footer
?>
