<?php
session_start();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>登入系統</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-3">系統登入</h3>

                    <?php if (isset($_SESSION["login_error"])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION["login_error"]; unset($_SESSION["login_error"]); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="login_process.php">
                        <div class="mb-3">
                            <label>帳號</label>
                            <input type="text" name="account" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>密碼</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100">登入</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
