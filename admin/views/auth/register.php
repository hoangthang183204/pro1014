<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký Admin</title>

    <link rel="stylesheet" href="./assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="./assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="./assets/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">

    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a class="h1"><b>Đăng Ký Admin</b></a>
        </div>

        <div class="card-body">

            <?php if (isset($_SESSION['error'])) { ?>
                <p class="text-danger text-center"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); } ?>

            <?php if (isset($_SESSION['success'])) { ?>
                <p class="text-success text-center"><?= $_SESSION['success']; ?></p>
            <?php unset($_SESSION['success']); } ?>

            <form action="?act=register-process" method="post">

                <div class="input-group mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Mật khẩu" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="confirm" placeholder="Nhập lại mật khẩu" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <button class="btn btn-primary btn-block">Đăng ký</button>

            </form>
        </div>
    </div>

</div>
</body>
</html>
