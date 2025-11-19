<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn-register {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-register:hover {
            background-color: #218838;
        }
        .form-footer {
            text-align: center;
            margin-top: 15px;
        }
        .form-footer a {
            color: #007bff;
            text-decoration: none;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Đăng ký</h2>
        <form action="?act=register-process" method="post">
            <div class="input-group">
                <label for="ten_dang_nhap">Tên đăng nhập</label>
                <input type="text" name="ten_dang_nhap" id="ten_dang_nhap" placeholder="Tên đăng nhập" required>
            </div>

            <div class="input-group">
                <label for="ho_ten">Họ tên</label>
                <input type="text" name="ho_ten" id="ho_ten" placeholder="Họ tên" required>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <label for="so_dien_thoai">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" id="so_dien_thoai" placeholder="Số điện thoại">
            </div>

            <div class="input-group">
                <label for="password">Mật khẩu</label>
                <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
            </div>

            <div class="input-group">
                <label for="confirm">Nhập lại mật khẩu</label>
                <input type="password" name="confirm" id="confirm" placeholder="Nhập lại mật khẩu" required>
            </div>

            <button type="submit" class="btn-register">Đăng ký</button>
        </form>
    </div>
</body>
</html>