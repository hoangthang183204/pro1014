<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập Admin</title>
    <link rel="stylesheet" href="./assets/plugins/fontawesome-free/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #269269ff, #224abe);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            width: 380px;
            background: #ffffff;
            padding: 35px 40px;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .title {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #224abe;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d3e2;
            border-radius: 10px;
            font-size: 15px;
            transition: 0.2s;
        }

        .input-group input:focus {
            border-color: #4e73df;
            box-shadow: 0 0 5px rgba(78,115,223,0.4);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #4e73df;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-login:hover {
            background: #2e59d9;
        }

        .register-link {
            text-align: center;
            display: block;
            margin-top: 18px;
            font-size: 14px;
            color: #4e73df;
            text-decoration: none;
        }

        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="title">Admin</div>

    <form action="?act=login" method="post">

        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <input type="password" name="password" placeholder="Mật khẩu" required>
        </div>

        <button type="submit" class="btn-login">Đăng nhập</button>
    </form>

    <a href="?act=register" class="register-link">Đăng ký tài khoản Admin</a>
</div>

</body>
</html>
