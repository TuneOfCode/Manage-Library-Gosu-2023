<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nhận mật khẩu mới giành cho thành viên {{ $full_name }}</title>
</head>
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
        color: #333;
    }

    .wrapper {
        display: flex;
        flex-direction: column;
        /* align-items: center;
        justify-content: center; */
    }

    .memberName,
    .email,
    .password {
        color: #e7650e;
        font-weight: bold;
    }

    .wrapper-content {
        /* display: block; */
        margin: 5px;
        color: #333;
        padding: 8px 10px;
    }

    #btnVerify {
        cursor: pointer;
    }

    .link {
        color: #fff;
        background-color: #e7650e;
        padding: 8px 8px;
        border: none;
        border-radius: 5px;
        width: 100px;
        text-align: center;
        margin: 20px auto;
        display: block;
        font-weight: bold;
    }

    footer {
        margin-top: 20px;
    }

    .copyright {
        padding-top: 20px;
        color: #f00;
    }
</style>

<body>
    <div class="wrapper">
        <div class="wrapper-content">
            <h1>Xin chào, <span class="memberName">{{ $full_name }}</span>!</h1>
            <p class="email">Email: {{ $email }}</p>
            <p>
                Đây là mật khẩu mới của bạn: <span class="password">{{ $password }}</span>
            </p>
            <p>
                Hãy đăng nhập và đổi mật khẩu ngay để bảo mật tài khoản của bạn.
            </p>
            <footer>
                Chào bạn,<br>
                <span class="copyright"><strong style="color:'red'">&copy; Thực tập sinh Gosu 2023</strong></span>
            </footer>
        </div>
    </div>
</body>

</html>
